<?php

$msg = $_SESSION["MSG_REQUEST"] ?? [];
$nivel_acceso = $msg["nivel_acceso"]["value"] ?? "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/assets/css/login.css">
</head>

<body>
    <h1>LOGIN</h1>
    <form action="/login-in" method="POST">
        <label>Formulario</label>
        <select name="nivel_acceso">
            <option value="user" <?= $nivel_acceso == "user" ? "selected" : "" ?>>Usuario</option>
            <option value="editor" <?= $nivel_acceso == "editor" ? "selected" : "" ?>>Editor</option>
            <option value="admin" <?= $nivel_acceso == "admin" ? "selected" : "" ?>>Administrador</option>
        </select>
        <input type="text" name="usuario" value="<?= $msg["usuario"]["value"] ?? "" ?>">
        <?php if (isset($msg["usuario"]["message"])) : ?>
            <span style="background:red"><?= $msg["usuario"]["message"] ?></span>
        <?php endif; ?>
        <input type="password" name="password">
        <?php if (isset($msg["password"]["message"])) : ?>
            <span style="background:red"><?= $msg["password"]["message"] ?></span>
        <?php endif; ?>
        <input type="submit" value="logear">
    </form>
</body>

</html>