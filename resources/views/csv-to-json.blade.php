<!DOCTYPE html>
<html>
    <head>
        <title>CSV to JSON Converter</title>
    </head>
    <body>
        <h1>Upload CSV File</h1>
        <form action="{{ url('/csv-to-json') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file" required>
            <button type="submit">Convert to JSON</button>
        </form>
    </body>
</html>
