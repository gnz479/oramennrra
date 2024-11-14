<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function store(){
        $user = new User();
        $user->name = 'prueba';
        $user->email = 'prueba@tci.com';
        $user->password = bcrypt('test1234'); // Encripta la contraseña
        $user->save();

        \Log::info('Usuario guardado correctamente');
    }

    //funcion para obtener todas las tareas que no esten finalizadas
    function getQuickBase(){
        $db = 'budxyyjmm';
        $query = '{12.EX.false} AND {8.XCT.""}';
        $clist = '8.16.20.19';

        $url = "https://aortizdemontellanoarevalo.quickbase.com/db/".$db; //url a donde se consulta
        $userToken = "b8degy_fwjc_0_dkr9dvzbeqfv43cj89itthgskgd";

        $bodyQuery = "<qdbapi> 
            <usertoken>".$userToken."</usertoken>
            <query>".$query."</query>
            <clist>".$clist."</clist>
        </qdbapi>"; //consulta para obtener los productos

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$bodyQuery);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/xml',
            'Content-Length:',
            'QUICKBASE-ACTION: API_DoQuery'
        ));
        
        $response = curl_exec($ch);
        curl_close ($ch);
        $record = simplexml_load_string($response);
        $values = array();
        foreach($record->record as $value){
            $values[] = $value;
        }
        return  $values;
    }
}
