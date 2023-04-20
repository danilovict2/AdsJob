let modal = document.getElementById("modal");

let button = document.getElementById("openModal");

let closeButton = document.getElementById("close3");


button.onclick = function () {
    modal.style.display = "block";
}

closeButton.onclick = function () {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}