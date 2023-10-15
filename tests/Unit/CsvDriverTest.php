<?php

use App\Services\FileCreator\Builders\FileBuilder;
use App\Services\FileCreator\Directors\CsvDirector;
use Mockery;

test('build files', function () {
    $mockedFileBuilder = Mockery::mock(FileBuilder::class, function ($fileBuilder) {
        $fileBuilder->shouldReceive('setScopes')
            ->with([
                'indirect-emissions-owned',
                'electricity',
            ])
            ->once();

        $fileBuilder->shouldReceive('setName')
            ->with('meeting-rooms')
            ->once();

        $fileBuilder->shouldReceive('build')
            ->once();
    });

    $csvDirector = resolve(CsvDirector::class);
    $csvDirector->setBuilder($mockedFileBuilder);
    $csvDirector->setFilePath(__DIR__ . '/models.csv');
    $csvDirector->buildFiles();
});
