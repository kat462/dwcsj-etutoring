<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

echo "Recent sessions (limit 20):\n";
$rows = DB::table('sessions')->orderBy('last_activity', 'desc')->limit(20)->get();
foreach ($rows as $r) {
    $payload = $r->payload;
    $snippet = substr($payload, 0, 200);
    echo "id={$r->id} user_id={$r->user_id} last_activity={$r->last_activity}\n";
    echo "payload_snippet={$snippet}\n";
    // attempt to base64-decode and unserialize to show _token
    $decoded = base64_decode($payload);
    if ($decoded !== false && strpos($decoded, 'a:') === 0) {
        $un = @unserialize($decoded);
        if ($un !== false && is_array($un) && isset($un['_token'])) {
            echo "_token_in_session=" . $un['_token'] . "\n";
        }
    }
    echo "----\n";
}
if (count($rows) === 0) {
    echo "(no sessions found)\n";
}
