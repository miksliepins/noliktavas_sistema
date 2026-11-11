<?php
session_start();
require_once __DIR__ . '/db.php';

const ROLES = [
    'administrators'        => 'Administrators',
    'noliktavas_darbinieks' => 'Noliktavas darbinieks',
    'plauktu_kartotajs'     => 'Plauktu kārtotājs',
];

function esc(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function require_login(): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.html');
        exit;
    }
}

function has_role(array $roles): bool {
    return isset($_SESSION['loma']) && in_array($_SESSION['loma'], $roles, true);
}

function require_role(array $roles): void {
    require_login();
    if (!has_role($roles)) {
        http_response_code(403);
        exit('Jums nav piekļuves tiesību šai lapai.');
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Nederīvs pieprasījums. Atsvaidziniet lapu un mēģiniet vēlreiz.');
    }
}

function flash(string $message, string $type = 'ok'): void {
    $_SESSION['flash'] = ['msg' => $message, 'type' => $type];
}

function log_action(PDO $conn, string $text): void {
    $stmt = $conn->prepare("INSERT INTO izmainas (lietotajs_id, darbiba) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $text]);
}

function db_error(PDOException $e): string {
    error_log($e->getMessage());
    return $e->getCode() === '23000'
        ? 'Ieraksts ar šādiem datiem jau eksistē vai ir saistīts ar citiem ierakstiem.'
        : 'Sistēmas kļūda. Mēģiniet vēlreiz.';
}

function json_out(bool $ok, string $message, string $redirect = ''): void {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(
        ['ok' => $ok, 'message' => $message, 'redirect' => $redirect],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}