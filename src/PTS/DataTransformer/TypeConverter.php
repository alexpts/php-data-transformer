<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PTS\DataTransformer\Types;

class TypeConverter
{
    /** @var array */
    protected $types = [];

    public function __construct()
    {
        $this->types = [];
    }

    /**
     * @param string $name
     * @param Types\* $type
     *
     * @return $this
     *
     * @throws TypeException
     */
    public function addType(string $name, $type)
    {
        if (!method_exists($type, 'toModel')) {
            throw new TypeException('Type must implement a method toModel');
        }

        if (!method_exists($type, 'toData')) {
            throw new TypeException('Type must implement a method toData');
        }

        $this->types[$name] = $type;
        return $this;
    }

    /**
     * @return Types\BaseType[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param string $direction
     * @param mixed $val
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     *
     * @return mixed
     */
    protected function convert(string $direction, $val, PropRule $propRule, DataTransformer $transformer)
    {
        list($type, $isCollection) = $this->getType($propRule);
        if (!$isCollection) {
            return $type->{$direction}($val, $propRule, $transformer);
        }

        $collection = [];
        foreach ($val as $itemVal) {
            $collection[] = $type->{$direction}($itemVal, $propRule, $transformer);
        }

        return $collection;
    }

    public function toData($val, PropRule $propRule, DataTransformer $transformer)
    {
        return $this->convert('toData', $val, $propRule, $transformer);
    }

    public function toModel($val, PropRule $propRule, DataTransformer $transformer)
    {
        return $this->convert('toModel', $val, $propRule, $transformer);
    }

    protected function getType(PropRule $propRule): array
    {
        $isCollection = $propRule->getKey('coll', false);
        $type = $this->types[$propRule->getType()];
        return [$type, $isCollection];
    }
}
