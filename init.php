<?php
function create_database(){
    /*
        Создание структуры базы данных
        Вход: нет
        Выход: нет
    */
    global $dbc;
    echo "Создание БД! <br> Внимание!<br> Не запускать на действующей системе! <br>";
    
    // Пересоздание БАЗЫ ДАННЫХ (удаление, а затем создание)
    try{
        //$dbc->exec('drop database if exists larsCustoms');
        $dbc->exec('create database larsCustoms');
        $dbc->exec('use larsCustoms');
    }catch(PDOException $err){
        echo $err->getMessage();
    }

    // ======== Создание таблицы users - список пассажиров
    try{
        $query_str = 'create table if not exists queuestamp (queueNum int unsigned 
                        , state varchar(10) NOT NULL
                        , number varchar(45) NOT NULL
                        , model varchar(16) NOT NULL
                        , datereg varchar(45) NOT NULL
                        , arrivalTime time NOT NULL
                        , actualtimestamp varchar(45))';
        $dbc->exec($query_str);
        echo 'Таблица очереди на КПП создана!<br>';
    }catch(PDOException $err){
        echo "queuestamp".$err->getMessage();
    }
}
?>