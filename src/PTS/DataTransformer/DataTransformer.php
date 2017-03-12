<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

class DataTransformer implements DataTransformerInterface
{
    /** @var ModelClosure */
    protected $fn;
    /** @var TypeConverter */
    protected $typeConverter;
    /** @var MapsManager */
    protected $mapsManager;

    public function __construct(TypeConverter $converter, MapsManager $mapsManager, ModelClosure $closuresFn)
    {
        $this->mapsManager = $mapsManager;
        $this->typeConverter = $converter;
        $this->fn = $closuresFn;
    }

    public function getMapsManager(): MapsManager
    {
        return $this->mapsManager;
    }

    public function getConverter(): TypeConverter
    {
        return $this->typeConverter;
    }


    public function getData($model, string $mapType = 'dto', array $excludeFields = []): array
    {
        $class = get_class($model);
        $map = $this->mapsManager->getMap($class, $mapType);

        $fromModelFn = $this->fn->getFromModel();
        $props = [];

        foreach ($map as $dataKey => $propRule) {
            if (in_array($dataKey, $excludeFields, true)) {
                continue;
            }

            $propRule = new PropRule($propRule);
            $val = $fromModelFn->call($model, $propRule->getGetter(), $propRule->getProp($dataKey));
            if ($val !== null) {
                $props[$dataKey] = $this->getConverter()->toData($val, $propRule, $this);
            }
        }

        return $props;
    }

    public function fillModel(array $data, $model, string $mapType = 'dto')
    {
        $class = get_class($model);
        $map = $this->mapsManager->getMap($class, $mapType);

        $typeConverter = $this->getConverter();
        $setModelFn = $this->fn->setToModel();

        foreach ($data as $name => $val) {
            if (!array_key_exists($name, $map)) {
                continue;
            }

            $propRule = new PropRule($map[$name]);
            $val = $typeConverter->toModel($val, $propRule, $this);

            $setModelFn->call($model, $propRule->getSet(), $val, $propRule->getProp($name));
        }
    }

    public function createModel(string $class)
    {
        $emptyModel = new \ReflectionClass($class);
        return $emptyModel->newInstanceWithoutConstructor();
    }
}
