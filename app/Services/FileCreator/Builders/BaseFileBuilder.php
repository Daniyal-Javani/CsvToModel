<?php

namespace App\Services\FileCreator\Builders;

use App\Exceptions\CannotCreateFile;
use App\Exceptions\CannotLoadTemplateFile;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

abstract class BaseFileBuilder
{
    protected string $name;

    protected array $scopes;

    protected array $placeholderValues;

    protected string $filePath;

    abstract protected function getTemplateFilePath() : string;

    abstract protected function getBaseFilePath(): string;

    abstract protected function getBaseNamespace(): string;

    public function createFile(): string
    {
        $classContent = $this->getClassContent();
        
        $filePath = $this->getFilePath();

        $fileName = $this->getFileName();
        
        try {
            if(!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0775, true, true);
            }

            // TODO: show error if the file already exist
            if(!File::exists($filePath . $fileName)) {
                File::put($filePath . $fileName, $classContent);
            }
        } catch(Exception $e) {
            logger()->error($e);

            throw new CannotCreateFile();
        }

        return $filePath;
    }

    protected function getClassContent(): string
    {
        $template = $this->getTemplate();
        
        return strtr($template, $this->placeholderValues);
    }

    protected function getTemplate() : string
    {
        try {
            $templateFilePath = $this->getTemplateFilePath();
            
            return File::get($templateFilePath);
        } catch (Exception $e) {
            throw $e;
            logger()->error($e);

            throw new CannotLoadTemplateFile();
        }
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    protected function addNamespace(): void
    {
        $this->placeholderValues['{namespace}'] = $this->getBaseNamespace() . implode('\\', $this->getStudlyScopes());
    }

    protected function addClassName(): void
    {
        $this->placeholderValues['{class_name}'] = $this->getClassName();
    }

    protected function getClassName(): string
    {
         return Str::studly($this->name);
    }

    protected function getFilePath(): string
    {
         return base_path() . $this->getBaseFilePath() . implode('/', $this->getStudlyScopes()) . '/';
    }

    protected function getFileName(): string
    {
         return $this->getClassName() . '.php';
    }

    protected function getStudlyScopes(): array
    {
         return array_map(function ($scope) {
            return Str::studly($scope);
         }, $this->scopes);
    }
}
