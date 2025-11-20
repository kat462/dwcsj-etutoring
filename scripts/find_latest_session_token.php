<?php
// Script to lookup a session row and extract the stored CSRF `_token` value.
// Usage:
//   php scripts/find_latest_session_token.php <session_id>

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$db = $app->make('db');

$sessionId = $argv[1] ?? null;

// If the provided session id looks like an encrypted cookie (starts with eyJ...)
// attempt to decrypt it with the app encrypter so we can match the DB id.
if ($sessionId) {
    $maybeEncrypted = $sessionId;
    try {
        $encrypter = $app->make('encrypter');
        $decoded = urldecode($maybeEncrypted);
        $decrypted = $encrypter->decrypt($decoded);
        if ($decrypted) {
            // decrypted value should be the raw session id
            $sessionId = $decrypted;
            echo "decrypted_session_id: {$sessionId}\n";
        }
    } catch (Throwable $e) {
        // print decrypt error to help debugging
        echo "decrypt_error: " . $e->getMessage() . "\n";
    }

    $row = $db->table('sessions')->where('id', $sessionId)->first();
} else {
    $row = $db->table('sessions')->orderBy('last_activity', 'desc')->first();
}

if (! $row) {
    echo "NO_SESSION\n";
    exit(0);
}

echo "session_id: {$row->id}\n";
echo "last_activity: {$row->last_activity}\n";
echo "raw_payload_len: " . strlen($row->payload) . "\n";

$payload = $row->payload;

$decoded = @base64_decode($payload, true);
if ($decoded === false) {
    $decoded = $payload;
}

$data = @unserialize($decoded);
if ($data === false) {
    $json = @json_decode($decoded, true);
    if ($json !== null) {
        $data = $json;
    }
}

if (is_array($data) && array_key_exists('_token', $data)) {
    echo "_token: " . $data['_token'] . "\n";
} else {
    echo "NO_TOKEN_IN_PAYLOAD\n";
    echo "Decoded payload preview:\n";
    echo substr(var_export($data, true), 0, 2000) . "\n";
}

// Exit
exit(0);
