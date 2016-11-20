<?php
$host="mysql2.justhost.ru ";/*Имя сервера*/
$user="u8002f93_lyzhin";/*Имя пользователя*/
$password="112233";/*Пароль пользователя*/
$db="u8002f93_laba";/*Имя базы данных*/

 
// подключаемся к серверу
$link = mysql_connect($host, $user, $password, $db) 
    or die("Ошибка " . mysql_error($link));
 
// выполняем операции с базой данных
     
// закрываем подключение
mysql_close($link);
?>