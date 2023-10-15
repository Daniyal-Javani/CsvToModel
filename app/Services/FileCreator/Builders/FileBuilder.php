<?php

namespace App\Services\FileCreator\Builders;

interface FileBuilder
{
    public function setName(string $name): void;

    public function setScopes(array $scopes) : void;

    public function build(): void;
}