<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Jauns jautājums</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222; line-height:1.6;">
    <h2>Jauns jautājums no kontaktformas</h2>

    <p><strong>Vārds un uzvārds:</strong> {{ $contactMessage->name }}</p>
    <p><strong>E-pasts:</strong> {{ $contactMessage->email }}</p>
    <p><strong>Tēma:</strong> {{ $contactMessage->subject }}</p>

    <p><strong>Ziņojums:</strong></p>
    <p>{!! nl2br(e($contactMessage->message)) !!}</p>
</body>
</html>