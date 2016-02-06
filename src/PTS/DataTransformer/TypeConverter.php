<?php
namespace PTS\DataTransformer;

use PTS\DataTransformer\Types;

class TypeConverter
{
    /** @var Types\BaseType[] */
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
    public function addType($name, Types\BaseType $type)
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
     * @param array $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toData($val, array $propRule, DataTransformer $transformer)
    {
        return $this->types[$propRule['type']]->toData($val, $propRule, $transformer);
    }

    /**
     * @param mixed $val
     * @param array $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toModel($val, array $propRule, DataTransformer $transformer)
    {
        return $this->types[$propRule['type']]->toModel($val, $propRule, $transformer);
    }
}