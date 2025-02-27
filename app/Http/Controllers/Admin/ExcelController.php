<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class ExcelController extends Controller
{
    //funcion para generar archivos csv
    public function generateExcel()
    {   
        ini_set('memory_limit', '512M');
        $db = ['budxpzx4g', 'budxp2nvi', 'budxp862p', 'budxt88sw', 'budxyyjmm', 'budxy5i8y'];
        $query = '';
        $clist = [];

        try {
            $urls_download = [];
            foreach($db as $db_item){
                $data = $this->getQuickbase($db_item, $query, $clist);
                $data_excel = [];
                
                if(count($data->data)){
                    
                    $headers = []; //cabecera columnas
                    $body = []; 
                    $con = 0; //contador para llenar la cabecera solo una vez
                    foreach($data as $index => $item){
                        $json = json_encode($item);
                        $data_decode = json_decode($json, true); //convertimos a array

                        $x = 0; 
                        foreach($data_decode as $header => $decode){
                            if($con == 0){
                                $headers[] = $header;
                                $x == (count($data_decode)-1) ? $con++:'';
                            }

                            $body[$index][] = $decode;
                            $x++;
                        }
                    }
                    
                    $csv = Writer::createFromString('');
                    $csv->insertOne($headers);
                    $csv->insertAll($body);
                    
                    $filename = 'Excel/'.$db_item.'.csv';
                    Storage::put($filename, $csv->getContent());
                    $urls_download[] = route('download.csv', ['filename' => $db_item]);
                    \Log::info('Excel '.$db_item.' generado. -');
                }
            }       
            
            return response()->json([
                'status' => 'ok',
                'urls_download' => $urls_download,
            ]);

        } catch (\Exception $e) {
            \Log::error('Excepción al generar el CSV: ' . $e->getMessage());
            return response()->json([
                'status' => 'error 204',
            ]);
        }

    }

    //funcion para descargar csv generados
    public function downloadCsv($filename){
        ini_set('memory_limit', '512M');
        
        $filePath = 'Excel/'.$filename.'.csv';

        if (!Storage::exists($filePath)) {
            abort(404, 'El archivo no existe.');
        }

        return Storage::download($filePath);
    }
}