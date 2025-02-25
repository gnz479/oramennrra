<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    //funcion para asignar valor a quickbase
    function setQuickBase($record_id, $db){
        $url = "https://aortizdemontellanoarevalo.quickbase.com/db/".$db; //url a donde se consulta
        $userToken = "b8degy_fwjc_0_dkr9dvzbeqfv43cj89itthgskgd";

        $bodyQuery = "<qdbapi>
            <usertoken>$userToken</usertoken>
            <rid>$record_id</rid>
            <field fid='12'>true</field>
            </qdbapi>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$bodyQuery);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml',
            'Content-Length:',
            'QUICKBASE-ACTION: API_EditRecord'
        ));
        
        $response = curl_exec($ch);
        curl_close ($ch);
    }  

    //funcion para consultar informacion de un registro de quickbase
    function getRegistroQuickBase($record_id){
        $db = 'budxyyjmm';
        $url = "https://aortizdemontellanoarevalo.quickbase.com/db/".$db; //url a donde se consulta
        $userToken = "b8degy_fwjc_0_dkr9dvzbeqfv43cj89itthgskgd";

        $bodyQuery = "<qdbapi>
            <usertoken>$userToken</usertoken>
            <rid>$record_id</rid>
            </qdbapi>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$bodyQuery);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml',
            'Content-Length:',
            'QUICKBASE-ACTION: API_GetRecordInfo'
        ));
        
        $response = curl_exec($ch);
        curl_close ($ch);
        $record = simplexml_load_string($response);
        
        foreach($record->field as $value){
            if($value->fid == 12 && $value->value == 1){
                return true;
            }
        }

        return  false;
    }

    //funcion para obtener data de quickbase
    function getQuickBase($db, $query, $clist){
        $subdomain = 'aortizdemontellanoarevalo';
        $userToken = 'b8degy_fwjc_0_dkr9dvzbeqfv43cj89itthgskgd';
        $sortOrder = [["fieldId" => 3,"order" => "ASC"],];

        $url = "https://api.quickbase.com/v1/records/query";
     
        $headers = [
            "QB-Realm-Hostname: https://$subdomain.quickbase.com",
            "User-Agent: {User-Agent}",
            "Authorization: QB-USER-TOKEN $userToken",
            "Content-Type: application/json"
        ];
     
        $data = [
            "from" => $db,
            "select" => $clist,
            "where" => $query,
            "sortBy" => $sortOrder,
            "options" => [
                "skip" => 0,
                "top" => 0,
                "compareWithAppLocalTime" => false
            ]
        ];
     
        $ch = curl_init($url);
     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);          
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
     
        $response = curl_exec($ch);
     
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            return json_decode($response);
        }
        curl_close($ch);
    }
}
