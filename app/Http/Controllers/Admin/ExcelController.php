<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Http};
use League\Csv\Writer;

class ExcelController extends Controller
{   

    public function generateExcel()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        try {
            $urlBase = "https://tciconsultoria.com/zelestra/dropletOramennrra_new.php";
            // $urlBase = "https://tciconsultoria.com/zelestra/dropletOramennrra.php";
            $dbList = ['budxpzx4g','budxp862p','budxq7yuj','budxt88sw','budxyyjmm','budxy5i8y','budxzg6cz','budx7h949','bud2t7c8m','buqx9twu3','buq8cu39j','buv2xwba2'];
            
            $urls_download = [];

            foreach ($dbList as $table) {
                $response = Http::timeout(60)->get($urlBase, ['table' => $table]);

                if ($response->successful()) {
                    $data = json_decode($response->body(), true);
                    
                    if (!empty($data['header'][0]) && !empty($data['body'][0])) {
                        $csv = Writer::createFromString('');
                        $csv->insertOne($data['header'][0]); // Solo hay un header por respuesta
                        $csv->insertAll($data['body'][0]);   // Solo un bloque de body

                        $filename = 'Excel/' . $table . '.csv';
                        Storage::put($filename, $csv->getContent());

                        $urls_download[] = route('download.csv', ['filename' => $table]);
                        \Log::info('Excel generado exitosamente. -'.$table);
                    }
                } else {
                    \Log::warning("Fallo la petición de la tabla: $table");
                }

                // Puedes agregar un pequeño delay para no saturar Quickbase
                usleep(500000); // 0.5 segundos
            }

            return response()->json([
                'status' => 'ok',
                'urls_download' => $urls_download,
            ]);
        } catch (\Exception $e) {
            \Log::error('Excepción al generar los CSVs: ' . $e->getMessage());
            return response()->json([
                'status' => 'error 204',
            ]);
        }
    }

    public function generateExcel_original(){
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
        
        try {
            $url = "https://tciconsultoria.com/zelestra/dropletOramennrra.php";
            $db = ['budxpzx4g','budxp862p','budxq7yuj','budxt88sw','budxyyjmm','budxy5i8y','budxzg6cz','budx7h949','bud2t7c8m','buqx9twu3','buq8cu39j','buv2xwba2'];

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