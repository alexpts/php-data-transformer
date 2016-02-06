<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\DataTransformer;
use PTS\DataTransformer\ModelInterface;
use Symfony\Component\Yaml\Exception\ParseException;

class RefModelsType
{
    /**
     * @param string[]|ModelInterface[] $value
     * @param array $propRule
     * @param DataTransformer $transformer
     * @return mixed[]
     *
     *  @throws ParseException
     */
    public function toData(array $value, array $propRule, DataTransformer $transformer)
    {
        $newValue = [];
        $type = $propRule['rel']['map'];
        foreach ($value as $model) {
            $newValue[] = $transformer->getData($model, $type);
        }

        return $newValue;
    }

    /**
     * @param array[] $value
     * @param array $propRule
     * @param DataTransformer $transformer
     * @return ModelInterface
     *
     * @throws ParseException
     */
    public function toModel($value, array $propRule, DataTransformer $transformer)
    {
        $type = $type = $propRule['rel']['map'];
        $emptyModel = $transformer->createModel($propRule['rel']['model']);
        $models = [];

        foreach ($value as $one) {
            $model = clone $emptyModel;
            $transformer->fillModel($one, $model, $type);
            $models[] = $model;
        }

        return $models;
    }
}