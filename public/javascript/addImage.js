let images = document.querySelectorAll("input[type='file']");

for(let image of images){
    image.addEventListener("change", event =>{
        let label = document.querySelector(`label[for='${image.id}']`);
        label.classList.replace("input-images-box", "jobImage");
    });
}
