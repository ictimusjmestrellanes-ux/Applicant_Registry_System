<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $fileName ?? 'Document' }}</title>
    <style>
        html, body {
            margin: 0;
            width: 100%;
            height: 100%;
        }

        iframe {
            display: block;
            width: 100vw;
            height: 100vh;
            border: 0;
        }
    </style>
</head>
<body>
    <iframe src="{{ $fileUrl }}" frameborder="0"></iframe>
</body>
</html>
