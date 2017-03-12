<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

class ModelClosure
{
    /** @var \Closure */
    protected $fillModel;
    /** @var \Closure */
    protected $fromModel;

    public function getFromModel(): \Closure
    {
        if ($this->fromModel === null) {
            $this->fromModel = $this->createGetFromModelClosure();
        }

        return $this->fromModel;
    }

    public function setToModel(): \Closure
    {
        if ($this->fillModel === null) {
            $this->fillModel = $this->createFillModelClosure();
        }

        return $this->fillModel;
    }

    protected function createGetFromModelClosure(): \Closure
    {
        return function($getter, $propVal) {
            $val = null;

            if ($getter !== null) {
                $method = is_array($getter) ? $getter[0] : $getter;
                $args = is_array($getter) ? $getter[1] : [];
                $val = call_user_func_array([$this, $method], $args);
            } elseif (property_exists($this, $propVal)) {
                $val = $this->{$propVal};
            }

            return $val;
        };
    }

    protected function createFillModelClosure(): \Closure
    {
        return function($setter, $val, $modelName) {
            $setter
                ? call_user_func([$this, $setter], $val)
                : $this->{$modelName} = $val;
        };
    }
}
