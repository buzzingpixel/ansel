<?php


declare(strict_types=1);

namespace App;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

trait RemoveEnvFromBootstrappers
{
    public function removeEnvFromBootstrappers(): void
    {
        $this->bootstrappers = array_filter(
            $this->bootstrappers,
            /**
             * @param class-string $className
             */
            function (string $className) {
                return $className !== LoadEnvironmentVariables::class;
            }
        );
    }
}
