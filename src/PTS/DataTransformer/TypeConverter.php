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
            'string' => new Types\StringType,
            'int' => new Types\IntType,
            'array' => new Types\ArrayType,
            'date' => new Types\DateType,
            'mongoDate' => new Types\MongoDateType,
            'id' => new Types\MongoIdType,
            'float' => new Types\FloatType,
            'bool' => new Types\BoolType,
            'refModels' => new Types\RefModelsType,
            'refModel' => new Types\RefModelType,
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
     * @param array $prop
     * @return mixed
     */
    public function toStorage($val, array $prop)
    {
        return $this->types[$prop['type']]->toStorage($val);
    }

    /**
     * @param mixed $val
     * @param array $prop
     * @return mixed
     */
    public function toModel($val, array $prop)
    {
        return $this->types[$prop['type']]->toModel($val);
    }
}