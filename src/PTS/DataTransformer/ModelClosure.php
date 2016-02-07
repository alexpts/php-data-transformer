<?php
namespace PTS\DataTransformer;

class ModelClosure
{
    /** @var \Closure */
    protected $fill;
    /** @var \Closure */
    protected $data;

    /**
     * @param string $key
     * @param array $propRule
     * @param mixed $default
     * @return mixed
     */
    static public function getKey($key, array $propRule = [], $default = null)
    {
        return array_key_exists($key, $propRule) ? $propRule[$key] : $default;
    }

    /**
     * @return \Closure
     */
    public function getToDataFn()
    {
        if (!$this->data) {
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

            foreach ($mapping as $name => $propRule) {
                $getter = ModelClosure::getKey('get', $propRule);
                $propVal = ModelClosure::getKey('prop', $propRule);
                $val = null;

                if ($getter) {
                    $method = is_array($getter) ? $getter[0] : $getter;
                    $args = is_array($getter) ? $getter[1] : [];
                    $val = call_user_func_array([$this, $method], $args);
                } elseif ($propVal) {
                    $val = $this->{$propVal};
                }

                if ($val !== null) {
                    $props[$name] = $typeConverter->toData($val, $propRule, $transformer);
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

                $propRule = $mapping[$name];

                $modelName = ModelClosure::getKey('prop', $propRule, $name);
                $setter = ModelClosure::getKey('set', $propRule);

                $val = $typeConverter->toModel($val, $propRule, $transformer);

                $setter
                    ? call_user_func([$this, $setter], $val)
                    : $this->{$modelName} = $val;
            }
        };
    }
}