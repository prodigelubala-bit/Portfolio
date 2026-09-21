<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $route = '', array $params = []): string
{
    $query = array_merge(['route' => $route], $params);
    $base = APP_BASE_PATH !== '' ? rtrim(APP_BASE_PATH, '/') . '/' : '';
    return $base . 'index.php?' . http_build_query($query);
}

function redirect(string $route, array $params = []): never
{
    header('Location: ' . url($route, $params));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function post(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

function getv(string $key, string $default = ''): string
{
    return trim((string) ($_GET[$key] ?? $default));
}

function findAll(string $table, string $orderBy): array
{
    $allowedTables = ['ouvriers', 'departements', 'fonctions', 'mois'];
    if (!in_array($table, $allowedTables, true)) {
        throw new InvalidArgumentException('Table non autorisée.');
    }

    return Database::pdo()->query("SELECT * FROM {$table} ORDER BY {$orderBy}")->fetchAll();
}

function countRows(string $table): int
{
    $allowedTables = ['ouvriers', 'departements', 'fonctions', 'mois', 'programmes', 'affectations'];
    if (!in_array($table, $allowedTables, true)) {
        throw new InvalidArgumentException('Table non autorisée.');
    }

    return (int) Database::pdo()->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
}

function currentRoute(): string
{
    return getv('route', 'home');
}
