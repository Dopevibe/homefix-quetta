<?php
/**
 * HomeFix Quetta - Database Connection Layer (PDO)
 * Platform: PHP 8+ / MySQL 8+ / MariaDB
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $host;
    private static $db;
    private static $user;
    private static $pass;
    private static $charset = 'utf8mb4';
    private static $port;
    
    private static $instance = null;
    private $pdo;

    private function __construct() {
        self::$host = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'sql206.infinityfree.com'));
        self::$db   = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'if0_42665674_homefix_quetta'));
        self::$user = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'if0_42665674'));
        self::$pass = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($_ENV['DB_PASS'] ?? 'oBcn5e8gUQaQChz'));
        self::$port = (int)(defined('DB_PORT') ? DB_PORT : (getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? 3306)));

        $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db . ";charset=" . self::$charset;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, self::$user, self::$pass, $options);
        } catch (PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            if (defined('IS_AJAX') && IS_AJAX) {
                json_response(false, 'Database connection error: ' . $e->getMessage(), [], 500);
            } else {
                die('<div style="font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif; max-width: 600px; margin: 50px auto; padding: 24px; border-radius: 16px; background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B;">
                    <h2 style="margin-top:0; font-size: 20px;">Database Connection Issue</h2>
                    <p style="font-size: 14px; line-height: 1.6;">Could not connect to the MySQL database server. Please verify your credentials in <code>config/database.php</code>.</p>
                    <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid #FECACA; font-family: monospace; font-size: 12px; color: #7F1D1D; word-break: break-all;">' . htmlspecialchars($e->getMessage()) . '</div>
                </div>');
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public static function query($sql, $params = []) {
        $db = self::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll($sql, $params = []) {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetch($sql, $params = []) {
        return self::query($sql, $params)->fetch();
    }

    public static function execute($sql, $params = []) {
        return self::query($sql, $params)->rowCount();
    }

    public static function lastInsertId() {
        return self::getInstance()->getConnection()->lastInsertId();
    }
}
