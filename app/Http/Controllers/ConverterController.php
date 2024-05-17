<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use SimpleXMLElement;

class ConverterController extends Controller
{
    //convert CSV to JSON
    public function showCsvToJson()
    {
        return view('csv-to-json');
    }

    public function csvToJson(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');

        $csvData = array_map('str_getcsv', file($file));

        $headers = array_shift($csvData);

        $jsonData = [];
        foreach ($csvData as $row) {
            $jsonData[] = array_combine($headers, $row);
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'json_data');
        file_put_contents($tempFilePath, json_encode($jsonData));

        return response()->download($tempFilePath, 'JSON_data.json')->deleteFileAfterSend(true);
    }

    //convert XML to JSON
    public function showXmlToJson()
    {
        return view('xml-to-json');
    }

    public function xmlToJson(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml',
        ]);

        $xmlFile = $request->file('xml_file');

        $xmlString = file_get_contents($xmlFile->path());
        $xml = simplexml_load_string($xmlString);

        $json = json_encode($xml);
        $array = json_decode($json, true);

        $directory = public_path('json');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'converted_json.json';
        $filePath = $directory . '/' . $fileName;

        file_put_contents($filePath, $json);

        return response()->download($filePath, $fileName);
    }

    //convert XLSX to JSON
    public function showXlsxToJson()
    {
        return view('xlsx-to-json');
    }

    public function xlsxToJson(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');

        $spreadsheet = IOFactory::load($file);

        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $data = [];

        $headers = [];
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $headers[] = $sheet->getCell($col . '1')->getValue();
        }

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            $colIndex = 0;
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sheet->getCell($col . $row)->getValue();
                $rowData[$headers[$colIndex]] = $cellValue;
                $colIndex++;
            }
            $data[] = $rowData;
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="data.json"',
        ];

        return response($jsonData, 200, $headers);
    }

    //convert JSON to CSV
    public function showJsonToCsv()
    {
        return view('json-to-csv');
    }

    public function jsonToCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:json|max:2048',
        ]);

        $filePath = $request->file('file')->store('uploads');

        $jsonContents = Storage::get($filePath);
        $data = json_decode($jsonContents, true);

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys($data[0]));
        foreach ($data as $row) {
            $csv->insertOne($row);
        }

        $csvFilePath = str_replace('.json', '.csv', $filePath);
        Storage::put($csvFilePath, $csv->getContent());

        return response()->download(storage_path('app/' . $csvFilePath))->deleteFileAfterSend();
    }

    //convert XLSX to CSV
    public function showXlsxToCsv()
    {
        return view('xlsx-to-csv');
    }

    public function xlsxToCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        $xlsxFilePath = public_path('uploads') . '/' . $filename;
        $csvFilePath = public_path('uploads') . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.csv';

        $spreadsheet = IOFactory::load($xlsxFilePath);
        $writer = IOFactory::createWriter($spreadsheet, 'Csv');
        $writer->setDelimiter(',');
        $writer->setEnclosure('"');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        $writer->save($csvFilePath);

        return response()->download($csvFilePath)->deleteFileAfterSend();
    }

    //convert JSON to XML
    public function showJsonToXml()
    {
        return view('json-to-xml');
    }

    public function jsonToXml(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
        ]);

        $jsonFile = $request->file('json_file');

        $jsonData = json_decode(file_get_contents($jsonFile->path()), true);

        $xml = new SimpleXMLElement('<root/>');
        $this->arrayToXml($jsonData, $xml);

        $tempFilePath = tempnam(sys_get_temp_dir(), 'xml');
        file_put_contents($tempFilePath, $xml->asXML());

        return response()->download($tempFilePath, 'converted_data.xml', [
            'Content-Type' => 'application/xml'
        ])->deleteFileAfterSend(true);
    }

    function arrayToXml($array, &$xml){
        foreach ($array as $key => $value) {
            if(is_int($key)){
                $key = "e";
            }
            $updatedKey = str_replace(' ', '', $key);

            if(is_array($value)){
                $label = $xml->addChild($updatedKey);
                $this->arrayToXml($value, $label);
            }
            else {
                $xml->addChild($updatedKey, $value);
            }
        }
    }

    //convert JSON to XLSX
    public function showJsonToXlsx()
    {
        return view('json-to-xlsxx');
    }

    public function jsonToXlsx(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('json_file');
        $jsonData = file_get_contents($file);
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['Invalid JSON file.']);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $row = 1;
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $col = 'A';
                foreach ($value as $subKey => $subValue) {
                    $sheet->setCellValue($col . $row, $subValue);
                    $col++;
                }
            } else {
                $sheet->setCellValue('A' . $row, $value);
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $xlsxFilePath = storage_path('app/public/convert.xlsx');
        $writer->save($xlsxFilePath);

        return response()->download($xlsxFilePath)->deleteFileAfterSend(true);
    }

    //convert csv to XLSX
    public function showCsvToXlsx()
    {
        return view('csv-to-xlsx');
    }

    public function csvToXlsx(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');

        $reader = IOFactory::createReader('Csv');
        $spreadsheet = $reader->load($file->getPathname());

        $writer = new Xlsx($spreadsheet);
        $outputFilePath = storage_path('app/public/') . 'converted_file.xlsx';
        $writer->save($outputFilePath);

        return response()->download($outputFilePath)->deleteFileAfterSend(true);
    }
}
