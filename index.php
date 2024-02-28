<?php
include('connection_db.php');
include('init.php');
$html = file_get_contents('https://codd15.ru/ticket.html');


$dom = new domDocument;
create_database();
/** загружаем html в объект **/
$dom->loadHTML($html);
$dom->preserveWhiteSpace = false;

/** элемент по тэгу **/
$tables = $dom->getElementsByTagName('table');
$dateTime = new DateTime();
$currentDate=$dateTime->format('d.m.Y H:i'); // вывод в формате дд:мм:гг чч:мм, например 27:06:2021 10:30


/** получаем все строки таблицы **/
$rows = $tables->item(0)->getElementsByTagName('tr');

/** цикл по строкам **/
foreach ($rows as $row)
{
    /** все ячейки по тэгу **/
    $cols = $row->getElementsByTagName('td');
    /** выводим значения **/
    $queue=$cols->item(0)->nodeValue;
    $state=$cols->item(1)->nodeValue;
    $number=$cols->item(2)->nodeValue;
    $model=$cols->item(3)->nodeValue;
    $datereg=$cols->item(4)->nodeValue;
    $departCountdown=$cols->item(5)->nodeValue;
    $sql="INSERT into customtraffic(queueNum,state,number,model,datereg,departCountdown,actualtimestamp) values($queue,'$state','$number','$model','$datereg','$departCountdown','$currentDate')"; 
    $checkquery = "select count(*) from customtraffic where state=$state and number=$number and model=$model and datereg=$datereg"; 
    
    // if(TRUE){
    //     try{
    //         $dbc->exec($sql);
    //     }
    //     catch(PDOException $err){
    //          echo 'insert customtraffic ' . $err->getMessage();
    //          break;
    //     }
    // }
    
}










