<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
try {
    $migrationsToMark = [
        '2025_11_20_000000_create_sessions_table',
        '2025_11_20_000100_add_notes_to_bookings_table'
    ];
    $maxBatch = DB::table('migrations')->max('batch');
    $batch = $maxBatch ? $maxBatch + 1 : 1;
    foreach ($migrationsToMark as $migration) {
        DB::table('migrations')->insert(['migration' => $migration, 'batch' => $batch]);
        echo "Marked $migration as run (batch $batch)\n";
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
