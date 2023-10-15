<?php

namespace App\Services\FileCreator\Builders\Model;

use App\Services\FileCreator\Builders\FileBuilder;
use App\Services\FileCreator\Builders\BaseFileBuilder;


class ModelBuilder extends BaseFileBuilder implements FileBuilder
{
    public function build(): void
    {
        $this->addNamespace();

        $this->addClassName();
        
        $this->addTableName();

        $this->createFile();
    }

    private function addTableName() : void
    {
        $this->placeholderValues['{table_name}'] = "'$this->name'";
    }

    protected function getBaseNamespace(): string
    {
        return 'App\Models';
    }

    protected function getBaseFilePath(): string
    {
        return '/app/Models/';
    }

    protected function getTemplateFilePath() : string
    {
        return __DIR__ . '/template.stub';
    }
}