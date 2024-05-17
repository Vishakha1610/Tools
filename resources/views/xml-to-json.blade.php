<!DOCTYPE html>
<html>
    <head>
        <title>XML to JSON Converter</title>
    </head>
    <body>
        <h1>Upload XML File</h1>
        <form method="POST" action="{{ route('xmlToJson') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="xml_file">
            <button type="submit">Upload</button>
        </form>
    </body>
</html>
