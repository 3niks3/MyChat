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
