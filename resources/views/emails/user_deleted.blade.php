<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Konts dzēsts</title>
</head>
<body style="font-family:Arial,sans-serif; color:#333; line-height:1.6;">
    <h2>Sveicināti, {{ $user->name }}!</h2>

    <p>Informējam, ka jūsu konts vietnē tika dzēsts.</p>

    <p><strong>Dzēšanas iemesls:</strong></p>

    <div style="padding:12px; background:#f8f8f8; border:1px solid #ddd; border-radius:8px;">
        {{ $reason }}
    </div>

    <p style="margin-top:20px;">
        Ja uzskatāt, ka tā ir kļūda, lūdzu, sazinieties ar administrāciju.
    </p>

    <p>Ar cieņu,<br>Feimaņu administrācija</p>
</body>
</html>
