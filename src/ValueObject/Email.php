<?php

namespace App\ValueObject;

final class Email
{
    /** @var string */
    private $email;

    public function __construct(string $email)
    {
        if (strlen($email) < 5) {
            throw new \InvalidArgumentException('Invalid email');
        }

        $this->email = $email;
    }

    public function value() : string
    {
        return $this->email;
    }

    public function __toString() : string
    {
        return $this->email;
    }
}