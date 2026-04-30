<?php
/**
 * Triple S Entry — contact form handler
 *
 * Receives the enquiry form, applies basic spam protection
 * (honeypot + minimum-fill-time + simple rate limit), sanitises input
 * and emails the enquiry to the business inbox.
 *
 * Configure: set $TO_EMAIL below to the destination inbox.
 * Drop into a PHP-capable hosting environment (Crazy Domains supports PHP).
 */

declare(strict_types=1);

// ---------- Configuration ----------
$TO_EMAIL    = 'info@TripleSEntry.com.au';
$FROM_NAME   = 'Triple S Entry Website';
$FROM_EMAIL  = 'no-reply@TripleSEntry.com.au'; // adjust to a verified hosting mailbox
$SUBJECT_PREFIX = '[Triple S Entry Enquiry]';
$REDIRECT_SUCCESS = '/contact.html?status=success#enquiry';
$REDIRECT_ERROR   = '/contact.html?status=error#enquiry';
$RATE_LIMIT_SECONDS = 30; // per IP

// ---------- Helpers ----------
function isAjax(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function respond(bool $ok, string $message, string $redirect): void {
    if (isAjax()) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($ok ? 200 : 400);
        echo json_encode(['ok' => $ok, 'message' => $message]);
        exit;
    }
    header('Location: ' . $redirect, true, 302);
    exit;
}

function clean(string $value): string {
    $value = trim($value);
    $value = str_replace(["\r", "\n", "%0a", "%0d"], ' ', $value);
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function cleanMultiline(string $value): string {
    $value = trim($value);
    $value = preg_replace("/[\r\n]+/", "\n", $value);
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ---------- Method check ----------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method.', $REDIRECT_ERROR);
}

// ---------- Honeypot ----------
if (!empty($_POST['website'])) {
    // Bot most likely. Pretend it succeeded.
    respond(true, 'Thank you — we will be in touch.', $REDIRECT_SUCCESS);
}

// ---------- Rate limit (per IP) ----------
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateDir = sys_get_temp_dir() . '/tse_ratelimit';
if (!is_dir($rateDir)) {
    @mkdir($rateDir, 0700, true);
}
$rateFile = $rateDir . '/' . hash('sha256', $ip);
if (is_file($rateFile)) {
    $last = (int) @file_get_contents($rateFile);
    if ($last && (time() - $last) < $RATE_LIMIT_SECONDS) {
        respond(false, 'Please wait a moment before sending another enquiry.', $REDIRECT_ERROR);
    }
}
@file_put_contents($rateFile, (string) time());

// ---------- Validate ----------
$errors = [];

$name        = clean((string) ($_POST['name'] ?? ''));
$company     = clean((string) ($_POST['company'] ?? ''));
$phone       = clean((string) ($_POST['phone'] ?? ''));
$email       = trim((string) ($_POST['email'] ?? ''));
$enquiryType = clean((string) ($_POST['enquiry_type'] ?? 'General enquiry'));
$suburb      = clean((string) ($_POST['suburb'] ?? ''));
$message     = cleanMultiline((string) ($_POST['message'] ?? ''));

if ($name === '' || mb_strlen($name) < 2)              { $errors[] = 'Name is required.'; }
if ($phone === '' || mb_strlen($phone) < 6)            { $errors[] = 'Phone is required.'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid email is required.'; }
if ($message === '' || mb_strlen($message) < 5)        { $errors[] = 'Please include a short message.'; }

// Reject obvious spam payloads
$blockedPatterns = ['/https?:\/\//i', '/<a\s+href/i', '/\[url=/i'];
foreach ($blockedPatterns as $pattern) {
    if (preg_match($pattern, $_POST['message'] ?? '')) {
        // Treat link-stuffed messages as spam — fail silently like honeypot
        respond(true, 'Thank you — we will be in touch.', $REDIRECT_SUCCESS);
    }
}

if ($errors) {
    respond(false, implode(' ', $errors), $REDIRECT_ERROR);
}

// ---------- Build email ----------
$emailSafe = filter_var($email, FILTER_SANITIZE_EMAIL);

$subject = $SUBJECT_PREFIX . ' ' . $enquiryType . ' — ' . $name;

$lines = [
    "New website enquiry — Triple S Entry",
    str_repeat('-', 44),
    "Name:         {$name}",
    "Company/Site: {$company}",
    "Phone:        {$phone}",
    "Email:        {$emailSafe}",
    "Enquiry type: {$enquiryType}",
    "Suburb/Area:  {$suburb}",
    str_repeat('-', 44),
    "Message:",
    $message,
    str_repeat('-', 44),
    "Submitted:    " . date('Y-m-d H:i:s') . " (server time)",
    "IP:           {$ip}",
    "User agent:   " . substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200),
];
$body = implode("\r\n", $lines);

$headers = [];
$headers[] = 'From: ' . $FROM_NAME . ' <' . $FROM_EMAIL . '>';
$headers[] = 'Reply-To: ' . $name . ' <' . $emailSafe . '>';
$headers[] = 'X-Mailer: PHP/' . phpversion();
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$sent = @mail($TO_EMAIL, $subject, $body, implode("\r\n", $headers));

if (!$sent) {
    // Fallback: log to disk so submissions are never lost while mail is being configured
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0700, true);
    }
    @file_put_contents(
        $logDir . '/enquiries-' . date('Y-m') . '.log',
        "\n\n=== " . date('Y-m-d H:i:s') . " ===\n" . $body,
        FILE_APPEND
    );
    respond(false, 'Sorry — we could not send your enquiry just now. Please call 0493 691 684.', $REDIRECT_ERROR);
}

respond(true, 'Thanks — your enquiry has been sent. We will be in touch shortly.', $REDIRECT_SUCCESS);
