<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CSV to XLSX Converter</title>
    </head>
    <body>
    <h1>Upload CSV File</h1>
    <form action="{{ route('csvToXlsx') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Choose CSV File:</label>
        <input type="file" name="file" id="file" accept=".csv">
        <button type="submit">Convert to XLSX</button>
    </form>

    </body>
</html>
