<?php

namespace App\Interfaces;

interface IntegrationInterface
{
    public function calculate(array $properties = []);
    public function getDeliveryType(): int;
}