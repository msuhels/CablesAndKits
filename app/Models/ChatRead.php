<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRead extends Model
{
    protected $table = 'chat_read';

	protected $fillable = ['chat_with','user_id','last_read'];

}
