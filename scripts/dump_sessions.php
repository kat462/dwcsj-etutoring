<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$rows = DB::table('sessions')->orderBy('last_activity','desc')->limit(20)->get();
if ($rows->isEmpty()) {
    echo "No sessions found\n";
    exit(0);
}

foreach ($rows as $r) {
    echo "--- session id={$r->id} user_id={$r->user_id} last_activity={$r->last_activity} ---\n";
    $payload = $r->payload;
    $len = strlen($payload);
    echo "payload length: {$len}\n";
    // try base64 decode
    $decoded = @base64_decode($payload, true);
    if ($decoded !== false) {
        echo "base64 decoded length: " . strlen($decoded) . "\n";
        // try unserialize
        $un = @unserialize($decoded);
        if ($un !== false && is_array($un)) {
            echo "unserialized keys: " . implode(',', array_keys($un)) . "\n";
            if (array_key_exists('_token', $un)) {
                echo "_token (session CSRF token) = " . substr($un['_token'],0,200) . "\n";
            }
        } else {
            echo "decoded preview: " . substr($decoded,0,300) . "\n";
        }
    } else {
        echo "raw payload preview: " . substr($payload,0,300) . "\n";
    }
}
