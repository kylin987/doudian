<?php

/*
 * Author: cc
 *  Created by PhpStorm.
 *  Copyright (c)  cc Inc. All rights reserved.
 */

namespace Imactool\Jinritemai\Core;

class Container implements \ArrayAccess
{
    /**
     * 中间件.
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var array
     */
    private $values = [];

    /**
     * @var
     */
    public $register;

    public function offsetExists(mixed $offset): bool
    {
        // 方法实现
        return isset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return $this|mixed
     */
    public function offsetGet($offset): mixed
    {
        if (isset($this->instances[$offset])) {
            return $this->instances[$offset];
        }

        $raw = $this->values[$offset];
        $value = $this->values[$offset] = $raw($this);
        $this->instances[$offset] = $value;

        return $value;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->values[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * 注册服务
     *
     * @param $provider
     *
     * @return $this
     */
    public function serviceRegsiter($provider)
    {
        $provider->serviceProvider($this);

        return $this;
    }

    /**
     * 获取中间件.
     *
     * @param array $middlewares
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * 设置中间件.
     */
    public function setMiddlewares(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    /**
     * 添加中间件.
     *
     * @param string $name
     *
     * @return array
     */
    public function addMiddlewares(array $classFun, $name = '')
    {
        if (empty($this->middlewares)) {
            $this->middlewares[$name] = $classFun;
        } else {
            array_push($this->middlewares, [$name => $classFun]);
        }

        return $this->middlewares;
    }
}
