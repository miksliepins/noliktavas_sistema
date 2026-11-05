<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(false, 'Nederīvs pieprasījums.');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    json_out(false, 'Lūdzu aizpildiet visus laukus!');
}

try {
    $stmt = $conn->prepare(
        "SELECT id, lietotajvards, parole, loma FROM lietotaji_noliktavas WHERE epasts = ?"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['parole'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['lietotajvards'] = $user['lietotajvards'];
        $_SESSION['loma']          = $user['loma'];
        json_out(true, 'Veiksmīgi pieslēdzies!', 'panelis.php');
    }

    json_out(false, 'Nepareizs e-pasts vai parole!');
} catch (PDOException $e) {
    error_log($e->getMessage());
    json_out(false, 'Sistēmas kļūda. Mēģiniet vēlreiz.');
}