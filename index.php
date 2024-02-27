<?php
session_start();
header("Refresh: 60"); //автообновление каждые 1 минуту
include('connection_db.php');
include('init.php');
$html = file_get_contents('https://codd15.ru/ticket.html');
$queue=array(); //номер очереди
$state=array(); //id страны
$number=array(); //номер машины
$model=array(); //модель машины
$reg=array(); //дата регистрации
$arr=array(); //время отправле

$dom = new domDocument;

/** загружаем html в объект **/
$dom->loadHTML($html);
$dom->preserveWhiteSpace = false;

/** элемент по тэгу **/
$tables = $dom->getElementsByTagName('table');

function special_push($a){
    if ($a==""){
        $a="NaN";
    }
    return $a;
}

/** получаем все строки таблицы **/
$rows = $tables->item(0)->getElementsByTagName('tr');
echo '<link rel="stylesheet" href="style.css">';
echo '<title>larsCustoms</title>';
echo '<table>';
echo '<tr><th>Номер в очереди</th><th>Страна</th><th>Номер</th><th>Модель</th><th>Дата регистрации</th><th>Отправление через</th><th>Актуально на</th></tr>';
/** цикл по строкам **/
foreach ($rows as $row)
{
    /** все ячейки по тэгу **/
    $cols = $row->getElementsByTagName('td');
    /** выводим значения **/
    array_push($queue,$cols->item(0)->nodeValue);
    array_push($state,$cols->item(1)->nodeValue);
    array_push($number,$cols->item(2)->nodeValue);
    array_push($model,$cols->item(3)->nodeValue);
    array_push($reg,$cols->item(4)->nodeValue);
    array_push($arr,$cols->item(5)->nodeValue);
    
    echo '<tr>';
    echo '<td>'.special_push($cols->item(0)->nodeValue).'</td>';
    echo '<td>'.special_push($cols->item(1)->nodeValue).'</td>';
    echo '<td>'.special_push($cols->item(2)->nodeValue).'</td>';
    echo '<td>'.special_push($cols->item(3)->nodeValue).'</td>';
    echo '<td>'.special_push($cols->item(4)->nodeValue).'</td>';
    echo '<td>'.special_push($cols->item(5)->nodeValue).'</td>';
    echo '<td class="time">'.'23.02.24 14:24'.'</td>';
    echo '</tr>';
}
//все массивы заполнены, с этим нет проблем
$len=count($model);
$dateTime = new DateTime();
$g=$dateTime->format('d.m.Y H:i'); // вывод в формате дд:мм:гг чч:мм, например 27:06:2021 10:30
create_database();
for($i=1;$i<$len;$i++){
    $a=$queue[$i];
    $b=$state[$i];
    $c=$number[$i];
    $d=$model[$i];
    $e=$reg[$i];
    $f=$arr[$i];
    $sql="INSERT into queuestamp(queueNum,state,number,model,datereg,arrivalTime,actualtimestamp) values($a,'$b','$c','$d','$e','$f','$g')"; 
    try{
        $dbc->exec($sql);
        // $san_sql="DELETE FROM queuestamp WHERE queueNum = $a AND state = '$b' AND number = '$c' AND model ='$d' AND datereg ='$e' AND arrivalTime ='$f' AND actualtimestamp !='$g'";
        // $dbc->exec($san_sql); 
        //удаление дубликатных строк пока не работает. Поэтому лучше вручную страницу не перегружать пока.

    }
    catch(PDOException $err){
         echo 'insert queuestamp ' . $err->getMessage();
         break;
    }
}
echo '<script src="script.js"></script>';
echo '</table>';





