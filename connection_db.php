<?php
try {
	$dbc = new pdo('mysql:host=localhost;dbname=lars', 'root', 'root');
} catch (PDOException $err) {
	echo $err->getMessage();
}
