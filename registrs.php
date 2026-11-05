<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(false, 'Nederīvs pieprasījums.');
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    json_out(false, 'Lūdzu aizpildiet visus laukus!');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(false, 'Lūdzu ievadiet derīgu e-pastu!');
}
if (mb_strlen($password) < 6) {
    json_out(false, 'Parolei jābūt vismaz 6 rakstzīmēm!');
}

try {
    $check = $conn->prepare(
        "SELECT id FROM lietotaji_noliktavas WHERE epasts = ? OR lietotajvards = ?"
    );
    $check->execute([$email, $username]);
    if ($check->fetch()) {
        json_out(false, 'Šis e-pasts vai lietotājvārds jau ir reģistrēts!');
    }

    $insert = $conn->prepare(
        "INSERT INTO lietotaji_noliktavas (lietotajvards, epasts, parole, loma)
         VALUES (?, ?, ?, 'plauktu_kartotajs')"
    );
    $insert->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

    json_out(true, 'Konts ir veiksmīgi izveidots! Tagad varat pieslēgties.', 'login.html');
} catch (PDOException $e) {
    error_log($e->getMessage());
    json_out(false, 'Sistēmas kļūda. Mēģiniet vēlreiz.');
}