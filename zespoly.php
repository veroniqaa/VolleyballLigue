<?php

    session_start();

    require_once "connect.php";

?>

<?php include('interface/naglowek.php') ?>

   <div id="panel_glowny">
    
   <div id='tytul'> DRUŻYNY </div>

   <div class="formularz">

   <?php

    if($polaczenie)
    {
        /**
         * SelectBox z wszystkimi zespołami z bazy do wyboru
         */
        $q="SELECT * FROM wszystkie_zespoly";
        if($rezultat=pg_query($polaczenie, $q))
        {
            echo('<select class="select" id="wybor_zespol" onchange="WyborZespoly();">');
            echo('<option class="option" value="0" >--- Wybierz zespół ---</option>');

            while($wiersz=pg_fetch_assoc($rezultat))
            {
                echo('<option class="option" value="'.$wiersz['id_zespol'].'">'.$wiersz['nazwa_zespolu'].'</option>');
            }
            echo('</select>');

            pg_free_result($rezultat);
        }   

        /**
         * SelectBox z imionami i nazwiskami wszystkich trenerów z bazy do wyboru
         */
        $q="SELECT * FROM wszyscy_trenerzy_imie_nazwisko";

        if($rezultat=pg_query($polaczenie, $q))
        {
            echo('<select class="select" id="wybor_trener" onchange="WyborZespoly();">');
            echo('<option class="option" value="0" >--- Wybierz trenera ---</option>');

            while($wiersz=pg_fetch_assoc($rezultat))
            {
                echo('<option class="option" value="'.$wiersz['id_trener'].'">'.$wiersz['imie'].' '.$wiersz['nazwisko'].'</option>');
            }
            echo('</select>');

            pg_free_result($rezultat);
        }   

        /**
         * SelectBox z wszystkimi możliwymi rodzajami sortowań do wyboru
         */
        echo ('<select class="select" id="sortowanie" onchange="WyborZespoly();">');
        echo ('<option class="option" value="0"> --- Wybierz opcję sortowania ---</option>');
        echo ('<option class="option" value="1"> Alfabetycznie - drużyna </option>');
        echo ('<option class="option" value="2"> Alfabetycznie - miasto </option>');
        echo ('<option class="option" value="3"> Po dacie dodania </option>');
        echo ('</select>');
        echo ('</div>');

        /**
         * zwracanie wszystkich zespołów z bazy 
         */
        $q = " SELECT * FROM wszystkie_zespoly";

        if($rezultat=pg_query($polaczenie, $q))
        {
           $ile=pg_num_rows($rezultat);

           echo "<div id='DivZespoly'>";
           while($wiersz=pg_fetch_assoc($rezultat))
           { 
                echo "<div class='zespol'>";
                echo "<span class='nazwa_zespolu'>".$wiersz['nazwa_zespolu']."</span></br></br>";
                echo "<div class='zespol_dane'>";
                echo "<b> Miasto: </b>".$wiersz['miasto']."</br>";

                /**
                 * zwracanie trenerów danego zespołu
                 */
                $q2 = " SELECT * FROM trener_w_zespole (".$wiersz['id_zespol'].")";
                    
                    if($rezultat2 = pg_query($polaczenie, $q2))
                    {
                        $ile2=pg_num_rows($rezultat2);
                        echo "<table id='zespoly_tabelka'>";
                        echo "<th colspan=5> TRENERZY </th>";
                        while($wiersz2=pg_fetch_assoc($rezultat2))
                        {
                            echo "<tr> <td>".$wiersz2['nazwisko']." ".$wiersz2['imie']."</td> <td> ".$wiersz2['kraj']." </td> <td> ".$wiersz2['wiek']." lat </td> <td> ".$wiersz2['funkcja']." </td></tr>";
                        }
                        echo "</table>";
                    } 
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            pg_free_result($rezultat);
       }
       else
       {
           echo "Nic nie znalazłam";
       }
       pg_close($polaczenie);
    }

?>

   </div>

<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>