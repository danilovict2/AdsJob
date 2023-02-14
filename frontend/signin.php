<?php
require __DIR__ . '/navbar.php';
?>

<div class="container">
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form>
                <label for="chk" aria-hidden="true">Registrujte se</label>
                <input type="email" name="txt" placeholder="Unesite E-mail" required="">
                <input type="text" placeholder="Unesite vaše ime">
                <input type="text" placeholder="Unesite vaše prezime">
                <input type="password" name="pswd" placeholder="Unesite lozinku" class="password" minlength="8">
                <input type="password" name="pswd" placeholder="Potvrdi lozinku" class="password" minlength="8">
                <label class="showpas">Prikaži lozinku<input type="checkbox" class="pass" onclick="showPassword()"></label>

                <button>Registruj me</button> <br>
                <a href="login.php" class="nalog">Već imate nalog? Prijavite se</a>
            </form>

        </div>

    </div>
</div>