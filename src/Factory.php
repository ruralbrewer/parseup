<?php
declare(strict_types=1);

namespace ParseUp;

use ParseUp\Converter\BlockQuoteConverter;
use ParseUp\Converter\BoldCloserConverter;
use ParseUp\Converter\BoldItalicCloserConverter;
use ParseUp\Converter\BoldItalicOpenerConverter;
use ParseUp\Converter\BoldOpenerConverter;
use ParseUp\Converter\EmptyLineConverter;
use ParseUp\Converter\EscapedCharactersConverter;
use ParseUp\Converter\IndentedCodeBlockConverter;
use ParseUp\Converter\InlineBoldItalicConverter;
use ParseUp\Converter\InlineCodeBlockConverter;
use ParseUp\Converter\CodeBlockConverter;
use ParseUp\Converter\HeaderConverter;
use ParseUp\Converter\HorizontalRuleConverter;
use ParseUp\Converter\ImageConverter;
use ParseUp\Converter\InlineBoldConverter;
use ParseUp\Converter\InlineItalicConverter;
use ParseUp\Converter\ItalicCloserConverter;
use ParseUp\Converter\ItalicOpenerConverter;
use ParseUp\Converter\LineBreakConverter;
use ParseUp\Converter\LinkConverter;
use ParseUp\Converter\OrderedListConverter;
use ParseUp\Converter\ParagraphConverter;
use ParseUp\Converter\QuickEmailConverter;
use ParseUp\Converter\QuickLinkConverter;
use ParseUp\Converter\ReferenceLinkConverter;
use ParseUp\Converter\UnderlinedHeaderConverter;
use ParseUp\Converter\UnorderedListConverter;
use ParseUp\Utility\ConverterCollection;

class Factory
{
    public function createParser()
    {
        return new MarkDownToHtml($this->createConverterCollection());
    }

    private function createConverterCollection()
    {
        $converters = [
            new EscapedCharactersConverter(),
            new QuickLinkConverter(),
            new QuickEmailConverter(),
            new BlockQuoteConverter(),
            new CodeBlockConverter(),
            new IndentedCodeBlockConverter(),
            new InlineCodeBlockConverter(),
            new InlineBoldItalicConverter(),
            new BoldItalicOpenerConverter(),
            new BoldItalicCloserConverter(),
            new HorizontalRuleConverter(),
            new ReferenceLinkConverter(),
            new ImageConverter(),
            new LinkConverter(),
            new InlineBoldConverter(),
            new BoldOpenerConverter(),
            new BoldCloserConverter(),
            new InlineItalicConverter(),
            new ItalicOpenerConverter(),
            new ItalicCloserConverter(),
            new HeaderConverter(),
            new UnderlinedHeaderConverter(),
            new UnorderedListConverter(),
            new OrderedListConverter(),
            new ParagraphConverter(),
            new LineBreakConverter(),
            new EmptyLineConverter()
        ];

        return ConverterCollection::fromArray($converters);
    }
}