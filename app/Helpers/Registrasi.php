<?php
namespace App\Helpers;

use App\Http\Libraries\Requestor;

class Registrasi{
    public static function sendMessage($params) {
        $text = "*Selamat !* Pendaftaran Anda berhasil!\n\n";
        $text = "*No. Reg       : $params->no_permohonan*\n";
        $text = "*Nama Pemohon       : $params->nama_pemohon*\n";
        $text = "*Kuasa       : $params->nama_kuasa*\n";
        $text = "*Jenis Hak       : $params->jenis_hak*\n";
        $text = "*No. Sertipikat     : $params->no_sertifikat*\n";
        $text = "*Desa       : " . $params->desa->nama ."*\n";
        $text = "*Kecamatan       :  " . $params->kecamatan->nama . "*\n";
        $text = "*Status       : $params->status_permohonan*\n\n";
        $text = "Simpan nomor registrasi Anda. Anda dapat melakukan pengecekan berkala untuk melihat status permohonan validasi anda.\n";

        $curl = curl_init();
        $token = "GZBy1FssJ0x0QQWIA5JnOGCskFQcrUm57FhKoBjAllgHqYyfrut25BA";
        $secret_key = "pvB1Kr9i";
        $random = true;
        $payload = [
            "data" => [
                [
                    'phone' => $params->telepon_pemohon,
                    'message' => $text,
                ]
            ]
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token.$secret_key",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        curl_setopt($curl, CURLOPT_URL,  "https://texas.wablas.com/api/v2/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r($result);
    }
}
