<?php

namespace Oli\Support\Transport;

class Http
{
    /**
     * Statically call any method of Request.
     * @param   string $method
     * @param   array $argument
     * @return  \Oli\Support\Transport\Request
     */
    public static function __callStatic(string $method, array $arguments)
    {
        return (new Request)->{$method}(...$arguments);
    }
}