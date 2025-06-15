<?php

namespace App\Models\Webhook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBotReport extends Model
{
    use HasFactory;
    protected $table = 'chat_bot_report';
	public $timestamps = false;

    public static function filter($params){
		return ChatBotReport::where('phone',$params->phone)->first();
	}
	public static function store($params){
		$save = new ChatBotReport;
		$save->phone=$params->phone;
		$save->save();
		return $save?$save:false;
	}
}
