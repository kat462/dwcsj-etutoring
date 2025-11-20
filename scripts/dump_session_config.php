<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Config;

$sessionConfig = Config::get('session');
echo "SESSION config:\n";
echo var_export($sessionConfig, true) . "\n\n";

$appKey = env('APP_KEY');
$masked = $appKey ? (substr($appKey,0,6) . str_repeat('*', max(0, strlen($appKey)-12)) . substr($appKey,-6)) : '(missing)';
echo "APP_KEY (masked): {$masked}\n";
echo "APP_KEY length: " . ($appKey ? strlen($appKey) : 0) . "\n";
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Config;

echo "session.driver=" . Config::get('session.driver') . PHP_EOL;
echo "session.cookie=" . Config::get('session.cookie') . PHP_EOL;
echo "session.domain=" . Config::get('session.domain') . PHP_EOL;
echo "session.secure=" . (Config::get('session.secure') ? 'true' : 'false') . PHP_EOL;
echo "session.same_site=" . Config::get('session.same_site') . PHP_EOL;
echo "app.env=" . Config::get('app.env') . PHP_EOL;
$appKey = env('APP_KEY');
if ($appKey) {
	$show = substr($appKey, 0, 8) . '...' . strlen($appKey);
	echo "APP_KEY(masked)={$show}" . PHP_EOL;
} else {
	echo "APP_KEY=NOT SET" . PHP_EOL;
}
