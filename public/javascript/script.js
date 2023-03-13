function showPassword() {
  var x = document.getElementsByClassName("password");
  for(var i=0; i<x.length; ++i){
    if (x[i].type === "password") {
      x[i].type = "text";
    } else {
      x[i].type = "password";
    }
  }
}

let usrLoggedIn = false;

function auth() {
  let email = document.getElementById("email").value;
  let password = document.getElementById("password").value;
  if (email == "primer@gmail.com" && password == "primer123") {
    alert("Uspesna prijava!");
    document.querySelector("#dropdown").hidden = false;

  }
  else {
    alert("Netacne informacije! Pokusajte ponovo");
    return;

  }
}






