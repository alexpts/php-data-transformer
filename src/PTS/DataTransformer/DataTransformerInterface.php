<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

interface DataTransformerInterface
{
    public function getData($model, string $mapType = 'dto', array $excludeFields = []): array;

    public function fillModel(array $data, $model, string $mapType = 'dto');
}
