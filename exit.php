<?php
// On peut récupérer les infos passées en GET (ex: exit.php?montant=99.99&devise=EUR)
$montant = isset($_GET['montant']) ? htmlspecialchars($_GET['montant']) : '99.99';
$devise = isset($_GET['devise']) ? htmlspecialchars($_GET['devise']) : 'EUR';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : 'exemple@mail.com';
$motif = isset($_GET['motif']) && $_GET['motif'] !== '' ? htmlspecialchars($_GET['motif']) : 'Non renseigné';

// Symbole de la devise
$symbols = ['EUR' => '€', 'USD' => '$', 'GBP' => '£'];
$symbol = $symbols[$devise] ?? '€';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecurePay - Paiement Validé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #6366f1;
            --success-color: #10b981;
            --text-color: #374151;
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        .container {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            padding: 30px 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        .header { margin-bottom: 20px; }

        .logo {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        /* --- ANIMATION DE VALIDATION --- */
        .success-animation {
            margin: 20px auto;
            width: 80px;
            height: 80px;
            position: relative;
        }

        .success-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #d1fae5;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: popIn 0.5s ease-out;
        }

        .success-circle i {
            color: var(--success-color);
            font-size: 40px;
            animation: checkIn 0.4s ease-out 0.3s both;
        }

        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes checkIn {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }

        .title {
            font-size: 22px;
            color: #111827;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        /* --- REÇU (sans carte bancaire) --- */
        .receipt {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            text-align: left;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 13px;
            color: #4b5563;
        }

        .receipt-row:not(:last-child) {
            border-bottom: 1px dashed #e5e7eb;
        }

        .receipt-row strong {
            color: #111827;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        .receipt-row .amount {
            color: var(--primary-color);
            font-size: 18px;
            font-weight: 700;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #d1fae5;
            color: #065f46;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* --- BOUTON RETOUR --- */
        .btn-back {
            display: block;
            width: 100%;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: transform 0.1s, background 0.3s;
            margin-bottom: 15px;
        }

        .btn-back:hover { background: var(--primary-light); }
        .btn-back:active { transform: scale(0.98); }
        .btn-back i { margin-right: 8px; }

        .footer {
            margin-top: 10px;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #f3f4f6;
            padding-top: 15px;
        }

        .footer i { color: var(--success-color); margin-right: 5px; }

        .brands {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            opacity: 0.5;
            font-size: 10px;
            font-weight: bold;
        }

        @media (max-width: 380px) {
            .container { padding: 20px 15px; }
            .receipt-row { font-size: 12px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fa-solid fa-credit-card"></i> SecurePay
            </div>
        </div>

        <!-- Animation de succès -->
        <div class="success-animation">
            <div class="success-circle">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>

        <h1 class="title">Paiement validé !</h1>
        <p class="subtitle">Votre transaction a été traitée avec succès.<br>Un reçu vous a été envoyé à <strong><?= $email ?></strong>.</p>

        <!-- Récapitulatif -->
        <div class="receipt">
            <div class="receipt-row">
                <span>Montant payé</span>
                <span class="amount"><?= $montant ?> <?= $symbol ?></span>
            </div>
            <div class="receipt-row">
                <span>Devise</span>
                <strong><?= $devise ?></strong>
            </div>
            <div class="receipt-row">
                <span>Motif</span>
                <strong><?= $motif ?></strong>
            </div>
            <div class="receipt-row">
                <span>E-mail</span>
                <strong><?= $email ?></strong>
            </div>
            <div class="receipt-row">
                <span>Statut</span>
                <span class="status-badge">
                    <i class="fa-solid fa-circle-check"></i> Payé
                </span>
            </div>
        </div>

        <!-- Bouton retour -->
        <a href="main.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>

        <div class="footer">
            <i class="fa-solid fa-lock"></i> Transaction sécurisée et cryptée
            <div class="brands">
                <span>VISA</span>
                <span>MASTERCARD</span>
                <span>AMEX</span>
            </div>
        </div>
    </div>

</body>
</html>