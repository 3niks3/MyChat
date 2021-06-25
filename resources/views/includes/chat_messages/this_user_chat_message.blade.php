<div class="card m-3 w-75 align-self-end mt-auto">
    <div class="card-header" style="background-color: #d4ebf2">
        <span class="fw-bold">You</span>
        <span class="fw-bold float-end">{{$message->format_create_date??''}}</span>
    </div>
    <div class="card-body" style="background-color: #d4ebf2">
        <p class="text-break">{{trim($message->content)}}</p>
    </div>
</div>
