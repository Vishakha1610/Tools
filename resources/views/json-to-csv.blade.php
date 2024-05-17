<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>JSON to CSV Converter</title>
    </head>
    <body>
        <h1>Upload JSON File</h1>
        <form action="{{ route('json-to-csv') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".json">
            <button type="submit">Upload and Convert</button>
        </form>
    </body>
</html>
