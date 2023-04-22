let form = document.querySelector('#job-data-form');
let formData = new FormData(form);
document.querySelector('#images').addEventListener("change", (event) =>{
    if(window.File && window.FileReader && window.FileList && window.Blob){
        const files = event.target.files;
        const output = document.querySelector('#result');
        for(let file of files){
            const picReader = new FileReader();
            picReader.addEventListener("load", e =>{
                const picFile = e.target;
                const div = document.createElement('div');
                div.innerHTML = `<img class="jobImage" src=${picFile.result}></img>`;
                output.appendChild(div);
            });
            picReader.readAsDataURL(file);
            formData.append(file.name, file);
        }
    }else{
        alert("Your browser doesn't support file API");
    }
});