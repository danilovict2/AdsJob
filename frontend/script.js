function showPassword() {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

function navBarSetter() {
    var navBar = document.getElementById("myTopnav");
    if (navBar.className === "topnav") {
      navBar.className += " responsive";
    } else {
      navBar.className = "topnav";
    }

  }
  
function auth(){
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    if(email == "primer@gmail.com" && password == "primer123"){
        window.location.href = "indx-profile.html";
        alert("Uspesna prijava!");
      
    }
    else{
      alert("Netacne informacije! Pokusajte ponovo");
      return;

    }
  }


  
