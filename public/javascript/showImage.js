const previewImage = (event, imageId) => {
    const imageFiles = event.target.files;
    const imageFilesLength = imageFiles.length;
    if (imageFilesLength > 0) {
        const imageSrc = URL.createObjectURL(imageFiles[0]);
        var imagePreviewElement = document.querySelector(`#preview-selected-image${imageId}`);
        imagePreviewElement.src = imageSrc;

        imagePreviewElement.style.display = "block";
    }
};
