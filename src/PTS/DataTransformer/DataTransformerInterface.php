<?php
namespace PTS\DataTransformer;

interface DataTransformerInterface
{
    /**
     * @param ModelInterface $model
     * @param string $mapType
     * @return array
     */
    public function getData(ModelInterface $model, $mapType = 'dto');

    /**
     * @param array $data
     * @param ModelInterface $model
     * @param string $mapType
     */
    public function fillModel(array $data, ModelInterface $model, $mapType = 'dto');
}