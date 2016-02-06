<?php
namespace PTS\DataTransformer;

class ModelClosure
{
    /** @var \Closure */
    protected $fillFn;
    /** @var \Closure */
    protected $dataFn;

    /**
     * @return \Closure
     */
    public function getToDataFn()
    {
        if (!$this->dataFn) {
            $this->dataFn = $this->createDataFn();
        }

        return $this->dataFn;
    }

    /**
     * @return \Closure
     */
    public function getFillFn()
    {
        if (!$this->fillFn) {
            $this->fillFn = $this->createFillFn();
        }

        return $this->fillFn;
    }

    /**
     * @return \Closure
     */
    protected function createDataFn() {
        return function (array $mapping, DataTransformer $transformer) {
            $typeConverter = $transformer->getConverter();
            $props = [];

            foreach ($mapping as $name => $propRule) {
                $getter = array_key_exists('get', $propRule) ? $propRule['get'] : null;
                $propVal = array_key_exists('prop', $propRule) ? $propRule['prop'] : null;
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
    protected function createFillFn()
    {
        return function(array $doc, array $mapping, DataTransformer $transformer) {
            $typeConverter = $transformer->getConverter();

            foreach ($doc as $name => $val) {
                if (!array_key_exists($name, $mapping)) {
                    continue;
                }

                $propRule = $mapping[$name];

                $modelName = array_key_exists('prop', $propRule) ? $propRule['prop'] : $name;
                $setter = array_key_exists('set', $propRule) ? $propRule['set'] : null;

                $val = $typeConverter->toModel($val, $propRule, $transformer);

                $setter
                    ? call_user_func([$this, $setter], $val)
                    : $this->{$modelName} = $val;
            }
        };
    }
}