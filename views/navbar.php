<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/dropdown.css">
    <script src="/javascript/script.js"></script>
    <script src="/javascript/navbar.js"></script>
    <title>Home</title>

</head>

<body>
    
    <div class="topnav" id="myTopnav">
        <a href="/"><img class="logo" src="/img/logo.png"></a>
        
        <!--
      Odradio sam vam celu dropdown mehaniku da ne morate da se jebete sa programiranjem
      Sad samo stilizujte
      Logika je u dropdown.js, style je u dropdown.css
      I ni slucajno ne sklanjaj onaj # kod a koji enable-uje dropdown
      Sjebe sve
    -->
       
        <a href="/post-find-job" class="job">Pronađi oglase | Objavi oglas</a>
        <a href="/login" class="prijavise">Prijavi se</a>
        <span class="dropdown" id="dropdown">
            <span class="select">
                <a href="#" onclick="enableDropdown()"><img class="loginn" src="/img/profile.png"></a>
            </span>
            <ul class="dropdown-content">
                <li><a href="/profile/1">Moj Profil</a></li>
                <li><a href="#reviews">Moji komentari</a></li>
                <li><a href="#ratings">Moje ocene</a></li>
            
            </ul>
        </span>
        <a href="javascript:void(0);" class="icon" onclick="navBarSetter()"> <img src="/img/hamburger.png" width="50px"></a>
    </div>
</body>

</html>