<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteMessage extends Model
{
    protected $table = 'chat_delete_setting';

	protected $fillable = ['user_id'];

}
