<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <form action="{{route('login.index')}}" method="get">
        <button type="submit">Fazer Login</button>
    </form>

    <form action="{{route('register.index')}}" method="get">
        <button type="submit">Fazer Cadastro</button>
    </form>
</body>
</html>