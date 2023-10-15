<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exceptions\CannotCreateFile;
use App\Exceptions\CannotLoadCsvFile;
use App\Exceptions\CannotLoadTemplateFile;
use App\Services\FileCreator\Directors\CsvDirector;
use App\Services\FileCreator\Builders\Model\ModelBuilder;
use Exception;

class FilesMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:files-make
                           {filePath : file path of the csv file}
                           {type=model : file path of the csv file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make files from a csv file';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $csvDriver = resolve(CsvDirector::class);

        $this->info('File creation started...');

        $filePath = $this->argument('filePath');
        $type = $this->argument('type');

        $csvDriver->setFilePath($filePath);

        try {
            if ($type == 'model') {
                $csvDriver->setBuilder(resolve(ModelBuilder::class));
            } else {
                $this->error('type not supported');
            }

            $csvDriver->buildFiles();
        } catch (CannotLoadTemplateFile $e) {
            $this->error('Cannot load template file');
        } catch (CannotCreateFile $e) {
            $this->error('Cannot create files or directories');
        } catch (CannotLoadCsvFile $e) {
            $this->error('Cannot load csv file');
        }  catch (Exception $e) {
            $this->error('An unexpected error happened, check the logs');
        }

        $this->info('Files created successfully...');
    }
}
