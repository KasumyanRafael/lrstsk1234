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
//$currentDate=$dateTime->format('d.m.Y H:i');
$currentDate=$dateTime->format('Y-m-d H:i'); // вывод в формате гг-мм-дд чч:мм
echo $currentDate;

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
    $datereg=explode(" ",$cols->item(4)->nodeValue);
    $timereg=$datereg[1];
    $datereg=$datereg[0];
    $departCountdown=$cols->item(5)->nodeValue;
    $sql="INSERT into customtraffic(queueNum,state,number,model,datereg,timereg,departCountdown,actualtimestamp) values($queue,'$state','$number','$model','$datereg','$timereg','$departCountdown','$currentDate')"; 
    $stmt = $dbc->prepare("SELECT COUNT(*) FROM customtraffic where queueNum=$queue and number='$number' and datereg ='$datereg' and timereg='$timereg' "); //executeScalar
    $stmt->execute();
    $row = $stmt->fetch();
    $count = $row[0];

    if($count==0){
        try{
             $dbc->exec($sql);
        }
        catch(PDOException $err){
             echo 'insert customtraffic ' . $err->getMessage();
             break;
        }
    }
}














