<?php
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
     * @return $this
     */
    public function addType($name, $type)
    {
        $this->types[$name] = $type;
        return $this;
    }

    /**
     * @return Types\BaseType[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param string $direction
     * @param mixed $val
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    protected function convert($direction, $val, PropRule $propRule, DataTransformer $transformer)
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

    /**
     * @param mixed $val
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toData($val, PropRule $propRule, DataTransformer $transformer)
    {
        return $this->convert('toData', $val, $propRule, $transformer);
    }

    /**
     * @param mixed $val
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toModel($val, PropRule $propRule, DataTransformer $transformer)
    {
        return $this->convert('toModel', $val, $propRule, $transformer);
    }

    /**
     * @param PropRule $propRule
     * @return array
     */
    protected function getType(PropRule $propRule)
    {
        $isCollection = $propRule->getKey('coll', false);
        $type = $this->types[$propRule->getType()];
        return [$type, $isCollection];
    }
}
