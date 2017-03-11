<?php
declare(strict_types = 1);

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

    public function getKey(string $name, $default = null)
    {
        return array_key_exists($name, $this->map) ? $this->map[$name] : $default;
    }

    public function getProp(string $default = null): ?string
    {
        return $this->getKey('prop', $default);
    }

    public function getSet()
    {
        return $this->getKey('set');
    }

    public function getGetter()
    {
        return $this->getKey('get');
    }

    public function getType(): ?string
    {
        return $this->getKey('type');
    }
}
