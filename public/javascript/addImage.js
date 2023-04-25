var previewImage = (event) => {
    const imageFiles = event.target.files;
    const imageFilesLength = imageFiles.length;
    if (imageFilesLength > 0) {
        const imageSrc = URL.createObjectURL(imageFiles[0]);
        var imagePreviewElement = document.querySelector("#preview-selected-image");
        imagePreviewElement.src = imageSrc;

        imagePreviewElement.style.display = "block";
    }
};

var previewImage2 = (event) => {
    const imageFiles = event.target.files;
    const imageFilesLength = imageFiles.length;
    if (imageFilesLength > 0) {
        const imageSrc = URL.createObjectURL(imageFiles[0]);
        var imagePreviewElement = document.querySelector("#preview-selected-image2");
        imagePreviewElement.src = imageSrc;

        imagePreviewElement.style.display = "block";
    }
};
