<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\ModelInterface;

class RefModelsType
{
    /**
     * @param string[]|ModelInterface[] $value
     * @return \MongoId[]
     */
    public function toStorage(array $value)
    {
        $newValue = [];
        foreach ($value as $model) {
            $refId = $model;
            if (is_object($model)) {
                /** @var ModelInterface $model */
                $refId = $model->getId();
            }

            $newValue[] = new \MongoId($refId);
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