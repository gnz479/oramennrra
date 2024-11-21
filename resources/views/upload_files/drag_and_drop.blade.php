<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop File Upload</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .drag-area {
            width: 100%;
            max-width: 500px;
            height: 200px;
            border: 2px dashed #8e8e8e;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #8e8e8e;
            text-align: center;
            cursor: pointer;
        }

        .drag-area.dragover {
            background-color: #f4f4f4;
        }

        #file-input {
            display: none;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #6c63ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #5751d9;
        }
    </style>
</head>
<body>
    <h1>Upload File - Drag and Drop</h1>
    <div class="drag-area" id="drag-area">
        <p>Drag and drop a file here or click to select it.</p>
        <input type="file" id="file-input">
    </div>
    <button id="upload-button">Upload File</button>

    <script>
        const dragArea = document.getElementById('drag-area');
        const fileInput = document.getElementById('file-input');
        const uploadButton = document.getElementById('upload-button');
        let selectedFile = null;

        // Evitar el comportamiento predeterminado al arrastrar/soltar
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dragArea.addEventListener(eventName, (e) => e.preventDefault());
        });

        // Cambiar estilos al arrastrar sobre el área
        ['dragenter', 'dragover'].forEach(eventName => {
            dragArea.addEventListener(eventName, () => dragArea.classList.add('dragover'));
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dragArea.addEventListener(eventName, () => dragArea.classList.remove('dragover'));
        });

        // Manejar el archivo arrastrado
        dragArea.addEventListener('drop', (e) => {
            selectedFile = e.dataTransfer.files[0];
            updateDragAreaText(selectedFile.name);
        });

        // Selección de archivo manual
        dragArea.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', (e) => {
            selectedFile = e.target.files[0];
            updateDragAreaText(selectedFile.name);
        });

        // Subir el archivo
        uploadButton.addEventListener('click', () => {
            if (!selectedFile) {
                swal.fire('info', '', 'Please select a file first.')
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                const fileContent = event.target.result;
                let directory = @json($data)['details']['directory'];
                
                // Crear un objeto JSON con el contenido del archivo
                const payload = {
                    filename: selectedFile.name,
                    directory: directory,
                    archive: fileContent.split(',')[1] // Solo la parte base64
                };

                // Enviar el JSON al servidor
                fetch('https://tciconsultoria.com/zelestra/upload_archive.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    // alert('File uploaded successfully: ' + JSON.stringify(data));

                    if(JSON.stringify(data['status']) === '"success"'){
                        setDataQB();
                    }else{
                        console.log('response', JSON.stringify(data));
                        swal.fire('error', '', 'There was an error uploading the file.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    swal.fire('error', '', 'There was an error uploading the file.');
                });
            };

            // Leer el archivo como base64
            reader.readAsDataURL(selectedFile);
        });

        function updateDragAreaText(fileName) {
            dragArea.innerHTML = `<p>Selected file: ${fileName}</p>`;
        }

        //funcion fetch para mandar la data a quickbase
        function setDataQB(){
            let data_ = @json($data);
            
            let jsonToSend = {
                'activityid': data_['details']['activity_id'],
                'filename': selectedFile.name,
                'directory': data_['details']['directory'],
                'description': data_['details']['description'],
                'project': data_['details']['project'],
                'user': data_['destinatario'][0],
                'taskId': data_['details']['taskId'],
                'fromDroplet': 'true',
            };

            fetch('https://tciconsultoria.com/zelestra/upload_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonToSend)
            }).then(response => response.json())
            .then(data => {
                // console.log('ok', JSON.stringify(data), 'json', jsonToSend);
                let url_ = @json($url); //url para finalizar la tarea y muestra vista de finalizado
                window.location.replace(url_);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error uploading the file.');
            });
        }
    </script>
</body>
</html>
