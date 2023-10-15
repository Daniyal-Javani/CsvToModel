<?php

use Illuminate\Support\Facades\File;
use App\Services\FileCreator\Builders\Model\ModelBuilder;

uses(Tests\TestCase::class);

test('build a file', function () {
    $modelBuilder = resolve(ModelBuilder::class);

    $modelBuilder->setScopes([
        'indirect-emissions-owned',
        'electricity',
    ]);

    $modelBuilder->setName('meeting-rooms');

    $path = base_path() . '/app/Models/IndirectEmissionsOwned/Electricity/';

    $fileName = 'MeetingRooms.php';

    File::partialMock()
        ->shouldReceive('isDirectory')
        ->with($path)
        ->once();

    File::partialMock()
        ->shouldReceive('makeDirectory')
        ->with($path, 0775, true, true)
        ->once();

    File::partialMock()
        ->shouldReceive('exists')
        ->with($path . $fileName)
        ->once();

    File::partialMock()
        ->shouldReceive('put')
        ->with($path . $fileName, getClassContent())
        ->once();

    $modelBuilder->build();
});

function getClassContent ()
{
    return '<?php

namespace App\Models\IndirectEmissionsOwned\Electricity;

use Illuminate\Database\Eloquent\Model;

class MeetingRooms extends Model
{
    const TABLE_NAME = \'meeting-rooms\';

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }
}
';
}
