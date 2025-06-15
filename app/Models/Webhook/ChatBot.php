<?php

namespace App\Models\Webhook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBot extends Model
{
    use HasFactory;

    protected $table = 'chat_bot';
	protected $primaryKey = 'id_chat_bot';
    protected $fillable = ['id_chat_bot','status_chat','tanggal_chat','jam_chat','status_akun','status_chat_sebelumnya','created_at','updated_at'];

    public static function filter($params){
      $status = isset($params->status)?$params->status:'notset';
      $query = ChatBot::where('id_chat_bot',$params->phone);
      $query->when($status!='notset',fn($q)=>$q->where('status_akun',$status)); # If $status is set, execute this line{this query}
      $chatBot = $query->first();
      return $chatBot;
   }

   public static function store($params){
      $cek = ChatBot::filter($params);
      $save = !$cek ? new ChatBot : $cek;
      !$cek ? ($save->id_chat_bot=$params->phone) : '';
      $params->statusChat ? ($save->status_chat=$params->statusChat) : '';
      $save->tanggal_chat = date('Y-m-d');
      $save->jam_chat = date('H:i:s');
      $save->status_akun = isset($params->statusAkun)?$params->statusAkun:true;
      $save->save();
      return $save?$save:false;
   }
}
