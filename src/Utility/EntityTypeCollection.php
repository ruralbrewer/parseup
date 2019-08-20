<?php
declare(strict_types=1);

namespace ParseUp\Utility;

use ParseUp\ValueObject\EntityType;

class EntityTypeCollection
{
    /**
     * @var array EntityType[]
     */
    private $entityTypes = [];

    public static function fromArray(array $entityTypes)
    {
        $collection = new static;

        foreach ($entityTypes as $entityType) {
            $collection->addEntityType($entityType);
        }

        return $collection;
    }

    public function addEntityType(EntityType $entityType)
    {
        $this->entityTypes[$entityType->asString()] = $entityType;
    }

    public function has(EntityType $entityType): bool
    {
        return isset($this->entityTypes[$entityType->asString()]);
    }

    public function iterator()
    {
        return new \ArrayIterator($this->entityTypes);
    }
}