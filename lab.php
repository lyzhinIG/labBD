<?php
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
  
echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Лабы БД</title>';
echo ' <form action="lab.php" method="get">
 <p>Лаба: <input type="text" name="lab" size="3" maxlength="1"/> ---- Пункт: <input type="text" name="p" size="2" maxlength="1" /></p>
 <p><input type="submit" value="Ответ"/></p>
</form>';
 if($_GET['lab']=='2'){
     if($_GET['p']==1){
     $zapros ='SELECT delo, n_kamer, COUNT( * ) c
            FROM client
            GROUP BY delo, n_kamer
            HAVING COUNT( * ) >1
            ORDER BY COUNT( * ) DESC ';
            
            echo 'SELECT delo, n_kamer, COUNT( * ) c<br>
            FROM client<br>
            GROUP BY delo, n_kamer<br>
            HAVING COUNT( * ) >1<br>
            ORDER BY COUNT( * ) DESC ';
            echo '<hr>';
            
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>Дело</th><th>№ Камеры</th><th>Кол-во обвиняемых</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,delo), 
            "</td><td>",mysql_result($result,$i,n_kamer), 
            "</td><td>",mysql_result($result,$i,c), 
            "</td></tr>"; 
            echo "</table>"; 
     }
    if($_GET['p']==2){
     $zapros ='select   sum(c.oplata) d
               from client c, dela d
               where c.delo = d.delo and d.d_end>DATE_SUB(CURDATE(),Interval 1 YEAR)';
            
            echo "SELECT   sum(c.oplata) 'doxod'<br>
                  FROM client c, dela d<br>
                  WHERE c.delo = d.delo and d.d_end>DATE_SUB(CURDATE(),Interval 1 YEAR)";
            echo '<hr>';
            
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>Сумма гонарара</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,d), 
            "</td></tr>"; 
            echo "</table>"; 
     }
     if($_GET['p']==3){
     $zapros ='SELECT c.fio fio, c.delo delo, d.d_start start
                FROM client c, dela d
                WHERE c.delo = d.delo
                ORDER BY d.d_start DESC
                LIMIT 0, 15';
            
            echo "SELECT c.fio 'подзащитный', c.delo '№ дела', d.d_start 'Дело началось в...'<br>
                    FROM client c, dela d <br>
                    WHERE c.delo = d.delo <br>
                    ORDER BY d.d_start DESC ";
            echo '<hr>';
            
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ФИО подзащитного</th><th>№ дела</th><th>Дело началось в...</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,fio), 
            "</td><td>",mysql_result($result,$i,delo), 
            "</td><td>",mysql_result($result,$i,start), 
            "</td></tr>"; 
            echo "</table><hr>"; 
            
            //2-3-2
                 $zapros ='SELECT c.fio fio, c.delo delo, d.d_start start
                        FROM client c, dela d
                        WHERE c.delo = d.delo AND (TO_DAYS(d.d_start)-TO_DAYS(c.data_r))<18*365
                        ORDER BY d.d_start DESC 
                        LIMIT 0, 15';

            echo "SELECT c.fio 'подзащитный', c.delo '№ дела', d.d_start 'Дело началось в...'<br>
                    FROM client c, dela d<br>
                    WHERE c.delo = d.delo AND (TO_DAYS(d.d_start)-TO_DAYS(c.data_r))<18*365<br>
                    ORDER BY d.d_start DESC  ";
            echo '<hr>';
            
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ФИО подзащитного</th><th>№ дела</th><th>Дело началось в...</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,fio), 
            "</td><td>",mysql_result($result,$i,delo), 
            "</td><td>",mysql_result($result,$i,start), 
            "</td></tr>"; 
            echo "</table><hr>";
            
            //2-3-3
            $zapros ='SELECT c.fio fio, c.delo delo, st.max_srok srok, d.d_start start
                            FROM client c, obvinenie o, st_yk st, dela d
                            WHERE c.n_pasp=o.client and o.st=st.ST and st.max_srok>=10 and c.delo=d.delo
                            order by d.d_start DESC
                            LIMIT 0, 15';
            
            echo "SELECT c.fio 'Обвиняемый', c.delo 'дело', st.max_srok 'Максимальный срок', d.d_start 'Дата начала'<br>
                    FROM client c, obvinenie o, st_yk st, dela d<br>
                    WHERE c.n_pasp=o.client and o.st=st.ST and st.max_srok>=10 and c.delo=d.delo<br>
                    order by d.d_start DESC ";
            echo '<hr>';
            
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>Обвиняемый</th><th>№ дела</th><th>Максимальный срок</th><th>дата начала</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,fio), 
            "</td><td>",mysql_result($result,$i,delo), 
             "</td><td>",mysql_result($result,$i,srok), 
            "</td><td>",mysql_result($result,$i,start), 
            "</td></tr>"; 
            echo "</table>";
     }
  
 }

  if($_GET['lab']=='3'){
     if($_GET['p']=='1'){
         $zapros ='SELECT * from l3z1_2';
            
            echo "CREATE VIEW  l3z1_2 (FIO,SROK) <br>
                    AS SELECT c.fio, c.srok<br>
                    FROM client c<br>
                    WHERE srok >1<br>
                    AND result LIKE  '%осужден условно%'; ";
            echo '<hr>';
    
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ФИО</th><th>СРОК</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,FIO), 
            "</td><td>",mysql_result($result,$i,SROK), 
            "</td></tr>"; 
            echo "</table>";
     }
         if($_GET['p']=='2'){
         $zapros ='SELECT * from l3z2_1 LIMIT 0, 70';
            
            echo "CREATE VIEW  l3z2_1 (DELO,FIO,maxSROK,SROKmin) <br>
                    AS SELECT c.delo, c.fio, SUM( max_srok ) - c.srok, c.srok - SUM( min_srok ) <br>
                    FROM client c, st_yk st, obvinenie o<br>
                    WHERE c.n_pasp = o.client AND o.st = st.ST <br>
                    GROUP BY c.delo, c.n_pasp, c.fio; ";
            echo '<hr>';
    
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ДЕЛО</th><th>ФИО</th><th>СРОК МАКС - Результат</th><th>Результат - мин.срок</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,DELO), 
            "</td><td>",mysql_result($result,$i,FIO), 
            "</td><td>",mysql_result($result,$i,maxSROK), 
            "</td><td>",mysql_result($result,$i,SROKmin), 
            "</td></tr>"; 
            echo "</table>";
     }
      if($_GET['p']=='3'){
         $zapros ='SELECT * from l3z3_1 LIMIT 0, 70';
            
            echo "CREATE VIEW  l3z3_1 (DELO,ST)<br>
                    AS SELECT c.delo, o.st<br>
                    FROM client c, obvinenie o <br>
                    WHERE c.n_pasp = o.client
                     ";
            echo '<hr>';
    
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>№ ДЕЛА</th><th>№ Статьи</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,DELO), 
            "</td><td>",mysql_result($result,$i,ST), 
            "</td></tr>"; 
            echo "</table>";
     }
  }
  if ($_GET['lab']==4){
      echo "<h3>Исходные таблицы</h3><br> <b>политика</b><br>";
      
        $zapros ='SELECT * from news_politica';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,ID), 
            "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data),
            "</td></tr>"; 
            echo "</table><hr> <b>Образование</b>";
                 $zapros ='SELECT * from news_edu';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th><th>Автор</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,ID), 
            "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data_pub),
             "</td><td>",mysql_result($result,$i,autor),
            "</td></tr>"; 
            echo "</table><hr>";
  }
  if ($_GET['p']==1){
           echo "<b>Объединение</b><br>
                SELECT `title` `text`, `data` <br>
                FROM `news_politica` <br>
                UNION <br>
                SELECT `title` `text`, `data_pub` <br>
                FROM `news_edu`";
                 $zapros ='SELECT  `title` ,  `text` ,  `data` 
                            FROM  `news_politica` 
                            UNION 
                            SELECT  `title` ,  `text` ,  `data_pub` 
                            FROM  `news_edu` ';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>Загаловок</th><th>Текст</th><th>Дата</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data),
            "</td></tr>"; 
            echo "</table><hr>";
  }
    if ($_GET['p']==2){
           echo "<b>Разность</b><br>
                SELECT e.title, e.text, e.data_pub<br>
                    FROM news_edu e LEFT JOIN news_politica p <br>
                    ON p.title = p.title AND p.text = e.text AND e.data_pub=p.data<br>
                    WHERE p.title IS NULL and p.text IS NULL";
                 $zapros ='SELECT e.title, e.text, e.data_pub
                FROM news_edu e LEFT JOIN news_politica p 
                ON p.title = p.title AND p.text = e.text AND e.data_pub=p.data
                WHERE p.title IS NULL and p.text IS NULL';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>Загаловок</th><th>Текст</th><th>Дата</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data_pub),
            "</td></tr>"; 
            echo "</table><hr>";
  }
   if ($_GET['p']==3){
           echo "<b>Пересечение INTERSECT</b><br>
               SELECT p.title, p.text, p.data<br>
                FROM news_politica p JOIN news_edu e<br>
                ON p.title=e.title AND p.text = e.text AND p.data=e.data_pub;";
                 $zapros ='SELECT p.title, p.text, p.data
                            FROM news_politica p JOIN news_edu e
                            ON p.title=e.title AND p.text = e.text AND p.data=e.data_pub';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>Загаловок</th><th>Текст</th><th>Дата</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data),
            "</td></tr>"; 
            echo "</table><hr>";
  }
  
   if ($_GET['p']==4){
           echo "<b>Декартово произведение</b><br>
           <i>16 строк</i><br>
               SELECT * FROM news_politica, news_edu";
                 $zapros ='SELECT * FROM news_politica, news_edu';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th><th>автор</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,ID),
           "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data),
            "</td><td>",mysql_result($result,$i,ID),
           "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data_pub),
            "</td><td>",mysql_result($result,$i,autor),
            "</td></tr>"; 
            echo "</table><hr>";
            echo ' <i>4 строки</i><br>
                SELECT * <br>
                FROM news_politica p, news_edu e<br>
                WHERE e.id = p.id<br>';
                $zapros ='SELECT * 
                FROM news_politica p, news_edu e
                WHERE e.id = p.id';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th><th>автор</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,ID),
           "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data),
            "</td><td>",mysql_result($result,$i,ID),
           "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data_pub),
            "</td><td>",mysql_result($result,$i,autor),
            "</td></tr>"; 
            echo "</table><hr>";
            
            echo "<i>2 строки</i><br>
                SELECT * FROM news_politica p , news_edu e<br>
                WHERE e.title=p.title";
                $zapros ='SELECT * FROM news_politica p , news_edu e
                    WHERE e.title=p.title';
            $result=mysql_query($zapros); 
            //$result - ассоциированный массив, т.е. таблички, у которой есть названия столбцов 
            //узнаем, сколько в массиве $result строчек 
            $n=mysql_num_rows($result); 
            //вывод на страничку в виде таблицы 
            echo "<table border=1> 
            <tr><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th><th>ID</th><th>Загаловок</th><th>Текст</th><th>Дата</th><th>автор</th></tr>"; 
            //вывод построчно 
            for($i=0;$i<$n;$i++) 
             echo  
            "<tr><td>",mysql_result($result,$i,ID),
           "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data),
            "</td><td>",mysql_result($result,$i,ID),
           "</td><td>",mysql_result($result,$i,title),
            "</td><td>",mysql_result($result,$i,text),
            "</td><td>",mysql_result($result,$i,data_pub),
            "</td><td>",mysql_result($result,$i,autor),
            "</td></tr>"; 
            echo "</table><hr>";
  }
  
  
  
?>