<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
try {
    $rows = DB::select('SHOW TABLES');
    $tables = [];
    foreach ($rows as $row) {
        $vals = array_values((array)$row);
        $tables[] = $vals[0];
    }
    if (count($tables) === 0) {
        echo "No tables found\n";
        exit(0);
    }
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    foreach ($tables as $table) {
        DB::statement("DROP TABLE IF EXISTS `{$table}`");
        echo "Dropped {$table}\n";
    }
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    echo "All tables dropped\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
