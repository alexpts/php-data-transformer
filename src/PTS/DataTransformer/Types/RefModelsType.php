<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\ModelInterface;

class RefModelsType
{
    /**
     * @param string[]|ModelInterface[] $value
     * @return mixed[]
     */
    public function toData(array $value)
    {
        $newValue = [];
        foreach ($value as $model) {
            $refId = $model;
            if (is_object($model)) {
                /** @var ModelInterface $model */
                $refId = $model->getId();
            }

            $newValue[] = $refId;
        }

        return $newValue;
    }

    /**
     * @param \MongoId[] $value
     * @return string[]
     */
    public function toModel($value)
    {
        return array_map('strval', $value);
    }
}