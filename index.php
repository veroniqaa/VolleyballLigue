<?php

    session_start();
    require_once "connect.php";

?>

<?php include('interface/naglowek.php') ?>

<div id="panel_glowny">
<p>
<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>


        <p class='napisy_str_glowna'> TO ONI ZDOBYLI MISTRZOSTWO POLSKI PLUSLIGI W OSTATNICH PIĘCIU LATACH! </p>
        <div id="slajdy"></div>

        <div id="numerki">
        <span onclick="ustawslajd(1)" style="cursor:pointer;"> 1 </span>
		<span onclick="ustawslajd(2)" style="cursor:pointer;"> 2 </span>
		<span onclick="ustawslajd(3)" style="cursor:pointer;"> 3 </span>
		<span onclick="ustawslajd(4)" style="cursor:pointer;"> 4 </span>
		<span onclick="ustawslajd(5)" style="cursor:pointer;"> 5 </span>
        </div>

        <div id="statystyka">
<?php
        if($polaczenie)
        {
            error_reporting(0);
            /**
            * zwracanie liczby wszystkich zawodników w bazie, ich średniego wieku oraz najniższego i najwyższego
            */
            $q="SELECT COUNT(id_zawodnik), AVG(wiek)::numeric(10,0), MIN(wiek), MAX(wiek)
                FROM zawodnik
                JOIN osoba ON osoba.id_osoba=zawodnik.id_osoba;";
            
            if($rezultat=pg_query($polaczenie, $q))
            {                
                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<p> W naszej lidze gra już <b>'.$wiersz['count'].' zawodników</b>! </p>');
                    echo('<p> Średni wiek zawodników w lidze:<b> '.$wiersz['avg'].' lat.</b></p>');
                    echo('<p> Najmłodszy z nich ma<b> '.$wiersz['min'].' lat</b>, a najstarszy - <b> '.$wiersz['max'].' lat.</b></p>');
                }
                pg_free_result($rezultat);
            }   

            /**
            * zwracanie liczby wszystkich rozegranych meczów, zapisanych w bazie
            */
            $q="SELECT COUNT(id_mecz) FROM mecz;";
            
            if($rezultat=pg_query($polaczenie, $q))
            {                
                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<p> Rozegrano już <b>'.$wiersz['count'].' meczów </b>');
                }
                pg_free_result($rezultat);
            }   

            /**
            * zwracanie liczby wszystkich rozegranych meczów, zakończonych wynikiem 3:2 (tie-break'iem), zapisanych w bazie
            */
            $q=" SELECT COUNT(id_mecz) FROM mecz WHERE wynik='3:2';";
            
            if($rezultat=pg_query($polaczenie, $q))
            {                
                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('w tym <b> tie-breaków: '.$wiersz['count'].' </b>!</p>');
                }
                pg_free_result($rezultat);
            }   

            /**
            * zwracanie liczby kibiców w bazie oraz ich średniego wieku
            */
            $q="SELECT COUNT(id_kibic), AVG(wiek)::numeric(10,0)
                FROM kibic
                JOIN osoba ON osoba.id_osoba=kibic.id_osoba;";
            
            if($rezultat=pg_query($polaczenie, $q))
            {                
                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<p> Zawodników wspiera <b>'.$wiersz['count'].' kibiców</b>, <br/>których średni wiek wynosi <b>'.$wiersz['avg'].' lat</b>! </p>');
                }
                pg_free_result($rezultat);
            } 
            /**
            * zwracanie wszystkich zespołów, które nie rozegrały jeszcze żadnego meczu w lidze
            */
            $q="SELECT * FROM zespoly_bez_meczu";
            
            if($rezultat=pg_query($polaczenie, $q))
            {
                $ile_zespolow = pg_num_rows($rezultat); 

                if($ile_zespolow > 0)
                {
                    echo('<br/><p> Oni wciąż czekają na swój pierwszy mecz: </p>');
                    echo('<ul id="lista_zespolow">');
                    while($wiersz=pg_fetch_assoc($rezultat))
                    {
                        echo('<li>'.$wiersz['nazwa_zespolu'].'</li>');
                    }
                    echo('<ul>');
                
                }
                
                pg_free_result($rezultat);
            }

       pg_close($polaczenie);
    }

?>
        </div>
        
        <script type="text/javascript">

        window.onload = ustawslajd(1);
        
        var timer1 = 0;
        var timer2 = 0;
   
    </script>        

</p>

</div>

<?php include('interface/panel_boczny.php') ?>		
		
<?php include('interface/stopka.php') ?>

