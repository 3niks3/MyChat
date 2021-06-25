class ImageUpload
{
   setCropImageValues()
   {
       $('.image-upload').each(function (id, element) {
           console.log('llop and set data');
           let $mainImage = $(this).find('img#main-image');
           let $hiddenFile = $(this).find('input#hiddenFile');

           let imageURL = $mainImage.cropper('getCroppedCanvas').toDataURL();
           $hiddenFile.val(imageURL);
       });
   }


}

window.imageUpload = new ImageUpload();



