<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Chat;
use App\Models\ChatRead;
use App\Models\DeleteMessage;
Use Carbon\Carbon;
class ChatController extends Controller
{
    //Start, This function is used to check authentication
    public function __construct()
    {
        $this->middleware('auth');
    }
    //End
    
     // Start,  This function is used to set message delete time
    public function delete_setting(Request $request){
        $settingData=[
            'user_id'=>Auth::user()->id,
            'delete_type'=>$request->delete_type,
            'custom_time'=>$request->custom_time,
            'created_at'=>date("Y-m-d h:i:s"),
            'updated_at'=>date("Y-m-d h:i:s"),
        ];
        $Exits = DeleteMessage::count();
        if($Exits > 0){
            $res=DeleteMessage::where('id',$request->updateid)->update($settingData);
        }else{ 
            $res = DeleteMessage::insert($settingData);
        }

       if($res){
            return redirect()->back()->with('success', 'Message Setting added  Successfully !');
       }else{
            return redirect()->back()->with('error', 'Something went wrong  !');
       }
    }
    // End
    
    // Start,  This function is used to load Chat window
    public function index(){
        $receiver = User::where('id','!=', Auth::user()->id)->first();
        $setting = DeleteMessage::first();
        $timer_time=30000; //second
        if(!empty($setting)){
            if($setting->delete_type == 1){
                $timer_time=1000*$setting->custom_time; 
            }
        }
        return view('chat', compact('receiver','setting','timer_time'));
    }
    // End

    // Start,  This function is used to store message into database
    public function send_message(Request $request){
        $encryptedMessage = Crypt::encrypt($request->message);
        $chatData=[
            'sender'=>Auth::user()->id,
            'receiver'=>$request->receiverid,
            'message'=>$encryptedMessage,
            'message_time'=>date("Y-m-d h:i:s"),
            'created_at'=>date("Y-m-d h:i:s"),
            'updated_at'=>date("Y-m-d h:i:s"),
        ];
       $res = Chat::insert($chatData);
       if($res){
            return true; 
       }else{
           return false; 
       }
    }
    // End

   // Start,  This function is used to get all  message from database
    public function getallmessages(Request $request){
        $receiverid=$request->id;
        $this->last_seen_chat($receiverid);
        $this->addMessagedeletetime($receiverid);
        $messages = Chat::where(function($query) use ($receiverid) {
            if($receiverid !=""){
                 $query->where([['sender',Auth::user()->id],['receiver',$receiverid],['is_del', '>', Carbon::now()]]);
                 $query->orwhere([['receiver',Auth::user()->id],['sender',$receiverid],['is_del', '>', Carbon::now()]]);
            }
        })->orderBy('id', 'ASC')->get();

        foreach ($messages as $key => $value) {
           $messages[$key]->message = Crypt::decrypt($value->message);
        }
        
        return view('message-line', compact('messages'));
    }
     // End

    // Start,  This function is used to store last message seen time into database
    public function last_seen_chat($chatwith){
        $user_id=Auth::user()->id;
        $data=[
            'last_read'=>Carbon::now(),
        ];
        $userExits = ChatRead::where('user_id',$user_id)
        ->where('chat_with',$chatwith)
        ->count();
        if($userExits > 0){
            ChatRead::where('user_id',$user_id)
            ->where('chat_with',$chatwith)
            ->update($data);

        }else{
            $data['user_id']=$user_id;
            $data['chat_with']=$chatwith;
            ChatRead::insert($data);
        }
    }
     // End


    // Start,  This function is used to store delete time of seen messages into database
    public function addMessagedeletetime($chatwith){
        $user_id=Auth::user()->id;
        $lastseendata = ChatRead::where('user_id',$user_id)
        ->where('chat_with',$chatwith)
        ->get();
        $setting = DeleteMessage::first();

        foreach ($lastseendata as $key => $lastseen) {
               $last_seen_datetime=$lastseen->last_read;
                if(!empty($setting)){
                if($setting->delete_type == 0){
                        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $last_seen_datetime);
                        $newDateTime = $datetime->addSeconds(30);
                    }

                    if($setting->delete_type == 1){
                        $custom_time=($setting->custom_time != "") ? $setting->custom_time : 1 ;
                        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $last_seen_datetime);
                        $newDateTime = $datetime->addMinutes($custom_time);
                    }
                }else{
                    $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $last_seen_datetime);
                    $newDateTime = $datetime->addSeconds(30);
                }

                Chat::where('sender',Auth::user()->id)
                      ->where('receiver',$chatwith)
                      ->whereNull('is_del')
                      ->update(['is_del'=>$newDateTime]);
        }

    }
     // End
    
}
