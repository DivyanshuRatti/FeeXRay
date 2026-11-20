<?php

require __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/Database.php';
requireLogin('login.php');

header('Cache-Control: no-store');
header('X-Frame-Options: DENY');

function settingsInput($key)
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? $value : '';
}

function validSettingsName($name)
{
    return preg_match('/\A[^\p{Cc}]{1,100}\z/u', $name) === 1;
}

function settingsSaved($message)
{
    $_SESSION['settings_notice'] = $message;
    header('Location: settings.php', true, 303);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'HEAD', 'POST'], true)) {
    header('Allow: GET, HEAD, POST');
    http_response_code(405);
    exit;
}

$_SESSION['settings_token'] ??= bin2hex(random_bytes(32));
$csrfToken = $_SESSION['settings_token'];
if ($method === 'POST' && !hash_equals($csrfToken, settingsInput('csrf_token'))) {
    http_response_code(403);
    exit('Please reload Settings and try again.');
}

$userId = (int) $_SESSION['user_id'];
$pdo = getConnection();
$stmt = $pdo->prepare('SELECT name, email, password_hash FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();
if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

$error = '';
if ($method === 'POST') {
    switch (settingsInput('action')) {
        case 'profile':
            $name = trim(settingsInput('name'));
            if (!validSettingsName($name)) {
                $error = 'Enter a name between 1 and 100 characters.';
                break;
            }
            $stmt = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
            $stmt->execute([$name, $userId]);
            $_SESSION['name'] = $name;
            settingsSaved('Your name was updated.');
            break;

        case 'portfolio':
            $portfolioId = filter_var(settingsInput('portfolio_id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $name = trim(settingsInput('name'));
            if (!$portfolioId || !validSettingsName($name)) {
                $error = 'Choose a portfolio and enter a name between 1 and 100 characters.';
                break;
            }
            $stmt = $pdo->prepare('UPDATE portfolios SET name = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([$name, $portfolioId, $userId]);
            if ($stmt->rowCount() === 0) {
                $stmt = $pdo->prepare('SELECT id FROM portfolios WHERE id = ? AND user_id = ?');
                $stmt->execute([$portfolioId, $userId]);
                if (!$stmt->fetch()) {
                    http_response_code(404);
                    $error = 'Portfolio not found.';
                    break;
                }
            }
            settingsSaved('Your portfolio name was saved.');
            break;

        case 'password':
            $current = settingsInput('current_password');
            $password = settingsInput('new_password');
            if (strlen($password) > 72 || str_contains($password, "\0") || !preg_match('/\A.{8,}\z/us', $password)
                || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)
                || !preg_match('/[0-9]/', $password)) {
                $error = 'Use at least 8 characters, an uppercase letter, a lowercase letter, and a number. The maximum is 72 bytes.';
                break;
            }
            if ($password !== settingsInput('confirm_password')) {
                $error = 'The new passwords do not match.';
                break;
            }
            $now = time();
            $attempts = $_SESSION['settings_attempts'] ?? ['count' => 0, 'until' => 0];
            if ($attempts['until'] <= $now) {
                $attempts = ['count' => 0, 'until' => $now + 300];
            }
            if ($attempts['count'] >= 5) {
                $error = 'Too many password attempts. Try again in a few minutes.';
                break;
            }
            if (strlen($current) > 4096 || !password_verify($current, $user['password_hash'])) {
                $attempts['count']++;
                $_SESSION['settings_attempts'] = $attempts;
                $error = 'The current password is incorrect.';
                break;
            }
            unset($_SESSION['settings_attempts']);
            if ($password === $current) {
                $error = 'Choose a password different from your current one.';
                break;
            }

            $newHash = password_hash($password, PASSWORD_DEFAULT);
            // Match the previous hash in case the password changed in another session.
            $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ? AND password_hash = ?');
            $stmt->execute([$newHash, $userId, $user['password_hash']]);
            if ($stmt->rowCount() !== 1) {
                $error = 'Your password changed in another session. Please start again.';
                break;
            }
            session_regenerate_id(true);
            unset($_SESSION['settings_token']);
            settingsSaved('Your password was updated.');
            break;

        default:
            http_response_code(400);
            $error = 'This settings action was not recognized.';
    }
}

$stmt = $pdo->prepare('SELECT id, name, account_type FROM portfolios WHERE user_id = ? ORDER BY name, id');
$stmt->execute([$userId]);
$portfolios = $stmt->fetchAll();
$notice = $_SESSION['settings_notice'] ?? '';
unset($_SESSION['settings_notice']);

define('FEEXRAY_SETTINGS_VIEW', true);
require __DIR__ . '/../views/settings.php';
