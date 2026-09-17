<?php
declare(strict_types=1);

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function current_user(): ?array {
    global $pdo;
    if (empty($_SESSION['user_id'])) return null;
    static $user = false;
    if ($user !== false) return $user;
    $stmt = $pdo->prepare('SELECT u.*, ur.name AS role_name FROM users u INNER JOIN user_roles ur ON ur.id = u.role_id WHERE u.id = ? LIMIT 1');
    $stmt->execute([(int)$_SESSION['user_id']]);
    $user = $stmt->fetch() ?: null;
    if (!$user) {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_phone']);
    }
    return $user;
}

function require_login(): void {
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool {
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function format_money($amount): string {
    return 'TZS ' . number_format((float)$amount, 0);
}

function time_ago(string $datetime): string {
    $time = strtotime($datetime);
    if (!$time) return $datetime;
    $diff = max(0, time() - $time);
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
    if ($diff < 604800) return floor($diff / 86400) . ' day' . (floor($diff / 86400) == 1 ? '' : 's') . ' ago';
    return date('d M Y', $time);
}

function lookup_id(PDO $pdo, string $table, string $name): ?int {
    $allowed = ['listing_types','listing_statuses','crops','units','regions','districts','wards','villages','user_roles'];
    if (!in_array($table, $allowed, true)) return null;
    $stmt = $pdo->prepare("SELECT id FROM {$table} WHERE name = ? LIMIT 1");
    $stmt->execute([$name]);
    $row = $stmt->fetch();
    return $row ? (int)$row['id'] : null;
}
