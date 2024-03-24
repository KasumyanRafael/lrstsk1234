<?php
include('connection_db.php');
create_database();

function create_database()
{
    /*
        Создание структуры базы данных
        Вход: нет
        Выход: нет
    */
    global $dbc;
    echo "Создание БД! <br> Внимание!<br> Не запускать на действующей системе! <br>";

    // Пересоздание БАЗЫ ДАННЫХ (удаление, а затем создание)
    try {
        $dbc->exec('drop database if exists lars');
        $dbc->exec('create database lars');
        $dbc->exec('use lars');
    } catch (PDOException $err) {
        echo $err->getMessage();
    }

    // Создание таблицы traffic
    try {
        $query_str = "CREATE TABLE IF NOT EXISTS `traffic` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `queueNumber` int(10) unsigned DEFAULT NULL,
            `country` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `number` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `model` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `dateReg` date DEFAULT NULL,
            `timeReg` time DEFAULT NULL,
            `timeDepart` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `timeActual` datetime DEFAULT current_timestamp(),
            `gateStatus` tinyint(4) unsigned DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `number` (`number`),
            KEY `datereg` (`dateReg`),
            KEY `timereg` (`timeReg`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $dbc->exec($query_str);
        echo 'Таблица очереди на КПП создана!<br>';
    } catch (PDOException $err) {
        echo "customtraffic" . $err->getMessage();
    }
}
