<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 20:40
 */

namespace Rebelion\Abstracts;

abstract class ServiceAbstract
{
    /**
     * Terminate an exception with stack trace then exit 0
     *
     * @param \Exception $exception
     */
    protected function terminate(\Exception $exception): void
    {
        echo sprintf(
            "<pre class=\"sf-dump\">Code: %s<br />File: %s<br />Line %s<br />Message: %s<br /><br /><br />Stack trace:<br />%s</pre>",
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getMessage(),
            $exception->getTraceAsString()
        );

        exit(0);
    }
}