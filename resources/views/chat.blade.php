@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('assets/chat.css') }}" class="template-customizer-core-css" />
<input type="hidden" id="receiverid" value="{{$receiver['id']}}"/>
<div class="container">
    <div class="row clearfix">
        <div class="col-lg-8 m-auto">
            <div class="card chat-app">
                <div class="chat">
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-lg-6">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                </a>
                                <div class="chat-about">
                                    <h6 class="m-b-0">{{$receiver['name']}}</h6>
                                    <small>online</small>
                                </div>
                            </div>
                            <div class="col-lg-6 hidden-sm text-right" style="text-align: right;">
                                <a href="javascript:void(0);" class="btn btn-outline-info" data-toggle="modal" data-target="#myModal"><i class="fa fa-gear"></i></a>
                                
                            </div>
                        </div>
                    </div>
                    <div class="chat-history">
                        <ul class="m-b-0 chat-body">
                           
                        </ul>
                    </div>
                    <div class="chat-message">
                        <div class="row">
                            <div class="col-sm-12 mb-2">
                                    <p id="error-message" class="text-danger"></p>
                            </div>
                                <div class="col-sm-10">
                                    <textarea name="message" class="form-control" id="message"></textarea>
                                    
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary align-middle" style="padding:17px;" onclick="sendMessage()"><i class="fa fa-send"></i></button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
     <form action="{{route('delete-setting')}}" method="post">
        <input type="hidden" name="updateid" value="{{isset($setting['id']) ? $setting['id'] : ''}}"/>
        @csrf
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Message Delete setting</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <!-- Modal body -->
        <div class="modal-body">
               <div class="form-group">
                    <label for="email">Delete Type:</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delete_type" value="0" id="delete_type1" {{(isset($setting['delete_type']) && $setting['delete_type']==0) ? 'checked' :''}}>
                    <label class="form-check-label" for="delete_type1">
                       Immediate
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delete_type" value="1" id="delete_type2" {{(isset($setting['delete_type']) && $setting['delete_type']==1) ? 'checked' :''}}>
                    <label class="form-check-label" for="delete_type2">
                        Custom
                    </label>
                </div>
                <div class="form-group mt-2" id="cutom_field" style="display:{{(isset($setting['delete_type']) && $setting['delete_type']==1 ) ? 'block' : 'none'}};">
                    <label for="email">Enter Time (in minute):</label>
                    <input type="number" name="custom_time" class="form-control custom_input" value="{{isset($setting['custom_time']) ? $setting['custom_time'] : ''}}">
                </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
     </form>
    </div>
  </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script>
    // this is used to show and hide input field of  message delete time
    $(document).ready(function(){
        $('input[type="radio"]').change(function(){
            var inputValue = $(this).attr("value");
            if(inputValue == 1){
              $("#cutom_field").show();
              $(".custom_input").attr('required',true);
              
            }else{
              $("#cutom_field").hide();
              $(".custom_input").attr('required',false);
            }
        });
    });


    //  this function is used to send message
    function sendMessage(){
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var message=$("#message").val();  
        var receiverid=$("#receiverid").val(); 
        if(message == ""){
            $("#error-message").html("Please Enter Message")
        }else{ 
            $.ajax({
                url: "{{route('send-message')}}",
                type: "POST",
                data:{ 
                    message:message,
                    receiverid:receiverid
                },
                dataType: "json",
                success: function (response) {
                    if(response == 1){
                        $("#message").val('');
                         getAllMessage();
                    }else{
                        $("#error-message").html("something went wrong");
                    }
                }
            }); 
       }
    }

    // this is used to call function when page loaded
    $(document).ready(function(){
       getAllMessage();
    });
    
    // this function is used to Get ALL message
    function getAllMessage(){
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var receiverid=$("#receiverid").val();   
        $.ajax({
            url: "{{URL::to('get-all-message/')}}/"+receiverid,
            type: "get",
            dataType: "text",
            success: function (response) {
               $(".chat-body").html(response);
               setTimeout(deleteMessage, {{$timer_time}} );
            }
        }); 
    }

    //this is used to call function at specific time which we set for delete message time.
    setTimeout(deleteMessage, {{$timer_time}} );

    function deleteMessage(){
       getAllMessage();
    }


    


</script>