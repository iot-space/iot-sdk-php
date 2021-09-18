<?php

namespace IotSpace;

use Illuminate\Container\Container;

class IotApplication extends Container
{
    public function __construct(array $config)
    {
        $this['config'] = $config;
        static::setInstance($this);
    }

}
