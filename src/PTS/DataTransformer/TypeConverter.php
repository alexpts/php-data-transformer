<?php
namespace PTS\DataTransformer;

use PTS\DataTransformer\Types;

class TypeConverter
{
    /** @var array */
    protected $types;

    public function __construct()
    {
        $this->types = [
            'proxy' => new Types\BaseType,
            'string' => new Types\StringType,
            'int' => new Types\IntType,
            'array' => new Types\ArrayType,
            'date' => new Types\DateType,
            'float' => new Types\FloatType,
            'bool' => new Types\BoolType,
            'refModelsToArrayStringId' => new Types\RefModelsToArrayStringIdType,
            'refModels' => new Types\RefModelsType,
            'refModel' => new Types\RefModelType
        ];
    }

    /**
     * @param string $name
     * @param Types\BaseType $type
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
     * @param mixed $val
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toData($val, PropRule $propRule, DataTransformer $transformer)
    {
        return $this->getType($propRule)->toData($val, $propRule, $transformer);
    }

    /**
     * @param mixed $val
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toModel($val, PropRule $propRule, DataTransformer $transformer)
    {
        return $this->getType($propRule)->toModel($val, $propRule, $transformer);
    }

    /**
     * @param PropRule $propRule
     * @return Types\BaseType
     */
    protected function getType(PropRule $propRule)
    {
        return $this->types[$propRule->getType()];
    }
}