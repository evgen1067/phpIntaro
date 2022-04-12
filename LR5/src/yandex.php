<?php

$ini_array = parse_ini_file("parameters.ini", true);
$params = [
    'apikey' => $ini_array['apikey'],
    'geocode' => $_POST['address'],
    'format' => 'json',
];

$response = file_get_contents('https://geocode-maps.yandex.ru/1.x/?' . http_build_query($params));
$obj = json_decode($response, true);


# структурированный адрес
$address = ($obj['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['text']);

# координаты
$coordinates = $obj['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['boundedBy']['Envelope']['lowerCorner'];

$tmpCoordinates = str_replace(" ", ",", $coordinates);
$parameters = array(
    'apikey' => $ini_array['apikey'],
    'geocode' => $tmpCoordinates,
    'kind' => 'metro',
    'format' => 'json',
    'results' => '1'
);

$response = file_get_contents('https://geocode-maps.yandex.ru/1.x/?' . http_build_query($parameters));
$obj = json_decode($response, true);
# ближайшее метро
$metro = ($obj['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['text']);

echo json_encode([
    'address' => $address,
    'coordinates' => $coordinates,
    'metro' => $metro,
]);
