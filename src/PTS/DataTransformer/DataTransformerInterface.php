<?php
namespace PTS\DataTransformer;

interface DataTransformerInterface
{
    /**
     * @param mixed $model
     * @param string $mapType
     * @return array
     */
    public function getData($model, $mapType = 'dto');

    /**
     * @param array $data
     * @param mixed $model
     * @param string $mapType
     */
    public function fillModel(array $data, $model, $mapType = 'dto');
}
