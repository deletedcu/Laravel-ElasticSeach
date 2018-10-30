<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dev-Tools</title>
    <style type="text/css">
        html { font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    
    <h1>Document Status Update</h1>
    <div class="results">
        <h3>Summary</h3>
        <div class="results-output">
            <strong>Documents updated: {{count($documentsUpdated)}} </strong> <hr>
            
            <table>
                @foreach($documentsUpdated as $document)
                    <tr><td>#{{$document->id}} - {{$document->name}} </td></tr>
                @endforeach
            </table>
            
        </div>
    </div>
    
</body>
</html>

