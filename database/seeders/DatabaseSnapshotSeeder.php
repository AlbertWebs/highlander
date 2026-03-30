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