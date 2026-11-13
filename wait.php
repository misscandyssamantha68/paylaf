<?php
$next = $_GET['next'] ?? 'exit.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification…</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; width: 100%; }
        body {
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; min-height: 100dvh; padding: 16px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f0f2f5; -webkit-font-smoothing: antialiased;
        }
        .container { text-align: center; padding: 24px; width: 100%; max-width: 400px; }
        .spinner {
            width: clamp(40px, 10vw, 56px); height: clamp(40px, 10vw, 56px);
            border: 4px solid #e2e8f0; border-top-color: #4f46e5; border-radius: 50%;
            margin: 0 auto; animation: spin 1s linear infinite;
        }
        .message { margin-top: clamp(12px, 3vw, 20px); font-size: clamp(14px, 3.5vw, 16px); color: #64748b; line-height: 1.5; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (prefers-reduced-motion: reduce) { .spinner { animation-duration: 2s; } }
        @media (max-width: 480px) { .container { padding: 16px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner" role="progressbar" aria-label="Vérification en cours"></div>
        <p class="message">Vérification de votre paiement…</p>
    </div>
    <script>
        // On décode proprement l'URL cible (gère &amp; → &, %26, etc.)
        var target = <?= json_encode($next, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        setTimeout(function () {
            window.location.replace(target);
        }, 5000);
    </script>
</body>
</html>