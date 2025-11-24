<?php
// Usage: php dump_session_for_id.php <session_cookie_value>
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Str;

// Boot a minimal Laravel application to access DB config
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if ($argc < 2) {
    echo "Usage: php dump_session_for_id.php <session_cookie_value>\n";
    exit(1);
}

$cookie = $argv[1];

$encrypter = $app->make('encrypter');
try {
    $plain = $encrypter->decrypt($cookie);
} catch (Exception $e) {
    try {
        $plain = $encrypter->decrypt(urldecode($cookie));
    } catch (Exception $e2) {
        echo "Failed to decrypt cookie: " . $e2->getMessage() . "\n";
        exit(2);
    }
}

$sessionId = $plain;
echo "Decrypted session id: $sessionId\n";

$session = \DB::table('sessions')->where('id', $sessionId)->first();
if (! $session) {
    echo "No session row found for id: $sessionId\n";
    exit(3);
}

echo "Found session row: id={$session->id}, last_activity={$session->last_activity}\n";
$payload = $session->payload;
$decodedPayload = base64_decode($payload);
if ($decodedPayload === false) {
    echo "Failed to base64-decode payload. Raw payload length: " . strlen($payload) . "\n";
    exit(4);
}

$un = @unserialize($decodedPayload);
if ($un === false) {
    $maybe = @json_decode($decodedPayload, true);
    if ($maybe !== null) {
        echo "Payload (json):\n" . print_r($maybe, true) . "\n";
        if (isset($maybe['_token'])) {
            echo "_token in session payload: " . $maybe['_token'] . "\n";
        }
    } else {
        echo "Failed to unserialize payload. Raw decoded payload:\n" . $decodedPayload . "\n";
    }
} else {
    echo "Payload (unserialized):\n" . print_r($un, true) . "\n";
    if (isset($un['_token'])) {
        echo "_token in session payload: " . $un['_token'] . "\n";
    }
}

exit(0);
