<?php
namespace PTS\DataTransformer;

class PropRule
{
    /** @var array */
    protected $map;

    /**
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return null|mixed
     */
    public function getKey($name, $default = null)
    {
        return  array_key_exists($name, $this->map) ? $this->map[$name] : $default;
    }

    /**
     * @param string|null $default
     * @return string|null
     */
    public function getProp($default = null)
    {
        return $this->getKey('prop', $default);
    }

    /**
     * @return mixed|null
     */
    public function getSet()
    {
        return $this->getKey('set');
    }

    /**
     * @return mixed|null
     */
    public function getGetter()
    {
        return $this->getKey('get');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getKey('type');
    }
}
