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
    $delo = rand(100,999999); // номер дела
    $pasp = "".rand(1000,9999)." ".rand(100000,999999)."";
    $s = rand(7,10); //длинна фамилии
    $s2 = rand (8,13); //имени
    $s3 = rand(6,15); //отчества
    $fio = "".random_string(1, "upper")."".random_string($s, "lower")." ".random_string(1, "upper")."".random_string($s2, "lower")." ".random_string(1, "upper")."".random_string($s3, "lower")."";
    $m=rand(1,12); // месяц
    $d = rand(1,28); //день
    $year = rand(1901,2002); //год
    $data_r = date("M:d:Y", mktime(0, 0, 0, $m, $d, $year));//тут тип вывода
    if(rand(0,1)==1){ // будут примечания или нет
        $prim=random_string($s3, "lower"); //генерируем примечание
    }
        else $prim='';
    $n_kamer = rand(1,999); // номер камеры
    $oplata = rand (500,1500000); 
    $fr = rand(1,3); //результат
    if( $fr = 1){ $result = 'оправдан'; $srok=0; } // если оправдан, срок сразу 0 выставляем флаг храним
    if ($fr =2) $result = 'осужден';
    if ($fr =3) $result = 'осужден условно';
    $j=1;//служебная, начало нумерации массива
    $buf = mysql_query("SELECT  `ST` FROM  `st_yk`  "); //получаем данные о статьях, для заполнения таблицы обвинения
    while ($M = mysql_fetch_row($buf)){
        $MAS_ST[$j] =  $M[0];
        $j++;
    }
    $buf = mysql_query("SELECT  `max_srok` FROM  `st_yk` "); // данные о макс сроках для генерации сроков
    $j=1;
    while ( $M = mysql_fetch_row($buf)){
        $MAS_SRK[$j] =  $M[0];
        $j++;
    }
   /*проверка корректности полученного массива*/
   echo count($MAS_ST);
    echo "<br>";
    echo count($MAS_SRK);
    /*конец проверки*/
    $kolvo_st = rand(1,3); // по скольким статьям обвинят клиента
    if ( $kolvo_st == 1 ) $por_n_st = rand(1,172);
    if ( $kolvo_st == 2 ){
        $por_n_st = rand(1,80);
        $por_n_st2 = rand(81,172); //костыльная реализация выбирания номеров статей
    } 
    if ( $kolvo_st == 3 ){
        $por_n_st = rand(1,70);
        $por_n_st2 = rand(71,129);
        $por_n_st3 = rand(130,172);
    } 
    if ($fr!=1) { //теперь играем в суд и случайно наказание выбираем 
       if ($kolvo_st == 1) $srok = $MAS_SRK[$por_n_st]*(rand(5,10)/10); // иногда такое чувство, что в РФ судьи так и генерируют приговоры
       if ($kolvo_st == 2) $srok = $MAS_SRK[$por_n_st]*(rand(5,10)/10)+$MAS_SRK[$por_n_st2]*(rand(3,9)/10);
       if ($kolvo_st == 2) $srok = $MAS_SRK[$por_n_st]*(rand(5,10)/10)+$MAS_SRK[$por_n_st2]*(rand(3,9)/10)+$MAS_SRK[$por_n_st3]*(rand(1,7)/10); 
    }
    $sql = mysql_query("INSERT INTO `client`  (`delo`, `n_pasp`, `fio`, `data_r`, `prim`, `n_kamer`, `oplata`, `result`, `srok`) 
                        VALUES ('". $delo."','".$pasp."','".$fio.",'".$data_r."','".$prim.",'".$n_kamer."',
                        '".$oplata.",'".$result."','".$srok."')");
                            if ($sql) {
        echo "<p>Данные успешно добавлены в таблицу.</p>";
        $flagADD == 1;
    } else {
        echo "<p>Произошла ошибка.</p>";
        $flagADD == 0;// флаг для определения нужно добавлять данные в остальные таблицы или что-то пошло не так и нужно пропустить
        echo $sql;
    }
    $flagADD == 1;
    //INSERT INTO `obvinenie`(`client`, `st`, `id`) VALUES ([value-1],[value-2],[value-3])
    /*формирования таблицы obvinenie*/
    
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