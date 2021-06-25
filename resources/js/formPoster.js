

class FormPoster {

    //constructor() {}

    standardFormErrorsClear(form)
    {
        form = (form instanceof Object)?form: $(form);

        form.find('input,select').removeClass('is-invalid');
        form.find('div.invalid-feedback').html('');
    }

    standardFormErrorShow(form, messages){

        form = (form instanceof Object)?form: $(form);
        messages = ( $.isArray(messages)||$.isPlainObject(messages) )?messages: {messages};

        $.each(messages, function( field, msg ){

            form.find('[name="'+field+'"]').addClass('is-invalid');
            msg = $.isArray(msg)?msg:{msg};

            $.each(msg, function( nr ,error ){
                form.find('div.invalid-feedback[data-input-field="#'+field+'"]').append('<p>'+error+'</p>');
            })
        });

        return true;
    }
    getFormDataAsJson(form)
    {
        form = (form instanceof Object)?form: $(form);

        var unindexed_array = form.serializeArray();
        var indexed_array = {};

        $.map(unindexed_array, function(data, i){
            indexed_array[data['name']] = data['value'];
        });

        return indexed_array;
    }

    getFormData(form)
    {
        form = (form instanceof Object)?form: $(form);
        return new FormData(form[0]);
    }

    setCropImageValues()
    {
        $('.image-upload').each(function (id, element) {

            let $mainImage = $(this).find('img#main-image');
            let $hiddenFile = $(this).find('input#hiddenFile');
            let fileType =  $mainImage.attr('data-file-type')||'';

            if( $mainImage.cropper('getCroppedCanvas') != null)
            {
                let imageURL = $mainImage.cropper('getCroppedCanvas').toDataURL(fileType);
                $hiddenFile.val(imageURL);
            }
        });
    }

}

window.formPoster =  new FormPoster;;


