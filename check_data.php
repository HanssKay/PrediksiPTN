<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$d = DB::table('dataset_prediksi')->first();
if (!$d) {
    echo "No data found!\n";
    exit;
}

echo "=== Columns with non-null/non-zero values ===\n";
foreach ((array)$d as $k => $v) {
    if ($v !== null && $v !== '' && $v != 0) {
        echo "$k = $v\n";
    }
}

echo "\n=== All column names ===\n";
echo implode(', ', array_keys((array)$d));
echo "\n\nTotal records: " . DB::table('dataset_prediksi')->count() . "\n";
