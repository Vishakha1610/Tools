<?php

use App\Http\Controllers\ConverterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//convert CSV to JSON
Route::get('/csv-to-json-upload', [ConverterController::class, 'showCsvToJson'])->name('csvToJsonUpload');
Route::post('/csv-to-json', [ConverterController::class, 'csvToJson']);

//convert XML to JSON
Route::get('/upload', [ConverterController::class, 'showXmlToJson'])->name('xmlToJsonUpload');
Route::post('/xml-to-json', [ConverterController::class, 'xmlToJson'])->name('xmlToJson');

//convert XLSX to JSON
Route::get('xlsx-to-json-upload', [ConverterController::class, 'showXlsxToJson'])->name('xlsxToJsonUpload');
Route::post('xlsx-to-json', [ConverterController::class, 'xlsxToJson'])->name('xlsx-to-json');

//convert JSON to CSV
Route::get('json-to-csv-upload',[ConverterController::class, 'showJsonToCsv'])->name('jsonToCsvUpload');
Route::post('json-to-csv', [ConverterController::class, 'jsonToCsv'])->name('json-to-csv');

//convert XLSX to CSV
Route::get('/xlsx-to-csv-upload', [ConverterController::class, 'showXlsxToCsv'])->name('xlsxToCsvUpload');
Route::post('/xlsx-to-csv', [ConverterController::class, 'xlsxToCsv'])->name('xlsxToCsv');

//convert JSON to XML
Route::get('/json-to-xml-upload', [ConverterController::class, 'showJsonToXml'])->name('jsonToXmlUpload');
Route::post('/json-to-xml', [ConverterController::class, 'jsonToXml'])->name('jsonToXml');

//convert JSON to XLSX
Route::get('json-to-xlsx-upload', [ConverterController::class, 'showJsonToXlsx'])->name('jsonToXlsxUpload');
Route::post('json-to-xlsx', [ConverterController::class, 'jsonToXlsx'])->name('jsonToXlsx');

//convert CSV to XLSX
Route::get('csv-to-xlsx-upload', [ConverterController::class, 'showCsvToXlsx'])->name('csvToXlsxUpload');
Route::post('csv-to-xlsx', [ConverterController::class, 'csvToXlsx'])->name('csvToXlsx');
