<?php
include('connection_db.php');
$html = file_get_contents('https://codd15.ru/ticket.html');

$dom = new domDocument;
libxml_use_internal_errors(true);
/** загружаем html в объект **/
$dom->loadHTML($html);
$dom->preserveWhiteSpace = false;

/** статус проезда **/
$gateStatus = $dom->getElementsByTagName('h4')->item(0)->nodeValue;
if (strpos($gateStatus, 'ЗАКРЫТ'))
    $gateStatus = 0;
else
    $gateStatus = 1;

/** элемент по тэгу **/
$tables = $dom->getElementsByTagName('table');

/** получаем все строки таблицы **/
$rows = $tables->item(0)->getElementsByTagName('tr');

/** цикл по строкам **/

foreach ($rows as $key => $row) {
    /** пропускаем первую строку (шапку таблицы) **/
    if ($key === 0)
        continue;
    
    /** все ячейки по тэгу **/
    $cols = $row->getElementsByTagName('td');

    /** выводим значения **/
    $queueNumber = $cols->item(0)->nodeValue;
    $country = $cols->item(1)->nodeValue;
    $number = $cols->item(2)->nodeValue;
    $model = $cols->item(3)->nodeValue;
    $dateReg = explode(" ", $cols->item(4)->nodeValue);
    $timeReg = $dateReg[1];
    $dateReg = explode(".", $dateReg[0]);
    $dateReg = "20" . $dateReg[2] . "-" . $dateReg[1] . "-" . $dateReg[0];
    $timeDepart = $cols->item(5)->nodeValue;
    $dateTime = new DateTime();
    $timeActual = $dateTime->format('Y-m-d H:i');

    $sql = "SELECT * FROM `traffic` WHERE `queueNumber`='$queueNumber' AND `number`='$number' AND `dateReg`='$dateReg' AND `timeReg`='$timeReg'";
    $stmt = $dbc->prepare($sql);
    $stmt->execute();

    /** проверяем наличие аналогичных записей в БД за текущие сутки **/
    if ($stmt->rowCount() === 0) {
        try {
            $sql = "INSERT INTO `traffic` (`queueNumber`, `country`, `number`, `model`, `dateReg`, `timeReg`, `timeDepart`, `gateStatus`) VALUES ('$queueNumber', '$country', '$number', '$model', '$dateReg', '$timeReg', '$timeDepart', '$gateStatus')";
            $stmt = $dbc->prepare($sql);
            $stmt->execute();
        } catch (PDOException $err) {
            echo 'insert traffic ' . $err->getMessage();
            break;
        }
    }
}
