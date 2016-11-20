<?php
header("Content-type: text/html; charset=utf-8");
include 'config.php';
$dbcnx = mysql_connect($DBlocation,$DBuser,$DBpasswd);
mysql_set_charset('utf8');
if (!$dbcnx) // Если дескриптор равен 0 соединение не установлено
{
  echo("<P>В настоящий момент сервер базы данных не доступен, поэтому 
           корректное отображение страницы невозможно.</P>");
  exit();
}



if(mysql_close($dbcnx)) // разрываем соединение
{
  echo("Соединение с базой данных прекращено");
}
else
{
  echo("Не удалось завершить соединение");
}
echo "god";
?>