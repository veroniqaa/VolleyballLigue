<?php

    session_start();
    require_once "connect.php";

    if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany']==false))
	{
		header('Location: logowanie.php');
		exit();
	}
?>

<?php include('interface/naglowek.php') ?>

   <div id="panel_glowny">

   <div id='tytul'> NOWY ZESPÓŁ </div>
    
    <div class="formularz">
    <form action="dodaj.php" method="post">

    <input type="text" name="nowa_nazwa_zespolu" id="nowa_nazwa_zespolu" placeholder="Nazwa zespołu"/> 
    <input type="text" name="nowe_miasto" id="nowe_miasto" placeholder="Nazwa miasta"/> </br>
    
<?php

    echo '<div id="tytul"> ZAWODNICY </div>';
    
    if($polaczenie)
    {
        for($i=1;$i<=7;$i++)
        {
            if($i==1)
                $poz = 'atakujący';
            else if($i==2 || $i==3)
                $poz = 'przyjmujący';
            else if($i==4 || $i==5)
                $poz = 'środkowy';
            else if($i==6)
                $poz = 'rozgrywający';
            else if($i==7)
                $poz = 'libero';

            echo '<br/>';
            echo '<b>Siatkarz '.$i.' - '.$poz.':</b>';
            echo '<br/><br/>';
            echo '<input type="text" name="nowe_imie'.$i.'" id="nowe_imie'.$i.'" placeholder="Imię"/>';
            echo '<input type="text" name="nowe_nazwisko'.$i.'" id="nowe_nazwisko'.$i.'" placeholder="Nazwisko"/> <br/>';
            echo '<input type="text" name="nowy_wiek'.$i.'" id="nowy_wiek'.$i.'" placeholder="Wiek"/>';
            echo '<input type="text" name="nowy_numer'.$i.'" id="nowy_numer'.$i.'" placeholder="Numer"/><br/>';
            
            /**
             * SelectBox z wszystkimi narodowościami z bazy do wyboru
             */
            $q="SELECT * FROM wszystkie_narodowosci";
            if($rezultat=pg_query($polaczenie, $q))
            {
                echo('<select class="select" id="nowa_nar'.$i.'" name="nowa_nar'.$i.'" onchange="DodajNarodowosc('.$i.', this.value);">');
                echo('<option class="option" value="0" >--- Wybierz narodowość ---</option>');

                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<option class="option" value="'.$wiersz['id_narodowosc'].'">'.$wiersz['kraj'].'</option>');
                }
                echo('<option class="option" value="-1"> Dodaj narodowość </option>');
                echo('</select>');
                echo('<span id="div_nar'.$i.'">');
                echo('</span>');
                pg_free_result($rezultat);
            }

            echo '</br><br/>';
        }

        echo '<div id="tytul"> TRENERZY </div>';
        
        echo '<br/>';

        for($i=1;$i<=2;$i++)
        {
            echo '<b>Trener '.$i.':</b>';
            echo '<br/><br/>';
            echo '<input type="text" name="tr_nowe_imie'.$i.'" id="tr_nowe_imie'.$i.'" placeholder="Imię"/>';
            echo '<input type="text" name="tr_nowe_nazwisko'.$i.'" id="tr_nowe_nazwisko'.$i.'" placeholder="Nazwisko"/> <br/>';
            echo '<input type="text" name="tr_nowy_wiek'.$i.'" id="tr_nowy_wiek'.$i.'" placeholder="Wiek"/>';
            echo '<input type="text" name="tr_nowa_funkcja'.$i.'" id="tr_nowa_funkcja'.$i.'"placeholder="I trener/II trener"/> <br/>';

            /**
             * SelectBox z wszystkimi narodowościami z bazy do wyboru
             */
            $q="SELECT * FROM wszystkie_narodowosci";
            if($rezultat=pg_query($polaczenie, $q))
            {   
                $j=$i+7;
                
                echo('<select class="select" name="tr_nowa_nar'.$i.'" onchange="DodajNarodowosc('.$j.', this.value);">');
                echo('<option class="option" value="0" >--- Wybierz narodowość ---</option>');

                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<option class="option" value="'.$wiersz['id_narodowosc'].'">'.$wiersz['kraj'].'</option>');
                }
                echo('<option class="option" value="-1"> Dodaj narodowość </option>');
                echo('</select>');
                echo('<span id="div_nar'.$j.'">');
                echo('</span>');
                pg_free_result($rezultat);
            }

            echo '<br/>';
            echo '<br/><br/>';
        }

        pg_close($polaczenie);
    }
   
       
    ?>

    </br>
        <input type="submit" name="submit_zespol" value="Dodaj drużynę" class="search_button"/>

     </form>   
    </div>

   </div>


<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>
