<?php
namespace PTS\DataTransformer\Types;

class DateType
{
    /**
     * @param \DateTime $value
     * @return \DateTime
     */
    public function toModel(\DateTime $value)
    {
        return $value;
    }

    /**
     * @param \DateTime $value
     * @return \DateTime
     */
    public function toData(\DateTime $value)
    {
        return $value;
    }
}