<?php

use App\HelperFunctions;
use App\MysqlSelect;
use App\MysqlSetup;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$setup = new MysqlSetup();
$connection = $setup->mysqlConnect($_ENV['DB_SERVER'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

if (!mysqli_select_db($connection, $_ENV['DB_NAME'])) {
    $setup->createDatabase($connection, $_ENV['DB_NAME']);
    mysqli_select_db($connection, $_ENV['DB_NAME']);
}

if (!mysqli_query($connection, "SELECT 1 FROM Products.product")){
    $lines = file('test.sql');
    $setup->seedDatabase($lines, $connection);
}

$selection = new MysqlSelect();
$data = $selection->getSelection($connection);
$connection->close();

$filePath = 'products.xml';
$dom = new DOMDocument('1.0', 'utf-8');
$root = $dom->createElement('root');

for ($i = 0; $i < count($data); $i += 3) {
    $names = HelperFunctions::sortLanguageContent($data[$i][7], $data[$i][10], $data[$i+1][7], $data[$i+1][10], $data[$i+2][7], $data[$i+2][10]);
    $descriptions = HelperFunctions::sortLanguageContent($data[$i][8], $data[$i][10], $data[$i+1][8], $data[$i+1][10], $data[$i+2][8], $data[$i+2][10]);

    $item = $dom->createElement('item');

    $model = $dom->createElement('model', $data[$i][0]);
    $item->appendChild($model);

    $status = $dom->createElement('status', $data[$i][1]);
    $item->appendChild($status);

    $name = $dom->createElement('name');
    HelperFunctions::setLanguageContent($dom, $names, $name);
    $item->appendChild($name);

    $description = $dom->createElement('description');
    HelperFunctions::setLanguageContent($dom, $descriptions, $description);
    $item->appendChild($description);

    $quantity = $dom->createElement('quantity', $data[$i][2]);
    $item->appendChild($quantity);

    $ean = $dom->createElement('ean', $data[$i][3]);
    $item->appendChild($ean);

    $image = $dom->createElement('image_url', "https://www.webdev.lv/{$data[$i][4]}");
    $item->appendChild($image);

    $date_created = $dom->createElement('date_created', date_format(new DateTime($data[$i][5]), 'd-m-Y'));
    $item->appendChild($date_created);

    $regular_price = $data[$i][6] + ($data[$i][6] * (float)$_ENV['PVN']);
    $price = $dom->createElement('price', number_format($regular_price, 2,'.', ' '));
    $item->appendChild($price);

    $discount_price = HelperFunctions::checkSpecials($data[$i][11], $data[$i][12], $data[$i][13]);
    $special_price = $dom->createElement('special_price', number_format($discount_price, 2, '.',' '));
    $item->appendChild($special_price);

    $root->appendChild($item);
}

$dom->appendChild($root);
$dom->formatOutput = true;
$dom->save($filePath);

if ($dom->save($filePath)){
    echo "XML file - products.xml created successfully" . PHP_EOL;
}else {
    echo "XML file - products.xml was not created" . PHP_EOL;
}
