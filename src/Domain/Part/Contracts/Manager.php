<?php

namespace Titantwentyone\FilamentCMS\Domain\Part\Contracts;

abstract class Manager
{
    protected array $views = [];

    //protected array $fields = [];
    public function views(): array
    {
        return $this->views;
    }

    public abstract function fields(string $location): array;
//    {
//        return $this->fields[$location];
//    }
}