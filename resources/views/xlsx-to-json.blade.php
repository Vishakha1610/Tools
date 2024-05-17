<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>XLSX to JSON Converter</title>
    </head>
    <body>
        <h1>Upload Excel File</h1>
        <form method="POST" action="{{route('xlsx-to-json')}}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file">
            <button type="submit">Convert</button>
        </form>
    </body>
</html>
