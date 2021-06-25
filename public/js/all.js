

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



class ChatManager
{
    constructor() {
        this.testVar = 'test var';
    }

    test()
    {
        console.log('ChatManager works');
    }

    freshActionScrollBottom(callback, force = false){
        let scroll_bottom = false;
        if ( ($('#chat-container')[0].scrollHeight - $('#chat-container')[0].scrollTop <= $('#chat-container')[0].clientHeight) || force)
        {
            scroll_bottom = true;
        }

        if (typeof callback === 'function') {
            callback();
        }

        if(scroll_bottom){
            $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);
        }
    }

    chatMemberOnline(user_id, username, action)
    {
        //search for user in list
        let element = $('ul#online-users li > i.online-icon#chat-member-'+user_id)

        //preform actions
        switch(true){
            case(action == 'join'):

                if(element.length <= 0 )
                {
                    element = this.chatAddNewMemberToList(user_id, username);
                }

                element.removeClass('text-secondary')
                .addClass('text-success')
                .parent('li')
                .removeClass('order-2')
                .addClass('order-1');
                break;
            case(action == 'leave'):
                console.log('action leave');
                element.removeClass('text-success')
                    .addClass('text-secondary')
                    .parent('li')
                    .removeClass('order-1')
                    .addClass('order-2');
                break;
            case(action == 'left_group'):
                console.log('action left_group');
                element.parent('li').remove();
                break;
        }

    }

    chatAddNewMemberToList(user_id, username)
    {
        let element =   $('<li class="list-group-item order-2" >\n' +
            '     <i class="fas fa-circle text-secondary online-icon" id="chat-member-'+user_id+'"></i>\n' +
            '         '+username+'\n' +
            '</li>');
        $('ul#online-users').append(element);
        return $('ul#online-users li > i.online-icon#chat-member-'+user_id);
    }


}

window.chatManager = new ChatManager;
