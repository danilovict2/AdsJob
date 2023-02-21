<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/../css/style.css">
    <link rel="stylesheet" href="/../css/dropdown.css">
    <script src="/../javascript/script.js"></script>
    <script src="/../javascript/navbar.js"></script>
    <title>Home</title>

</head>

<body>
    
    <div class="topnav" id="myTopnav">
        <a href="index.php"><img class="logo" src="../img/logo.png"></a>
        <a href="#reviews" class="reviews">Ocene korisnika</a>
        <!--
      Odradio sam vam celu dropdown mehaniku da ne morate da se jebete sa programiranjem
      Sad samo stilizujte
      Logika je u dropdown.js, style je u dropdown.css
      I ni slucajno ne sklanjaj onaj # kod a koji enable-uje dropdown
      Sjebe sve
    -->
        <span class="dropdown" id="dropdown">
            <span class="select">
                <a href="#" onclick="enableDropdown()"><img class="loginn" src="../img/login.png"></a>
            </span>
            <ul class="dropdown-content">
                <li>My Profile</li>
                <li>My Reviews</li>
                <li>My ratings</li>
            </ul>
        </span>
        <a href="postj-findj.php" class="job">Pronađi oglase | Objavi oglas</a>
        <a href="javascript:void(0);" class="icon" onclick="navBarSetter()"> <img src="img/hamburger.png" width="50px"></a>
    </div>
</body>

</html>