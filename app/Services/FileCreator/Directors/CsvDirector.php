<?php

namespace App\Services\FileCreator\Directors;

use App\Exceptions\CannotLoadCsvFile;
use App\Services\FileCreator\Builders\FileBuilder;
use Exception;

class CsvDirector
{
    private FileBuilder $fileBuilder;

    private string $filePath;

    public function buildFiles()
    {
        $filesDetails = $this->getFilesDetails();  
        
        foreach ($filesDetails as $fileDetails) {
            $fileDetails = str_getcsv($fileDetails);
            
            $this->fileBuilder->setScopes(explode(',', $fileDetails[0]));
            $this->fileBuilder->setName($fileDetails[1]);
            $this->fileBuilder->build();
        }
    }

    public function setBuilder(FileBuilder $fileBuilder): void
    {
        $this->fileBuilder = $fileBuilder;
    }

    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    private function getFilesDetails(): array
    {
        try {
            // TODO: Add validation
            return file($this->filePath);
        } catch (Exception $e) {
            logger()->error($e);

            throw new CannotLoadCsvFile();
        }
    }
}
