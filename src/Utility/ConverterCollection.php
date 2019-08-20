<?php
declare(strict_types=1);

namespace ParseUp\Utility;

use ParseUp\Exception\ConverterNotFoundException;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\ValueObject\EntityType;

class ConverterCollection
{
    /**
     * @var array MarkDownLineConverter[]
     */
    private $converters = [];

    public static function fromArray(array $converters): ConverterCollection
    {
        $collection = new static;

        foreach ($converters as $converter) {
            $collection->addConverter($converter);
        }

        return $collection;
    }

    public function addConverter(MarkDownLineConverter $converter): void
    {
        $this->converters[$converter->type()->asString()] = $converter;
    }

    public function hasConverterType(EntityType $entityType): bool
    {
        return isset($this->converters[$entityType->asString()]);
    }

    /**
     * @throws ConverterNotFoundException
     */
    public function getConverterType(EntityType $type): MarkDownLineConverter
    {
        if (!isset($this->converters[$type->asString()])) {
            throw new ConverterNotFoundException(
                sprintf('Converter of type %s not found in collection.', $type->asString())
            );
        }

        return $this->converters[$type->asString()];
    }

    public function iterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->converters);
    }
}