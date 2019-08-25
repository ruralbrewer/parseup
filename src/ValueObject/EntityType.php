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
    private const TABLE = 'TABLE';
    private const HEADER = 'HEADER';
    private const UNDERLINED_HEADER = 'UNDERLINED_HEADER';
    private const INLINE_BOLD_ITALIC = 'INLINE_BOLD_ITALIC';
    private const STRIKE_THROUGH = 'STRIKE_THROUGH';
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
    private const FOOT_NOTE_LINK = 'FOOT_NOTE_LINK';
    private const FOOT_NOTE = 'FOOT_NOTE';
    private const LINK = 'LINK';
    private const REFERENCE_LINK = 'REFERENCE_LINK';
    private const TASK_LIST = 'TASK_LIST';
    private const QUICK_LINK = 'QUICK_LINK';
    private const QUICK_EMAIL = 'QUICK_EMAIL';
    private const EMPTY_LINE = 'EMPTY_LINE';
    private const LINE_BREAK = 'LINE_BREAK';
    private const BLOCK_END = 'BLOCK_END';
    private const NESTED = 'NESTED';
    private const NULL = 'NULL';

    public static function escapedCharacters(): EntityType
    {
        return new static(self::ESCAPED_CHARACTERS);
    }

    public static function code(): EntityType
    {
        return new static(self::CODE);
    }

    public static function indentedCode(): EntityType
    {
        return new static(self::INDENTED_CODE);
    }

    public static function inlineCode(): EntityType
    {
        return new static(self::INLINE_CODE);
    }

    public static function unorderedList(): EntityType
    {
        return new static(self::UNORDERED_LIST);
    }

    public static function orderedList(): EntityType
    {
        return new static(self::ORDERED_LIST);
    }

    public static function blockQuote(): EntityType
    {
        return new static(self::BLOCK_QUOTE);
    }

    public static function table(): EntityType
    {
        return new static(self::TABLE);
    }

    public static function header(): EntityType
    {
        return new static(self::HEADER);
    }

    public static function underlinedHeader(): EntityType
    {
        return new static(self::UNDERLINED_HEADER);
    }

    public static function inlineBoldItalic(): EntityType
    {
        return new static(self::INLINE_BOLD_ITALIC);
    }

    public static function inlineBold(): EntityType
    {
        return new static(self::INLINE_BOLD);
    }

    public static function boldOpener(): EntityType
    {
        return new static(self::BOLD_OPENER);
    }

    public static function boldCloser(): EntityType
    {
        return new static(self::BOLD_CLOSER);
    }

    public static function boldItalicOpener(): EntityType
    {
        return new static(self::BOLD_ITALIC_OPENER);
    }

    public static function boldItalicCloser(): EntityType
    {
        return new static(self::BOLD_ITALIC_CLOSER);
    }

    public static function inlineItalic(): EntityType
    {
        return new static(self::INLINE_ITALIC);
    }

    public static function italicCloser(): EntityType
    {
        return new static(self::ITALIC_CLOSER);
    }

    public static function italicOpener(): EntityType
    {
        return new static(self::ITALIC_OPENER);
    }

    public static function strikeThrough(): EntityType
    {
        return new static(self::STRIKE_THROUGH);
    }

    public static function paragraph(): EntityType
    {
        return new static(self::PARAGRAPH);
    }

    public static function horizontalRule(): EntityType
    {
        return new static(self::HORIZONTAL_RULE);
    }

    public static function image(): EntityType
    {
        return new static(self::IMAGE);
    }

    public static function footNoteLink(): EntityType
    {
        return new static(self::FOOT_NOTE_LINK);
    }

    public static function footNote(): EntityType
    {
        return new static(self::FOOT_NOTE);
    }

    public static function link(): EntityType
    {
        return new static(self::LINK);
    }

    public static function referenceLink(): EntityType
    {
        return new static(self::REFERENCE_LINK);
    }

    public static function taskList(): EntityType
    {
        return new static(self::TASK_LIST);
    }

    public static function quickLink(): EntityType
    {
        return new static(self::QUICK_LINK);
    }

    public static function quickEmail(): EntityType
    {
        return new static(self::QUICK_EMAIL);
    }

    public static function emptyLine(): EntityType
    {
        return new static(self::EMPTY_LINE);
    }

    public static function lineBreak(): EntityType
    {
        return new static(self::LINE_BREAK);
    }

    public static function blockEnd(): EntityType
    {
        return new static(self::BLOCK_END);
    }

    public static function nested(): EntityType
    {
        return new static(self::NESTED);
    }

    public static function null(): EntityType
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