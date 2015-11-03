<?php

namespace Core\Exception;


class WrongRequestMethodException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
