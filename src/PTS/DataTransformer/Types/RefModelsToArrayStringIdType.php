<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\ModelInterface;
use Symfony\Component\Yaml\Exception\ParseException;

class RefModelsToArrayStringIdType
{
    /**
     * @param ModelInterface[] $value
     * @return array
     *
     *  @throws ParseException
     */
    public function toData(array $value)
    {
        $newValue = [];
        foreach ($value as $model) {
            $relId = is_object($model) ? $model->getId() : (string)$model;
            $newValue[] = $relId;
        }

        return $newValue;
    }

    /**
     * @param array[] $value
     * @return array
     *
     * @throws ParseException
     */
    public function toModel($value)
    {
       return array_map('strval', $value);
    }
}