<?php
namespace PTS\DataTransformer;

class ModelClosure
{
    /** @var \Closure */
    protected $fillModel;
    /** @var \Closure */
    protected $fromModel;

    /**
     * @return \Closure
     */
    public function getFromModel()
    {
        if ($this->fromModel === null) {
            $this->fromModel = $this->createGetFromModelClosure();
        }

        return $this->fromModel;
    }

    /**
     * @return \Closure
     */
    public function setToModel()
    {
        if ($this->fillModel === null) {
            $this->fillModel = $this->createFillModelClosure();
        }

        return $this->fillModel;
    }

    /**
     * @return \Closure
     */
    protected function createGetFromModelClosure()
    {
        return function($getter, $propVal)
        {
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

    /**
     * @return \Closure
     */
    protected function createFillModelClosure()
    {
        return function($setter, $val, $modelName)
        {
            $setter
                ? call_user_func([$this, $setter], $val)
                : $this->{$modelName} = $val;
        };
    }
}
