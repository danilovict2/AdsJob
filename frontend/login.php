<?php
require __DIR__ . '/navbar.php';
?>

<div class="container">
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form>
                <label for="chk" aria-hidden="true">Prijavite se</label>
                <input type="text" name="txt" placeholder="Unesite E-mail" required id="email">
                <input type="password" name="pswd" placeholder="Unesite lozinku" id="password" minlength="8">
                <label class="showpas">Prikaži lozinku<input type="checkbox" class="pass" onclick="showPassword()"></label>

                <button onclick="auth()">Prijavi me</button> <br>
                <a href="signin.html" class="nalog">Nemate nalog? Registrujte se</a>
            </form>

        </div>
    </div>
</div>