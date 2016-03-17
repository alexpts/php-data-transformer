<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\DataTransformer;
use PTS\DataTransformer\PropRule;
use Symfony\Component\Yaml\Exception\ParseException;

class RefModelsType
{
    /**
     * @param mixed[] $value
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return array
     *
     *  @throws ParseException
     */
    public function toData(array $value, PropRule $propRule, DataTransformer $transformer)
    {
        $newValue = [];
        $type = $propRule->getKey('rel')['map'];
        foreach ($value as $model) {
            $newValue[] = $transformer->getData($model, $type);
        }

        return $newValue;
    }

    /**
     * @param array[] $value
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed[]
     *
     * @throws ParseException
     */
    public function toModel($value, PropRule $propRule, DataTransformer $transformer)
    {
        $type = $propRule->getKey('rel')['map'];
        $emptyModel = $transformer->createModel($propRule->getKey('rel')['model']);
        $models = [];

        foreach ($value as $one) {
            $model = clone $emptyModel;
            $transformer->fillModel($one, $model, $type);
            $models[] = $model;
        }

        return $models;
    }
}
