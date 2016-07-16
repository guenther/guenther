<?php

namespace Guenther\Guenther\Check;

class Checker
{
    /**
     * @var callable
     */
    protected $success;
    /**
     * @var callable
     */
    protected $warning;
    /**
     * @var callable
     */
    protected $error;

    public function __construct(callable $success, callable $warning, callable $error)
    {
        $this->success = $success;
        $this->warning = $warning;
        $this->error = $error;
    }

    protected function success($check, $message)
    {
        return call_user_func_array($this->success, [$check, $message]);
    }

    protected function warning($check, $message)
    {
        return call_user_func_array($this->warning, [$check, $message]);
    }

    protected function error($check, $message)
    {
        return call_user_func_array($this->error, [$check, $message]);
    }

    protected function checkStringValue($value)
    {
        return ($value === null || $value === '');
    }

    protected function checkArrayValue($value)
    {
        return ($value === null || !count($value));
    }
}
