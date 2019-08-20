<?php
declare(strict_types=1);

namespace ParseUp;

use ParseUp\Exception\ConverterNotFoundException;
use ParseUp\Exception\InvalidMarkDownException;
use ParseUp\Exception\NoTitleFoundException;
use ParseUp\Converter\Interfaces\BlockWithoutEndTag;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Converter\Interfaces\Nestable;
use ParseUp\Utility\BlockStack;
use ParseUp\Utility\ConverterCollection;
use ParseUp\Utility\EntityTypeCollection;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\File;
use ParseUp\ValueObject\Line;

class MarkDownToHtml implements MarkDownParser
{
    /**
     * @var File
     */
    private $markDown;

    /**
     * @var int
     */
    private $lineNumber = 0;

    /**
     * @var array
     */
    private $html = [];

    /**
     * @var ConverterCollection
     */
    private $converters;

    /**
     * @var Nestable
     */
    private $converterForCurrentNesting;

    /**
     * @var BlockStack
     */
    private $blockStack;

    /**
     * @var EntityTypeCollection
     */
    private $specialBlockEntities;

    /**
     * @var int
     */
    private $linesProcessed = 0;


    public function __construct(ConverterCollection $converters)
    {
        $this->converters = $converters;
        $this->blockStack = new BlockStack();

        $specialBlockEntities = new EntityTypeCollection();
        $specialBlockEntities->addEntityType(EntityType::indentedCode());
        $specialBlockEntities->addEntityType(EntityType::orderedList());
        $specialBlockEntities->addEntityType(EntityType::unorderedList());
        $specialBlockEntities->addEntityType(EntityType::blockQuote());

        $this->specialBlockEntities = $specialBlockEntities;

    }

    public function linesProcessed(): int
    {
        return $this->linesProcessed;
    }

    public function loadFile(File $markDown)
    {
        $this->html = [];
        $this->markDown = $markDown;
    }

    /**
     * @throws NoTitleFoundException
     */
    public function getTitle(): string
    {
        if (preg_match('/^#\s*(.+)/', $this->markDown->asString(), $matches)) {
            return trim($matches[1]);
        }

        if (preg_match('/^(.+)\R+\s*==/', $this->markDown->asString(), $matches)) {
            return trim($matches[1]);
        }

        throw new NoTitleFoundException();
    }

    /**
     * @throws ConverterNotFoundException
     * @throws InvalidMarkDownException
     */
    public function convert(): string
    {
        foreach ($this->markDown->lines() as $lineNumber => $line) {

            $this->lineNumber = $lineNumber;

            $line = new Line($line);

            if ($this->converterForCurrentNesting && $this->converterForCurrentNesting->isNesting()) {
                $this->processNestedLine($line);
            }

            if ($this->canCompleteSpecialCase($line)) {
                continue;
            }

            foreach($this->converters->iterator() as $converter) {

                /** @var MarkDownLineConverter $converter */
                if ($converter->canConvert($line)) {

                    $converter->convert($line, $this->blockStack, $this->html);

                    if ($converter->isEndOfBlock()) {
                        break;
                    }
                }
            }

            $this->html[] = $line->asString();

        }

        $this->checkFinalBlockStack();

        $this->linesProcessed = $this->lineNumber;

        return join("", $this->html);
    }

    /**
     * @throws ConverterNotFoundException
     */
    private function canCompleteSpecialCase(Line $line): bool
    {

        if ($this->blockStack->isEmpty()) {
            return false;
        }

        $entityType = $this->blockStack->peek();

        if ($this->isInCodeBlock($entityType, $line)) {
            $line->htmlEntities();
            $this->html[] = $line->asString();
            return true;
        }

        if ($this->isFinishedWithBlock($entityType, $line)) {
            $this->closeBlock($entityType);
            return false;
        }
        else if ($entityType->equals(EntityType::indentedCode())) {
            $this->converters->getConverterType($entityType)->convert($line, $this->blockStack);
            $this->html[] = $line->asString();
            return true;
        }

        return false;
    }

    /**

     * @throws ConverterNotFoundException
     */
    private function isInCodeBlock(EntityType $entityType, Line $line): bool
    {
        return (
            $entityType->equals(EntityType::code()) &&
            !$this->converters->getConverterType($entityType)->canConvert($line)
        );
    }

    /**
     * @throws ConverterNotFoundException
     */
    private function isFinishedWithBlock(EntityType $entityType, Line $line): bool
    {
        /** @var MarkDownLineConverter | Nestable $converter */

        if (!$this->specialBlockEntities->has($entityType)) {
            return false;
        }

        $converter = $this->converters->getConverterType($entityType);

        $this->ensureNestableInstance($converter);

        if ($converter->canConvert($line) && $entityType->equals(EntityType::blockQuote())) {

            $matches = $line->getMatches('/^(>+)/');

            $indentionLevel = strlen($matches[1]);

            if ($indentionLevel > $converter->currentIndentionLevel()) {

                $this->setNestingMode($line, $converter);

                return false;
            }
        }
        else if (!$converter->canConvert($line)) {

            $indentionLevel = $line->getIndentionLevel();

            if ($indentionLevel > $converter->currentIndentionLevel()) {

                $this->setNestingMode($line, $converter);

                return false;
            }

            return true;
        }

        return false;
    }

    private function processNestedLine(Line $line)
    {
        $converter = $this->converterForCurrentNesting;
        $tabsToRemove = $converter->currentIndentionLevel() + 1;
        $line->reduceIndentionBy($tabsToRemove);
    }

    private function setNestingMode(Line $line, Nestable $converter)
    {
        $converter->setIsNesting();
        $this->converterForCurrentNesting = $converter;
        $this->processNestedLine($line);
        $this->blockStack->push(EntityType::nested());
    }

    /**
     * @throws ConverterNotFoundException
     */
    private function closeBlock(EntityType $entityType)
    {
        $converter = $this->converters->getConverterType($entityType);

        $this->ensureBlockWithoutEndTagInstance($converter);

        /** @var BlockWithoutEndTag $converter */
        $this->html[] = $converter->endTag();
        $this->blockStack->pop();
        if (!$this->blockStack->peek()->equals(EntityType::nested())) {
            $this->blockStack->push(EntityType::blockEnd());
        }
    }

    /**
     * @throws InvalidMarkDownException
     */
    private function checkFinalBlockStack()
    {
        if (!$this->blockStack->isEmpty()) {
            if ($this->blockStack->peek()->equals(EntityType::blockEnd())) {
                $this->blockStack->pop();
            }

            if ($this->blockStack->peek()->equals(EntityType::paragraph())) {
                $this->html[] = '</p>';
                $this->blockStack->pop();
            }

            if (!$this->blockStack->isEmpty()) {
                throw new InvalidMarkDownException(
                    sprintf('Parsing markdown for file "%s" failed. %s opened, but never closed.',
                        $this->markDown->name(),
                        $this->blockStack->pop()->asString()
                    )
                );
            }
        }
    }

    /**
     * @throws ConverterNotFoundException
     */
    private function ensureBlockWithoutEndTagInstance(MarkDownLineConverter $converter): void
    {
        if (!($converter instanceof BlockWithoutEndTag)) {
            throw new ConverterNotFoundException(
                sprintf(
                    'The requested converter of type %s does not implement BlockWithoutEndTag',
                    $converter->type()->asString()
                )
            );
        }
    }

    /**
     * @throws ConverterNotFoundException
     */
    private function ensureNestableInstance(MarkDownLineConverter $converter): void
    {
        if (!($converter instanceof Nestable)) {
            throw new ConverterNotFoundException(
                sprintf(
                    'The requested converter of type %s does not implement Nestable',
                    $converter->type()->asString()
                )
            );
        }
    }
}