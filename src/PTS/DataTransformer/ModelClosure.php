<?php
namespace PTS\DataTransformer;

class ModelClosure
{
    /** @var \Closure */
    protected $fill;
    /** @var \Closure */
    protected $data;

    /**
     * @return \Closure
     */
    public function getToDataFn()
    {
        if ($this->data === null) {
            $this->data = $this->createDataFn();
        }

        return $this->data;
    }

    /**
     * @return \Closure
     */
    protected function createDataFn() {
        return function (array $mapping, DataTransformer $transformer) {
            $typeConverter = $transformer->getConverter();
            $props = [];

            foreach ($mapping as $dataKey => $propRule) {
                $propRule = new PropRule($propRule);
                $getter = $propRule->getGet();
                $propVal = $propRule->getProp();
                $val = null;

                if ($getter !== null) {
                    $method = is_array($getter) ? $getter[0] : $getter;
                    $args = is_array($getter) ? $getter[1] : [];
                    $val = call_user_func_array([$this, $method], $args);
                } elseif ($propVal !== null) {
                    $val = $this->{$propVal};
                }

                if ($val !== null) {
                    $props[$dataKey] = $typeConverter->toData($val, $propRule, $transformer);
                }
            }

            return $props;
        };
    }

    /**
     * @return \Closure
     */
    public function getFillFn()
    {
        if (!$this->fill) {
            $this->fill = $this->createFillFn();
        }

        return $this->fill;
    }

    /**
     * @return \Closure
     */
    protected function createFillFn()
    {
        return function(array $doc, array $mapping, DataTransformer $transformer) {
            $typeConverter = $transformer->getConverter();

            foreach ($doc as $name => $val) {
                if (!array_key_exists($name, $mapping)) {
                    continue;
                }

                $propRule = new PropRule($mapping[$name]);

                $modelName = $propRule->getProp($name);
                $setter = $propRule->getSet();

                $val = $typeConverter->toModel($val, $propRule, $transformer);

                $setter
                    ? call_user_func([$this, $setter], $val)
                    : $this->{$modelName} = $val;
            }
        };
    }
}
