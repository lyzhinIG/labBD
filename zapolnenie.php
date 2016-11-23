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
  echo("<P>сервер базы данных не доступен</P>");
  exit();
}
  mysql_select_db($DBname,$dbcnx);

    
/*генерация в таблицу статьи*/

/*for($i=0;$i<2;$i++){
 $G_ST=$i*20+rand(0,9)+rand(0,2)*100;
 $G_srok= rand(1,99);
 $G_min_srok=$G_srok*(rand(2,9)/10);
 $G_max_srok=$G_srok*(rand(11,21)/10);
 $sql = mysql_query("INSERT INTO `st_yk` (`ST`, `min_srok`,`max_srok`) 
                        VALUES ('".$G_ST."','".$G_min_srok."','".$G_max_srok."')");
if ($sql) {
        echo "<p>Данные успешно добавлены в таблицу. st_yk</p>";
    } else {
        echo "<p>Произошла ошибка. st_yk</p>";
        echo $sql;
    }
 //echo $G_ST,' ',$G_min_srok,' ',$G_max_srok;
 //echo '</br>';
}*/

/*заполнение таблицы клиенты*/
for($i=1;$i<10;$i++){ //сколько записей у нас будет
 $flagADD=-1;//флаг для таблицы клиенты
 $flagADD2=-1;//таблица обвинение
 $flagADD3=-1;//таблица дела
    $delo = rand(100,999999); // номер дела
    $pasp = "".rand(1000,9999)." ".rand(100000,999999)."";//серия и номер паспорта
    $s = rand(7,10); //длинна фамилии
    $s2 = rand (8,13); //имени
    $s3 = rand(6,15); //отчества
    $fio = "".random_string(1, "upper")."".random_string($s, "lower")." ".random_string(1, "upper")."".random_string($s2, "lower")." ".random_string(1, "upper")."".random_string($s3, "lower")."";
    $m=rand(1,12); // месяц рождения
    $d = rand(1,28); //день
    $year = rand(1901,2002); //год
    $data_r = date("Y-m-d", mktime(0, 0, 0, $m, $d, $year));//тут тип вывода
    echo $data_r,'<br>';
    if(rand(0,1)==1){ // будут примечания или нет
        $prim=random_string($s3, "lower"); //генерируем примечание
    }
        else $prim='';
    $n_kamer = rand(1,199); // номер камеры
    $oplata = rand (500,1500000); 
    $fr = rand(1,3); //результат
    if($fr==1){ $result = 'оправдан'; $srok=0; } // если оправдан, срок сразу 0 выставляем флаг храним
    if ($fr==2) $result = 'осужден';
    if ($fr==3) $result = 'осужден условно';
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
//echo count($MAS_ST);
//echo "<br>";
//echo count($MAS_SRK);
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
    if ($fr==2 or $fr==3) { //теперь играем в суд и случайно наказание выбираем 
       if ($kolvo_st == 1) $srok = $MAS_SRK[$por_n_st]*(rand(5,10)/10); // иногда такое чувство, что в РФ судьи так и генерируют приговоры
       if ($kolvo_st == 2) $srok = $MAS_SRK[$por_n_st]*(rand(5,10)/10)+$MAS_SRK[$por_n_st2]*(rand(3,9)/10);
       if ($kolvo_st == 3) $srok = $MAS_SRK[$por_n_st]*(rand(5,10)/10)+$MAS_SRK[$por_n_st2]*(rand(3,9)/10)+$MAS_SRK[$por_n_st3]*(rand(1,7)/10); 
    }
/*длинный запрос на внесение данных*/
 $sql2 = "INSERT INTO `client`(`delo`, `n_pasp`, `fio`, `data_r`, `prim`, `n_kamer`, `oplata`, `result`, `srok`)
VALUES ('".$delo."','".$pasp."','".$fio."', '".$data_r."','".$prim."','".$n_kamer."','".$oplata."','".$result."','".$srok."')"; 
     $sql = mysql_query ($sql2);
                            if ($sql) {
        echo "<p>Данные успешно добавлены в таблицу. табл клиенты</p>";
        $flagADD=1;
    } else {
        echo "<p>Произошла ошибка. табл. клиенты</p>";
        $flagADD=0;// флаг для определения нужно добавлять данные в остальные таблицы или что-то пошло не так и нужно пропустить
    }
    
    /*формирование таблицы obvinenie*/
    /*данные не генерируем, они нам извесны из предыдущей таблицы*/
    if ($flagADD == 1) {//формируем, только если клиент нормально записался
    if ( $kolvo_st == 1 ) {//если по одной статье
                 $sql = mysql_query("INSERT INTO `obvinenie`  (`client`, `st`)  VALUES ('".$pasp."','".$MAS_ST[$por_n_st]."')");
                                    if ($sql) $flagADD2 == 1;
                                        else $flagADD2 == 0;
            }
            if ( $kolvo_st == 2 ){//по двум
                 $sql = mysql_query("INSERT INTO `obvinenie`  (`client`, `st`)  VALUES ('". $pasp."','".$MAS_ST[$por_n_st]."')");
                                    if ($sql) $flagADD2=1;
                                        else $flagADD2=0;
                 $sql = mysql_query("INSERT INTO `obvinenie`  (`client`, `st`)  VALUES ('". $pasp."','".$MAS_ST[$por_n_st2]."')");
                                    if ($sql) $flagADD2=1;
                                        else $flagADD2=0;
            } 
            if ( $kolvo_st == 3 ){ //по трем
                  $sql = mysql_query("INSERT INTO `obvinenie`  (`client`, `st`)  VALUES ('". $pasp."','".$MAS_ST[$por_n_st]."')");
                                    if ($sql) $flagADD2=1;
                                        else $flagADD2=0;          
                 $sql = mysql_query("INSERT INTO `obvinenie`  (`client`, `st`)  VALUES ('". $pasp."','".$MAS_ST[$por_n_st2]."')");
                                    if ($sql) $flagADD2=1;
                                        else $flagADD2=0;
                 $sql = mysql_query("INSERT INTO `obvinenie`  (`client`, `st`)  VALUES ('". $pasp."','".$MAS_ST[$por_n_st3]."')");
                                    if ($sql) $flagADD2=1;
                                        else $flagADD2= 0;
    }
    }
    
      /*формирование таблицы dela*/
    if($flagADD==1 and $flagADD2==1){
         $year_start=1900;
         $year_end = 2000;
       if($year>1984) { // если человек старше то можноо больше даты дел, а если меньше, то меньше чтоб не получилось что дело на 15 летнего открыли
           $year_delo = rand(2011,2014);
           $year_start = $year_delo-rand(0,4);
           $year_end = $year_delo+rand(0,2);
       }
       else {
           $year_delo = rand(2000,2010);
           $year_start = $year_delo-rand(0,10);
           $year_end = $year_delo+rand(0,6);
       }
        $m=rand(1,12); // месяц
        $m_end=rand(1,12);//месяц для окончания
        $d = rand(1,28); //день
        $data_start = date("Y-m-d", mktime(0, 0, 0, $m, $d, $year_start));
        $data_end = date("Y-m-d", mktime(0, 0, 0, $m_end, $d, $year_end));//тут тип вывода
        $sql = mysql_query("INSERT INTO `dela` (`delo`, `d_start`, `d_end`)  VALUES ('". $delo."','". $data_start."','". $data_end."')");
                                    if ($sql) $flagADD3=1;
                                        else $flagADD3=0;
    }
    if($flagADD==1 and $flagADD2==1 and $flagADD3==1){//все ли получилось
        echo '>>>all :) ok<<<<<</br>';
    }
    else echo '>>>>not ok((((</br>';
    
}

if(mysql_close($dbcnx)) // разрываем соединение
{
  echo("Соединение с базой данных прекращено");
}
else
{
  echo("Не удалось завершить соединение");
}
?>