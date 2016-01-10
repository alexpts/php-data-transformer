<?php
namespace PTS\DataTransformer\Types;

class MongoDateType
{
    /**
     * @param \MongoDate $value
     * @return \MongoDate
     */
    public function toModel(\MongoDate $value)
    {
        return $value;
    }

    /**
     * @param \MongoDate $value
     * @return \MongoDate
     */
    public function toStorage(\MongoDate $value)
    {
        return $value;
    }
}