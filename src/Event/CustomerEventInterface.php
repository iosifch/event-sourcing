<?php

namespace App\Event;


use App\ValueObject\CustomerId;

interface CustomerEventInterface
{
    public function customerId() : CustomerId;
}