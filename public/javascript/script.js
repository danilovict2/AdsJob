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







