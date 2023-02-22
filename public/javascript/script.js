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

function auth() {
  var email = document.getElementById("email").value;
  var password = document.getElementById("password").value;
  if (email == "primer@gmail.com" && password == "primer123") {
    window.location.href = "indx-profile.html";
    alert("Uspesna prijava!");

  }
  else {
    alert("Netacne informacije! Pokusajte ponovo");
    return;

  }
}




