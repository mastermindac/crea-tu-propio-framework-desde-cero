<?php

namespace Lune\Container;

use Lune\Database\Model;
use Lune\Http\HttpNotFoundException;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class DependencyInjection {
    public static function resolveParameters(\Closure|array $callback, $routeParameters = []) {
        $methodOrFunction = is_array($callback)
            ? new ReflectionMethod($callback[0], $callback[1])
            : new ReflectionFunction($callback);

        $params = [];

        foreach ($methodOrFunction->getParameters() as $param) {
            $resolved = null;

            if (is_subclass_of($param->getType()->getName(), Model::class)) {
                $modelClass = new ReflectionClass($param->getType()->getName());
                $routeParamName = snake_case($modelClass->getShortName());
                $resolved = $param->getType()->getName()::find($routeParameters[$routeParamName] ?? 0);

                if (is_null($resolved)) {
                    throw new HttpNotFoundException();
                }
            } elseif ($param->getType()->isBuiltin()) {
                $resolved = $routeParameters[$param->getName()] ?? null;
            } else {
                $resolved = app($param->getType()->getName());
            }

            $params[] = $resolved;
        }

        return $params;
    }
}
