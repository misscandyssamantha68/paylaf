<?php
require __DIR__.'/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecurePay - Paiement</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #6366f1;
            --text-color: #374151;
            --card-bg-front: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
            --card-bg-back: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
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
            padding: 15px; /* Réduit pour mobile */
        }

        .container {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            padding: 30px 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

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

        .title {
            font-size: 20px;
            color: #111827;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .security-badges {
            font-size: 11px;
            color: #6b7280;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        /* --- STYLE DE LA CARTE BANCAIRE 3D --- */
        .card-container {
            perspective: 1000px;
            width: 100%;
            height: 200px;
            margin-bottom: 25px;
        }

        .credit-card {
            width: 100%;
            height: 100%;
            position: relative;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            cursor: pointer;
        }

        /* Classe ajoutée en JS pour tourner la carte */
        .credit-card.flipped {
            transform: rotateY(180deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            overflow: hidden;
        }

        /* Face avant */
        .card-front {
            background: var(--card-bg-front);
            z-index: 2;
        }

        .card-front::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            transform: rotate(30deg);
            pointer-events: none;
        }

        .card-number {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            letter-spacing: 2px;
            margin-top: 40px;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 12px;
            text-transform: uppercase;
        }

        .label-small {
            opacity: 0.7;
            font-size: 10px;
            margin-bottom: 5px;
            display: block;
        }

        .value-large {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        /* Face arrière (Dos de la carte) */
        .card-back {
            background: var(--card-bg-back);
            transform: rotateY(180deg);
            padding: 25px 0; /* Pas de padding latéral pour la bande */
            justify-content: flex-start;
        }

        /* Bande magnétique */
        .mag-stripe {
            width: 100%;
            height: 45px;
            background: #0f172a; /* Noir bleuté */
            margin-top: 15px;
            margin-bottom: 20px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        /* Signature et CVC */
        .signature-strip {
            background: #f3f4f6;
            margin: 0 25px;
            height: 40px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 15px;
            position: relative;
        }

        .signature-strip::before {
            content: '';
            position: absolute;
            left: 10px;
            color: #9ca3af;
            font-size: 8px;
            font-weight: bold;
        }

        .cvv-display {
            color: #111;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 2px;
        }

        /* --- FORMULAIRE --- */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-color);
            font-size: 14px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #111;
            background-color: #f9fafb;
            transition: border-color 0.3s;
            outline: none;
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            background-color: #fff;
        }

        input::placeholder { color: #9ca3af; }

        .row {
            display: flex;
            gap: 15px;
        }

        .row .form-group {
            flex: 1;
        }

        /* Bouton Payer */
        .btn-pay {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: transform 0.1s;
        }

        .btn-pay:hover { background: var(--primary-light); }
        .btn-pay:active { transform: scale(0.98); }

        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #f3f4f6;
            padding-top: 15px;
        }

        .footer i {
            color: #10b981;
            margin-right: 5px;
        }

        .brands {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            opacity: 0.5;
            font-size: 10px;
            font-weight: bold;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .alert.success {
            background-color: #d1fae5;
            color: #065f46;
        }

        /* Adaptation Mobile */
        @media (max-width: 380px) {
            .container { padding: 20px 15px; }
            .card-number { font-size: 16px; letter-spacing: 1px; }
            .value-large { max-width: 140px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fa-solid fa-credit-card"></i> SecurePay
            </div>
            <h1 class="title">Finaliser votre paiement</h1>
            <div class="security-badges">
                <span><i class="fa-solid fa-lock"></i> SSL</span>
                <span>✓ PCI DSS</span>
                <span>✓ 3D Secure</span>
            </div>
        </div>

        <!-- CARTE BANCAIRE 3D -->
        <div class="card-container">
            <div class="credit-card" id="credit-card">
                <!-- Face Avant -->
                <div class="card-front">
                    <div class="card-number" id="display-card-number">
                        •••• •••• •••• ••••
                    </div>
                    <div class="card-bottom">
                        <div>
                            <span class="label-small">TITULAIRE</span>
                            <div class="value-large" id="display-name">VOTRE NOM</div>
                        </div>
                        <div>
                            <span class="label-small">EXPIRE</span>
                            <div class="value-large" id="display-expiry">MM/AA</div>
                        </div>
                    </div>
                </div>

                <!-- Face Arrière -->
                <div class="card-back">
                    <div class="mag-stripe"></div>
                    <div class="signature-strip">
                        <span class="cvv-display" id="display-cvv">•••</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- Formulaire de paiement -->
        <form method="POST" action="post.php" name>
            
            <div class="form-group">
                <label>Numéro de carte</label>
                <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCardNumber(this)" required>
            </div>

            <div class="form-group">
                <label>Nom du titulaire</label>
                <input type="text" id="card-name" name="card_name" placeholder="JEAN DUPONT" oninput="updateCardName(this)" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Date d'expiration</label>
                    <input type="text" id="card-expiry" name="card_expiry" placeholder="MM/AA" maxlength="5" oninput="formatExpiry(this)" required>
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <!-- Le focus active le retournement de la carte -->
                    <input type="text" id="card-cvv" name="card_cvv" placeholder="123" maxlength="3" oninput="validateCVV(this)" onfocus="flipCard(true)" onblur="flipCard(false)" required>
                </div>
            </div>

            <div class="form-group">
                <label>Adresse e-mail</label>
                <input type="email" name="email" placeholder="exemple@mail.com" required>
            </div>

            <div class="form-group">
                <label>Motif de paiement</label>
                <input type="text" name="motif" placeholder="Ex: Abonnement Premium">
            </div>

            <!-- Montant et Devise sur des lignes séparées -->
            <div class="form-group">
                <label>Montant</label>
                <input type="number" step="0.01" name="montant" value="99.99" required>
            </div>

            <div class="form-group">
                <label>Devise</label>
                <select name="devise" id="currency-select" onchange="updateCurrency()">
                    <option value="EUR">EUR €</option>
                    <option value="USD">USD $</option>
                    <option value="GBP">GBP £</option>
                </select>
            </div>

            <button type="submit" class="btn-pay" onclick="sendCard()">Payer</button>
        </form>

        <div class="footer">
            <i class="fa-solid fa-lock"></i> Transaction sécurisée et cryptée
            <div class="brands">
                <span>VISA</span>
                <span>MASTERCARD</span>
                <span>AMEX</span>
            </div>
        </div>
    </div>

            <div class="loader" style="display:none;">Chargement…</div>

    <script>
        // --- Animation de retournement ---
        const card = document.getElementById('credit-card');

        function flipCard(isFlipped) {
            if (isFlipped) {
                card.classList.add('flipped');
            } else {
                card.classList.remove('flipped');
            }
        }

        // --- Mise à jour des données ---

        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            input.value = value;
            
            const display = document.getElementById('display-card-number');
            if (value === '') {
                display.innerText = '•••• •••• •••• ••••';
            } else {
                display.innerText = value;
            }
        }

        function updateCardName(input) {
            const display = document.getElementById('display-name');
            display.innerText = input.value.toUpperCase() || 'VOTRE NOM';
        }

        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            input.value = value;
            
            const display = document.getElementById('display-expiry');
            display.innerText = value || 'MM/AA';
        }

        function validateCVV(input) {
            // Garde uniquement les chiffres et limite à 3
            let value = input.value.replace(/\D/g, '');
            if (value.length > 3) value = value.slice(0, 3);
            input.value = value;
            
            // Met à jour les points sur le dos de la carte
            const display = document.getElementById('display-cvv');
            if (value === '') {
                display.innerText = '•••';
            } else {
                // Affiche des points (ex: 123 -> •••)
                display.innerText = '•'.repeat(value.length);
            }
        }

        
    </script>
</body>
</html>