<?php
$callbackJSONData=file_get_contents('php://input');
$jsonFile = fopen('callback.json', 'w');
fwrite($jsonFile, $callbackJSONData);
