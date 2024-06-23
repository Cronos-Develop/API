<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid #eaeaea;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #eaeaea;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Recuperação de Senha</h2>
        </div>
        <div class="content">
            <p>Olá, {{ $data['userName'] }}</p>
            <p>O código abaixo será utilizado para redefinir sua senha:</p>
            <p><strong>{{$data['codigoConfirmacao']}}</strong></p>
            <p>Você solicitou a recuperação de sua senha. Clique no botão abaixo para redefinir sua senha:</p>
            <a href="#" class="button">Redefinir Senha</a>
            <p>Se você não solicitou a recuperação de senha, por favor ignore este e-mail.</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Cronos. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
