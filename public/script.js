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

function dropDown() {
  var click = document.getElementById("list-items");
  if (click.style.display === "none") {
    click.style.display = "block";
  } else {
    click.style.display = "none";
  }
}

function enableDropdown(){
    if(typeof enableDropdown.isEnabled == 'undefined'){
        enableDropdown.isEnabled = false;
    }
    enableDropdown.isEnabled = !enableDropdown.isEnabled;
    var dropdownContent = document.getElementById("dropdown-content");
    if(enableDropdown.isEnabled){
      dropdownContent.style.display = "block";
    }else{
      dropdownContent.style.display = "none";
    }
}

window.onclick = function(event){
  if(!event.target.matches('.enableDropdown')){
    if(enableDropdown.isEnabled){
      document.getElementById("dropdown-content").style.display = "none";
    }
  }
  
}
