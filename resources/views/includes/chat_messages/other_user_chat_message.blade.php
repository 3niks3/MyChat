<div class="card me-5 m-1 w-75 mt-auto">
    <div class="card-header" style="background-color: #eeeeee">
        @if(!$message->user->isMemberChatRoom($message->chat_group_id))
            <span><em>(ex.member)</em></span>
        @endif

        <span class="fw-bold">{{$message->user->username??''}}</span>
        <span class="fw-bold float-end">{{$message->format_create_date??''}}</span>
    </div>
    <div class="card-body" style="background-color: #eeeeee">
        <p class="text-break">{{trim($message->content)}}</p>
    </div>
</div>
