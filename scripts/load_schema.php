<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
$path = __DIR__ . '/../database/schema/mysql-schema.sql.disabled';
if (!file_exists($path)) {
    echo "Schema file not found: {$path}\n";
    exit(1);
}
$sql = file_get_contents($path);
try {
    DB::unprepared($sql);
    echo "Schema loaded\n";
} catch (Throwable $e) {
    echo "ERROR loading schema: " . $e->getMessage() . "\n";
    exit(1);
}
