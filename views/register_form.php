<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        form { max-width: 400px; }
        label { display: block; margin-top: 15px; }
        input { width: 100%; padding: 8px; }
        button { margin-top: 20px; padding: 10px; }
        .message { margin-top: 20px; color: #c00; }
    </style>
</head>
<body>

<h1>Register</h1>

<form method="post" action="">
    <label>
        Username
        <input type="text" name="username" required>
    </label>

    <label>
        Password
        <input type="password" name="password" required>
    </label>

    <button type="submit">Register</button>
</form>

</body>
</html>