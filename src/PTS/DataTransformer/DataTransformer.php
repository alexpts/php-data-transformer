<?php
namespace PTS\DataTransformer;

use Symfony\Component\Yaml\Exception\ParseException;

class DataTransformer implements DataTransformerInterface
{
    /** @var ModelClosure */
    protected $closuresFn;
    /** @var TypeConverter */
    protected $typeConverter;
    /** @var MapsManager */
    protected $mapsManager;

    /**
     * @param TypeConverter $converter
     * @param MapsManager $mapsManager
     * @param ModelClosure $closuresFn
     */
    public function __construct(TypeConverter $converter, MapsManager $mapsManager, ModelClosure $closuresFn)
    {
        $this->mapsManager = $mapsManager;
        $this->typeConverter = $converter;
        $this->closuresFn = $closuresFn;
    }

    /**
     * @return MapsManager
     */
    public function getMapsManager()
    {
        return $this->mapsManager;
    }

    /**
     * @return TypeConverter
     */
    public function getConverter()
    {
        return $this->typeConverter;
    }

    /**
     * @param mixed $model
     * @param string $mapType
     * @return array
     *
     * @throws ParseException
     */
    public function getData($model, $mapType = 'dto')
    {
        $class = get_class($model);
        $map = $this->mapsManager->getMap($class, $mapType);

        $typeConverter = $this->getConverter();
        $fromModelClosure = $this->closuresFn->getFromModel();
        $props = [];

        foreach ($map as $dataKey => $propRule) {
            $propRule = new PropRule($propRule);

            $fn = \Closure::bind($fromModelClosure, $model, $class);
            $val = $fn($propRule->getGet(), $propRule->getProp($dataKey));

            if ($val !== null) {
                $props[$dataKey] = $typeConverter->toData($val, $propRule, $this);
            }
        }

        return $props;
    }

    /**
     * @param array $data
     * @param mixed $model
     * @param string $mapType
     *
     * @throws ParseException
     */
    public function fillModel(array $data, $model, $mapType = 'dto')
    {
        $class = get_class($model);
        $map = $this->mapsManager->getMap($class, $mapType);

        $typeConverter = $this->getConverter();
        $setModelClosure = $this->closuresFn->setToModel();

        foreach ($data as $name => $val) {
            if (!array_key_exists($name, $map)) {
                continue;
            }

            $propRule = new PropRule($map[$name]);
            $val = $typeConverter->toModel($val, $propRule, $this);

            $fn = \Closure::bind($setModelClosure, $model, $class);
            $fn($propRule->getSet(), $val, $propRule->getProp($name));
        }
    }

    /**
     * @param string $class
     * @return mixed
     */
    public function createModel($class)
    {
        $emptyModel = new \ReflectionClass($class);
        return $emptyModel->newInstanceWithoutConstructor();
    }
}
