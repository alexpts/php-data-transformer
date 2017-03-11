<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

class BadType2
{
    public function toModel($value)
    {
        return $value;
    }
}