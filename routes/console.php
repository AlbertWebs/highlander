<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('snapshot:export-seeders', function () {
    $dbPath = base_path('database.sqlite');
    if (! is_string($dbPath) || ! file_exists($dbPath)) {
        $this->error('Missing database.sqlite at project root.');
        return;
    }

    $pdo = new PDO('sqlite:'.$dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = $pdo->query(
        "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name"
    )->fetchAll(PDO::FETCH_COLUMN);

    $snapshot = [];
    foreach ($tables as $table) {
        $rows = $pdo->query('SELECT * FROM "'.$table.'"')->fetchAll(PDO::FETCH_ASSOC);
        $snapshot[$table] = $rows;
        $this->line($table.' => '.count($rows));
    }

    $json = json_encode($snapshot, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (! is_string($json) || $json === '') {
        $this->error('Failed to generate snapshot JSON.');
        return;
    }

    $seedersDir = base_path('database/seeders');
    $jsonPath = $seedersDir.'/DatabaseSnapshotSeeder.snapshot.json';
    $seederPath = $seedersDir.'/DatabaseSnapshotSeeder.php';

    File::put($jsonPath, $json);

    $seederPhp = <<<'PHP'
<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSnapshotSeeder extends Seeder
{
    private const SNAPSHOT_JSON_PATH = __DIR__.'/DatabaseSnapshotSeeder.snapshot.json';

    public function run(): void
    {
        $json = file_get_contents(self::SNAPSHOT_JSON_PATH);
        if (! is_string($json) || $json === '') {
            throw new \RuntimeException('Snapshot JSON file missing or empty.');
        }

        $snapshot = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');
        }

        foreach (array_keys($snapshot) as $table) {
            DB::table($table)->delete();
        }

        foreach ($snapshot as $table => $rows) {
            if (! is_array($rows) || count($rows) === 0) {
                continue;
            }
            DB::table($table)->insert($rows);
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=ON');
        }
    }
}
PHP;

    File::put($seederPath, $seederPhp);

    $this->info('Generated DatabaseSnapshotSeeder snapshot seed.');
})->purpose('Export the current database.sqlite state into a snapshot seeder');
