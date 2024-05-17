<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>XLSX to CSV Converter</title>
    </head>
    <body>
        <h1>Upload XLSX File</h1>
        <form action="{{ route('xlsxToCsv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file">
            <button type="submit">Upload</button>
        </form>
    </body>
</html>
