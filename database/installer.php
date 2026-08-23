<?php
/**
 * HomeFix Quetta - Database Migrator & Seed Installer
 */
header('Content-Type: text/html; charset=utf-8');

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'homefix_quetta';

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HomeFix Quetta - Database Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-xl w-full bg-slate-800 border border-slate-700 rounded-2xl p-8 shadow-2xl">
        <h1 class="text-2xl font-bold text-emerald-400 mb-2">HomeFix Quetta Database Setup</h1>
        <p class="text-sm text-slate-400 mb-6">Automated database initialization and seed import for Quetta, Balochistan.</p>';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db`");

    $sqlFile = __DIR__ . '/homefix_quetta.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL schema file not found at: " . $sqlFile);
    }

    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);

    // Verify tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo '<div class="bg-emerald-950/60 border border-emerald-500/30 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 text-emerald-400 font-semibold mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Database Initialized & Seeded Successfully!
            </div>
            <p class="text-xs text-slate-300">Created ' . count($tables) . ' tables: <code class="text-emerald-300">' . implode(', ', $tables) . '</code></p>
          </div>';

    echo '<div class="space-y-3 text-sm text-slate-300 mb-6">
            <div class="p-3 bg-slate-700/50 rounded-lg">
                <strong class="text-emerald-400">Admin Account:</strong> admin@homefix.pk / <span class="font-mono text-white">Admin@123</span>
            </div>
            <div class="p-3 bg-slate-700/50 rounded-lg">
                <strong class="text-emerald-400">Customer Demo:</strong> customer@homefix.pk / <span class="font-mono text-white">Customer@123</span>
            </div>
          </div>';

    echo '<div class="flex gap-4">
            <a href="../index.php" class="flex-1 text-center py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium transition">Visit Website</a>
            <a href="../admin/login.php" class="flex-1 text-center py-2.5 px-4 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-medium transition">Admin Panel</a>
          </div>';

} catch (Exception $e) {
    echo '<div class="bg-rose-950/60 border border-rose-500/30 rounded-xl p-4 mb-6 text-rose-300">
            <strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '
          </div>';
}

echo '</div></body></html>';
