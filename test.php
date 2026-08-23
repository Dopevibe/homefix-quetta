<?php
/**
 * HomeFix Quetta - Server Health & Diagnostics Tool
 */
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Health Check | HomeFix Quetta</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0F172A; color: #F8FAFC; padding: 30px 20px; line-height: 1.6; }
        .container { max-width: 650px; margin: 0 auto; background: #1E293B; border: 1px solid #334155; border-radius: 16px; padding: 28px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        h1 { color: #2DD4BF; margin-top: 0; font-size: 24px; }
        .item { padding: 12px 16px; border-radius: 10px; margin-bottom: 12px; font-size: 14px; display: flex; justify-content: space-between; align-items: center; }
        .pass { background: rgba(16, 185, 129, 0.15); border: 1px solid #059669; color: #6EE7B7; }
        .fail { background: rgba(239, 68, 68, 0.15); border: 1px solid #DC2626; color: #FCA5A5; }
        .btn { display: inline-block; background: #0D9488; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 15px; }
        .btn:hover { background: #0F766E; }
    </style>
</head>
<body>
<div class="container">
    <h1>HomeFix Quetta Server Diagnostics</h1>

    <div class="item pass">
        <span>PHP Version:</span>
        <strong><?= PHP_VERSION ?></strong>
    </div>

    <?php
    $pdoOk = extension_loaded('pdo_mysql');
    ?>
    <div class="item <?= $pdoOk ? 'pass' : 'fail' ?>">
        <span>PDO MySQL Extension:</span>
        <strong><?= $pdoOk ? 'Enabled' : 'Disabled' ?></strong>
    </div>

    <?php
    $dbError = null;
    $tableCount = 0;
    try {
        require_once __DIR__ . '/config/database.php';
        $db = Database::getInstance()->getConnection();
        $tables = $db->query("SHOW TABLES")->fetchAll();
        $tableCount = count($tables);
        $dbOk = true;
    } catch (Exception $ex) {
        $dbOk = false;
        $dbError = $ex->getMessage();
    }
    ?>

    <div class="item <?= $dbOk ? 'pass' : 'fail' ?>">
        <span>Database Connection:</span>
        <strong><?= $dbOk ? 'Connected (' . $tableCount . ' tables)' : 'Failed' ?></strong>
    </div>

    <?php if (!$dbOk): ?>
        <div style="background: #450A0A; border: 1px solid #991B1B; padding: 12px; border-radius: 8px; font-size: 12px; color: #FECACA; word-break: break-all; margin-bottom: 15px;">
            <strong>Error Details:</strong> <?= htmlspecialchars($dbError) ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php" class="btn">Open HomeFix Website →</a>
    </div>
</div>
</body>
</html>
