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
class CronController extends Controller
{
    //Start, This function is used to delete message from database which will run on serverside
    public function delete_message_cron(Request $request){
        $messages = Chat::where('is_del', '<=', Carbon::now())->delete();
        return true;
    }
     // End
    
}
