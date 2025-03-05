<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Http};
use League\Csv\Writer;

class ExcelController extends Controller
{
    //funcion para generar archivos csv
    public function generateExcel2()
    {   
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        
        $db = ['budxpzx4g', 'budxp2nvi', 'budxp862p', 'budxt88sw', 'budxyyjmm', 'budxy5i8y'];
        $query = '';
        $clist = [];

        try {
            $urls_download = [];
            foreach($db as $db_item){
                $data = $this->getQuickbase($db_item, $query, $clist);
                $data_excel = [];

                if(count($data->data)){

                    $headers = array_column(json_decode(json_encode($data->fields), true), 'label', 'id');
                    
                    // $headers = []; //cabecera columnas
                    // $headers = array_map(function ($field) {
                    //     return $field->label ?? $field->id;
                    // }, $data->fields);

                    $body = [];                    
                    // Convertir datos en un formato plano
                    foreach (array_chunk($data->data, 500) as $chunk) {
                        $body = array_map(function ($item) {
                            return array_map(function ($field) {
                                if ($field instanceof stdClass) {
                                    $field = (array) $field;
                                }

                                return $field->value;
                            }, (array) $item);
                        }, $data->data);
                    }
                                        
                    $csv = Writer::createFromString('');
                    $csv->insertOne($headers);
                    $csv->insertAll($body);
                    
                    $filename = 'Excel/'.$db_item.'.csv';
                    Storage::put($filename, $csv->getContent());
                    $urls_download[] = route('download.csv', ['filename' => $db_item]);
                    \Log::info('Excel '.$db_item.' generado. -');
                }
                break;
            }       
            
            return response()->json([
                'status' => 'ok',
                'urls_download' => $urls_download ?? '*',
            ]);

        } catch (\Exception $e) {
            \Log::error('Excepción al generar el CSV: ' . $e->getMessage());
            return response()->json([
                'status' => 'error 204',
            ]);
        }

    }

    public function generateExcel(){
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        
        try {
            $url = "https://tciconsultoria.com/zelestra/dropletOramennrra.php";
            $db = ['budxpzx4g', 'budxp2nvi', 'budxp862p', 'budxt88sw', 'budxyyjmm', 'budxy5i8y'];

            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = json_decode($response->body(), true);

                $con = 0;
                foreach($data['header'] as $db_item){
                    $csv = Writer::createFromString('');
                    $csv->insertOne($db_item);
                    $csv->insertAll($data['body'][$con]);
                    
                    $filename = 'Excel/'.$db[$con].'.csv';
                    Storage::put($filename, $csv->getContent());
                    $urls_download[] = route('download.csv', ['filename' => $db[$con]]);
                    $con++;
                }
                \Log::info('Excel generado. -');
                
                return response()->json([
                    'status' => 'ok',
                    'urls_download' => $urls_download ?? [],
                ]);
            }
        }catch (\Exception $e) {
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