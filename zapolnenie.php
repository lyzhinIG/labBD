<?php
function random_string($length, $chartypes) //для генерации случайной строки
{
    $chartypes_array=explode(",", $chartypes);
    // задаем строки символов. 
    //Здесь вы можете редактировать наборы символов при необходимости
    $lower = 'abcdefghijklmnopqrstuvwxyz'; // lowercase
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase
    $numbers = '1234567890'; // numbers
    $special = '^@*+-+%()!?'; //special characters
    $chars = "";
    // определяем на основе полученных параметров, 
    //из чего будет сгенерирована наша строка.
    if (in_array('all', $chartypes_array)) {
        $chars = $lower . $upper. $numbers . $special;
    } else {
        if(in_array('lower', $chartypes_array))
            $chars = $lower;
        if(in_array('upper', $chartypes_array))
            $chars .= $upper;
        if(in_array('numbers', $chartypes_array))
            $chars .= $numbers;
        if(in_array('special', $chartypes_array))
            $chars .= $special;
    }
    // длина строки с символами
    $chars_length = strlen($chars) - 1;
    // создаем нашу строку,
    //извлекаем из строки $chars символ со случайным 
    //номером от 0 до длины самой строки
    $string = $chars{rand(0, $chars_length)};
    // генерируем нашу строку
    for ($i = 1; $i < $length; $i = strlen($string)) {
        // выбираем случайный элемент из строки с допустимыми символами
        $random = $chars{rand(0, $chars_length)};
        // убеждаемся в том, что два символа не будут идти подряд
        if ($random != $string{$i - 1}) $string .= $random;
    }
    // возвращаем результат
    return $string;
}


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
  mysql_select_db($DBname,$dbcnx);

 /*$sql = mysql_query("INSERT INTO st_yk (`ST`, `min_srok`,`max_srok`) 
                        VALUES ('9','71','75')");
                            if ($sql) {
        echo "<p>Данные успешно добавлены в таблицу.</p>";
    } else {
        echo "<p>Произошла ошибка.</p>";
        echo $sql;
    }
    $n=$t=$e=2;
    $result = mysql_query ("INSERT INTO st_yk (`ST`, `min_srok`,`max_srok`)  VALUES (`1`,`2`,`3`)");

if ($result){
	echo "Информация занесена в базу данных";
}else{
	echo "Информация не занесена в базу данных";
}*/
    
    
//генерация в таблицу статьи
/*for($i=0;$i<40;$i++){
 $G_ST=$i*20+rand(0,9)+rand(0,2)*100;
 $G_srok= rand(1,99);
 $G_min_srok=$G_srok*(rand(2,9)/10);
 $G_max_srok=$G_srok*(rand(11,21)/10);
 $sql = mysql_query("INSERT INTO `st_yk` (`ST`, `min_srok`,`max_srok`) 
                        VALUES ('".$G_ST."','".$G_min_srok."','".$G_max_srok."')");
                            if ($sql) {
        echo "<p>Данные успешно добавлены в таблицу.</p>";
    } else {
        echo "<p>Произошла ошибка.</p>";
        echo $sql;
    }
 echo $G_ST,' ',$G_min_srok,' ',$G_max_srok;
 echo '</br>';
}*/

//INSERT INTO `client`(`delo`, `n_pasp`, `fio`, `data_r`, `prim`, `n_kamer`, `oplata`, `result`, `srok`) 
//VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9])
for($i=1;$i<2;$i++){
    $Delo = rand(100,999999);
    $pasp = "".rand(1000,9999)." ".rand(100000,999999)."";
    $s = rand(7,10);
    $s2 = rand (8,13);
    $s3 = rand(6,15);
    $fio = "".random_string(1, "upper")."".random_string($s, "lower")." ".random_string(1, "upper")."".random_string($s2, "lower")." ".random_string(1, "upper")."".random_string($s3, "lower")."";
    echo $fio;
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