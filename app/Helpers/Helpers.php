<?php
namespace App\Helpers;

use App\Http\Libraries\Requestor;

class Helpers{
    public static function sendErrorSystemToAdmin($params = []){ # Prepare message for SYSTEM ERROR
		date_default_timezone_set('Asia/Jakarta');
		try{
			$title = isset($params['title']) ? strtoupper($params['title']) : 'SYSTEM ERROR';
			$text = "*$title*";
			$text .= "\n*DATE :* _".date('d-m-Y')."_";
			$text .= "\n*TIME :* _".date('H:i:s')."_";

			$message = isset($params['message']) ? $params['message'] : 'Terjadi kesalahan sistem';
			$arrKeys = ['url','file','line','message','data'];
			foreach($arrKeys as $key => $val){
				if(isset($params[$val])){
					$upper = strtoupper($val);
					if($val=='data'){
						$text .= "\n*$upper :* ".json_encode($params[$val],JSON_PRETTY_PRINT);
					}else if($val=='message'){
						$text .= "\n*$upper :* _".$message."_";
					}else{
						$text .= "\n*$upper :* _".$params[$val]."_";
					}
				}
			}
			return self::messageSenderError(['message' => $text]);
		}catch(\Throwable $e){
			$arrLog = [
				'title'   => 'FAILED PREPARE MESSAGE FOR SYSTEM ERROR',
				'url'     => request()->url(),
				'file'    => $e->getFile(),
				'line'    => $e->getLine(),
				'message' => $e->getMessage(),
			];
			Helpers::logging($arrLog); # Log info
		}
	}

    public static function messageSenderError($params = []){
		$phone = config('webhook.phone'); # Nomor admin get from ENV
		$text = isset($params['message']) ? $params['message'] : "_Terjadi kesalahan sistem_";
		$prepareMessage = [[ # Array 2 dimensi
			'phone' => $phone,
			'message' => $text,
			// 'isGroup' => true, # Matikan baris ini jika tidak dipakai {untuk report ke group}
		]];
		if(count($nomorAdmin = ChatBotReport::limit(5)->get()) > 0){ # Jika $nomorAdmin ada, replace value $prepareMessage
			$prepareMessage = []; # Set value jadi array kosong
			foreach($nomorAdmin as $key => $val){
				$forPush = [
					'phone' => $val->phone,
					'message' => $text,
				];
				array_push($prepareMessage,$forPush);
			}
		}
		$payload = [
			'payload' => ["data" => $prepareMessage],
			'token' => config('webhook.key'),
			'url' => config('webhook.send.type.message'),
		];
		return Requestor::sendMultipleChat($payload); # Send message to admin
	}

    # Logging start
	public static function logging($param=[]){ # Parameter using key-value
		$keyForLog = ['status','url','file','title','message','line','data']; # Declar key param log, tambahkan value di baris ini jika ingin menambah parameter untuk log
		$arr = [];
		# Modify params for logging start
		foreach($keyForLog as $key => $val){
			$arr[$val] = isset($param[$val]) ? $param[$val] : ( # Cek key, apakah sudah di set
				$val=='status' ? false : ( # Jika key "status" belum di-set, isi value menjadi "false" :bool
					$val=='title' ? 'NO TITLE' : (
						$val=='message' ? 'NO MESSAGES' : '-'
					)
				)
			);
		}
		# Modify params for logging end
    }
}
