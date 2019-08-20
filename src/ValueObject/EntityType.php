<?php
declare(strict_types=1);

namespace ParseUp\ValueObject;

class EntityType
{
    private $value;

    private const ESCAPED_CHARACTERS = 'ESCAPED_CHARACTERS';
    private const CODE = 'CODE';
    private const INDENTED_CODE = 'INDENTED_CODE';
    private const INLINE_CODE = 'INLINE_CODE';
    private const UNORDERED_LIST = 'UNORDERED_LIST';
    private const ORDERED_LIST = 'ORDERED_LIST';
    private const BLOCK_QUOTE = 'BLOCK_QUOTE';
    private const HEADER = 'HEADER';
    private const UNDERLINED_HEADER = 'UNDERLINED_HEADER';
    private const INLINE_BOLD_ITALIC = 'INLINE_BOLD_ITALIC';
    private const INLINE_BOLD = 'INLINE_BOLD';
    private const BOLD_OPENER = 'BOLD_OPENER';
    private const BOLD_CLOSER = 'BOLD_CLOSER';
    private const BOLD_ITALIC_OPENER = 'BOLD_ITALIC_OPENER';
    private const BOLD_ITALIC_CLOSER = 'BOLD_ITALIC_CLOSER';
    private const INLINE_ITALIC = 'INLINE_ITALIC';
    private const ITALIC_OPENER = 'ITALIC_OPENER';
    private const ITALIC_CLOSER = 'ITALIC_CLOSER';
    private const PARAGRAPH = 'PARAGRAPH';
    private const HORIZONTAL_RULE = 'HORIZONTAL_RULE';
    private const IMAGE = 'IMAGE';
    private const LINK = 'LINK';
    private const REFERENCE_LINK = 'REFERENCE_LINK';
    private const QUICK_LINK = 'QUICK_LINK';
    private const QUICK_EMAIL = 'QUICK_EMAIL';
    private const EMPTY_LINE = 'EMPTY_LINE';
    private const LINE_BREAK = 'LINE_BREAK';
    private const BLOCK_END = 'BLOCK_END';
    private const NESTED = 'NESTED';
    private const NULL = 'NULL';

    public static function escapedCharacters()
    {
        return new static(self::ESCAPED_CHARACTERS);
    }

    public static function code()
    {
        return new static(self::CODE);
    }

    public static function indentedCode()
    {
        return new static(self::INDENTED_CODE);
    }

    public static function inlineCode()
    {
        return new static(self::INLINE_CODE);
    }

    public static function unorderedList()
    {
        return new static(self::UNORDERED_LIST);
    }

    public static function orderedList()
    {
        return new static(self::ORDERED_LIST);
    }

    public static function blockQuote()
    {
        return new static(self::BLOCK_QUOTE);
    }

    public static function header()
    {
        return new static(self::HEADER);
    }

    public static function underlinedHeader()
    {
        return new static(self::UNDERLINED_HEADER);
    }

    public static function inlineBoldItalic()
    {
        return new static(self::INLINE_BOLD_ITALIC);
    }

    public static function inlineBold()
    {
        return new static(self::INLINE_BOLD);
    }

    public static function boldOpener()
    {
        return new static(self::BOLD_OPENER);
    }

    public static function boldCloser()
    {
        return new static(self::BOLD_CLOSER);
    }

    public static function boldItalicOpener()
    {
        return new static(self::BOLD_ITALIC_OPENER);
    }

    public static function boldItalicCloser()
    {
        return new static(self::BOLD_ITALIC_CLOSER);
    }

    public static function inlineItalic()
    {
        return new static(self::INLINE_ITALIC);
    }

    public static function italicCloser()
    {
        return new static(self::ITALIC_CLOSER);
    }

    public static function italicOpener()
    {
        return new static(self::ITALIC_OPENER);
    }

    public static function paragraph()
    {
        return new static(self::PARAGRAPH);
    }

    public static function horizontalRule()
    {
        return new static(self::HORIZONTAL_RULE);
    }

    public static function image()
    {
        return new static(self::IMAGE);
    }

    public static function link()
    {
        return new static(self::LINK);
    }

    public static function referenceLink()
    {
        return new static(self::REFERENCE_LINK);
    }

    public static function quickLink()
    {
        return new static(self::QUICK_LINK);
    }

    public static function quickEmail()
    {
        return new static(self::QUICK_EMAIL);
    }

    public static function emptyLine()
    {
        return new static(self::EMPTY_LINE);
    }

    public static function lineBreak()
    {
        return new static(self::LINE_BREAK);
    }

    public static function blockEnd()
    {
        return new static(self::BLOCK_END);
    }

    public static function nested()
    {
        return new static(self::NESTED);
    }

    public static function null()
    {
        return new static(self::NULL);
    }

    private function __construct(string $entityType)
    {
        $this->value = $entityType;
    }

    public function asString(): string
    {
        return $this->value;
    }

    public function equals(EntityType $entityType): bool
    {
        return $this->value === $entityType->asString();
    }
}