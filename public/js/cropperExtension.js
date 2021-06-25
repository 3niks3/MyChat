class ImageUpload
{
    initImagePreview()
    {
        console.log('initImagePreview');
    }
}
let imageUploader = new ImageUpload;
window.ImageUpload = imageUploader;



window.Cropper.prototype.showPreviewContainer = function (preview){
    $(preview).parent('div').removeClass('d-none');
}
