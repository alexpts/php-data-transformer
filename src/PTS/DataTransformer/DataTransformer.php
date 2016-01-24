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
     * @param ModelInterface $model
     * @param string $mapType
     * @return array
     *
     * @throws ParseException
     */
    public function getData(ModelInterface $model, $mapType = 'dto')
    {
        $class = get_class($model);
        $fn = \Closure::bind($this->closuresFn->getToDataFn(), $model, $class);
        $map = $this->mapsManager->getMap($class, $mapType);
        return $fn($map, $this->typeConverter);
    }

    /**
     * @param array $data
     * @param ModelInterface $model
     * @param string $mapType
     *
     * @throws ParseException
     */
    public function fillModel(array $data, ModelInterface $model, $mapType = 'dto')
    {
        $class = get_class($model);
        $fn = \Closure::bind($this->closuresFn->getFillFn(), $model, $class);
        $map = $this->mapsManager->getMap($class, $mapType);
        $fn($data, $map, $this->typeConverter);
    }

    /**
     * @param string $class
     * @return ModelInterface
     */
    public function createModel($class)
    {
        $emptyModel = new \ReflectionClass($class);
        return $emptyModel->newInstanceWithoutConstructor();
    }
}