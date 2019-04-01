<?php

class ControllerFileException extends RouteException
{
    public function __construct($message = null, $code = 0, Throwable $previous = null) 
    {
        parent::__construct($message ?? "Подключаемый файл контроле не найден.", $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}