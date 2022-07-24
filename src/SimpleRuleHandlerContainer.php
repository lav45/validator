<?php

declare(strict_types=1);

namespace Yiisoft\Validator;

use Yiisoft\Validator\Exception\RuleHandlerInterfaceNotImplementedException;
use Yiisoft\Validator\Exception\RuleHandlerNotFoundException;

final class SimpleRuleHandlerContainer implements RuleHandlerResolverInterface
{
    private array $instances = [];

    public function resolve(string $className): RuleHandlerInterface
    {
        if (!class_exists($className)) {
            throw new RuleHandlerNotFoundException($className);
        }
        if (!array_key_exists($className, $this->instances)) {
            $classInstance = new $className();
            if (!$classInstance instanceof RuleHandlerInterface) {
                throw new RuleHandlerInterfaceNotImplementedException($className);
            }
            return $this->instances[$className] = $classInstance;
        }

        return $this->instances[$className];
    }
}