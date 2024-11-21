<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }

        h1 {
            font-size: 24px;
            color: #d9534f;
            margin-bottom: 10px;
        }

        .success {
            color: #5876af !important;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }

        .icon {
            font-size: 50px;
            color: #d9534f;
            margin-bottom: 20px;
        }

        .icon_success {
            font-size: 50px;
            color: #5876af;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($status == 'success')
        <div class="icon_success"> <i class="fa-solid fa-check"></i></div>
        <h1 class="success">{{$title}}</h1>
        @else
        <div class="icon"> <i class="fa-regular fa-clock"> </i></div>
        <h1>{{$title}}</h1>
        @endif
        <p>{{$text}}</p>
    </div>
</body>
</html>
