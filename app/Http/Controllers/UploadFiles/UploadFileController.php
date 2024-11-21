<?php

namespace App\Http\Controllers\UploadFiles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    //Vista principal drag and drop
    public function dragAndDrop(Request $request){
        $jsonData = $request->query('data');
        $data = json_decode($jsonData);
    
        $response = $this->getRegistroQuickBase($data->record_id->{'0'});
       
        if($response !== true){ //$response == false (tarea no completada) 
            return view('upload_files.drag_and_drop', ['data' => $data, 'url' => route('taskComplete', $data->record_id->{'0'})]);
        }

        return view('upload_files.task_complete', ['title' => 'Expired Link',
                    'text' => 'The link you tried to access is no longer available because the task has been completed.',
                    'status' => 'expired']);
    }

    //funcion para guardar archivos
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
