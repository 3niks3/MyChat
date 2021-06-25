

class FormPoster {

    //constructor() {}

    standardFormErrorsClear(form)
    {
        form = (form instanceof Object)?form: $(form);

        form.find('input,select').removeClass('is-invalid');
        form.find('div.invalid-feedback').html('');
    }

    standardFormErrorShow(form, messages){
        console.log(form);
        return false

        form = (form instanceof Object)?form: $(form);
        messages = ( $.isArray(messages)||$.isPlainObject(messages) )?messages: {messages};

        $.each(messages, function( field, msg ){

            console.log('test');
            return false;
            form.find('[name="'+field+'"]').addClass('is-invalid');
            msg = $.isArray(msg)?msg:{msg};

            $.each(msg, function( nr ,error ){
                form.find('div.invalid-feedback[data-input-field="#'+field+'"]').append('<p>'+error+'</p>');
            })
        });

        return true;
    }

}

let formPoster = new FormPoster;

window.formPoster = formPoster;


