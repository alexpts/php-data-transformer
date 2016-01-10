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
        return function (array $mapping, TypeConverter $typeConverter) {
            $props = [];

            foreach ($mapping as $name => $prop) {
                $getter = array_key_exists('get', $prop) ? $prop['get'] : null;
                $propVal = array_key_exists('prop', $prop) ? $prop['prop'] : null;
                $val = null;

                if ($getter) {
                    $method = is_array($getter) ? $getter[0] : $getter;
                    $args = is_array($getter) ? $getter[1] : [];
                    $val = call_user_func_array([$this, $method], $args);
                } elseif ($propVal) {
                    $val = $this->{$propVal};
                }

                if ($val !== null) {
                    $props[$name] = $typeConverter->toStorage($val, $prop);
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
        return function(array $doc, array $mapping, TypeConverter $typeConverter) {
            foreach ($doc as $name => $val) {
                if (!array_key_exists($name, $mapping)) {
                    continue;
                }

                $prop = $mapping[$name];

                $modelName = array_key_exists('prop', $prop) ? $prop['prop'] : $name;
                $setter = array_key_exists('set', $prop) ? $prop['set'] : null;

                $val = $typeConverter->toModel($val, $prop);

                $setter
                    ? call_user_func([$this, $setter], $val)
                    : $this->{$modelName} = $val;
            }
        };
    }
}