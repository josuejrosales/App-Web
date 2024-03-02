<?php
$msg = $_SESSION["MSG_REQUEST"] ?? [];
$nivel_acceso = $msg["nivel_acceso"]["value"] ?? "user";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=block" rel="stylesheet" />
    <link rel="stylesheet" href="/public/assets/css/login.css" />
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="/login-in" method="POST" autocomplete="off">
        <h3>Login</h3>

        <div class="content-buttons">
            <button type="button" data-item="0" data-type="user" onclick="handleClick(event)">User</button>
            <button type="button" data-item="1" data-type="editor" onclick="handleClick(event)">Editor</button>
            <button type="button" data-item="2" data-type="admin" onclick="handleClick(event)">Admin</button>
        </div>

        <input id="typeUser" type="hidden" name="nivel_acceso" value="<?= $nivel_acceso ?>" />

        <label for="username">Username</label>

        <input type="text" placeholder="Example" name="usuario" value="<?= $msg["usuario"]["value"] ?? "" ?>" />

        <p class="msg-error">
            <?= isset($msg["usuario"]["message"]) ? $msg["usuario"]["message"] : '' ?>
        </p>

        <label for="password">Password</label>
        <input type="password" placeholder="Password" name="password" />
        <p class="msg-error">
            <?= isset($msg["password"]["message"]) ? $msg["password"]["message"] : '' ?>
        </p>
        <div class="content-btn">
            <input type="submit" class="login-in" value="Log in" />
        </div>
    </form>

    <script>
        var timeoutId;
        var typeUser = document.getElementById("typeUser");

        function resetSelectedButtons() {
            document
                .querySelectorAll("button.selected")
                .forEach((btn) => btn.classList.remove("selected"));
        }

        function setLeftPosition(value) {
            document.documentElement.style.setProperty(
                "--var-left-position",
                value
            );
        }

        function updateSelectedItem(element) {
            resetSelectedButtons();
            setLeftPosition(element.dataset.item);

            typeUser.value = element.dataset.type;
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => element.classList.add("selected"), 200);
        }

        function handleClick(event) {
            event.preventDefault();
            updateSelectedItem(event.target);
        }
        document.addEventListener("DOMContentLoaded", function() {

            const defaultItemButton = document.querySelector(
                `button[data-type='${typeUser.value}']`
            );
            updateSelectedItem(defaultItemButton);
        });
    </script>
</body>

</html>