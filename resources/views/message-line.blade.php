@foreach($messages as $key => $messagesdata)
    @if($messagesdata->sender == \Auth::user()->id)
    <li class="clearfix">
        <div class="message-data text-right">
            <span class="message-data-time">{{$messagesdata->message_time}}</span>
            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
        </div>
        <div class="message other-message float-right">{{$messagesdata->message}}</div>
    </li>
    @else
    <li class="clearfix">
        <div class="message-data">
            <span class="message-data-time">{{$messagesdata->message_time}}</span>
        </div>
        <div class="message my-message">{{$messagesdata->message}}</div>                                    
    </li>
    @endif
   
@endforeach