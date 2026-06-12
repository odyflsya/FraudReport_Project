<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kasus;

$k = Kasus::with('kerugianFraud.recoveries')->where('jenis_laporan', 'semester')->first();
if (! $k) {
    echo "no data\n";
    exit(0);
}
$kr = $k->kerugianFraud;
echo 'ljk_rill=' . ($kr->ljk_rill ?? 'NULL') . "\n";
echo 'ljk_potensial=' . ($kr->ljk_potensial ?? 'NULL') . "\n";
echo 'ljk_recovery=' . ($kr->ljk_recovery ?? 'NULL') . "\n";
echo 'sumHistory=' . $kr->recoveries->where('kategori', 'ljk')->sum('amount') . "\n";
echo 'outstanding=' . $kr->getOutstandingForKategori('ljk') . "\n";
