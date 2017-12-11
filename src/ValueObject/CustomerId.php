<?php

namespace App\ValueObject;

final class CustomerId
{
    private $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function generate() : self
    {
        return new self(mt_rand());
    }

    public function id() : int
    {
        return $this->id;
    }

    public static function createFromInteger(int $id) : self
    {
        return new self($id);
    }
}