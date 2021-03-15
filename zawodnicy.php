<?php

   session_start();
   require_once "connect.php";

?>

<?php include('interface/naglowek.php') ?>

   <div id="panel_glowny">

      <div id='tytul'> ZAWODNICY </div>

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
                echo('<select class="select" id="wybor_zespol" onchange="WyborZawodnicy();">');
                echo('<option class="option" value="0" >--- Wybierz zespół ---</option>');

                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<option class="option" value="'.$wiersz['id_zespol'].'">'.$wiersz['nazwa_zespolu'].'</option>');
                }
                echo('</select>');

                pg_free_result($rezultat);
            }

            /**
            * SelectBox z wszystkimi pozycjami z bazy do wyboru
            */
            $q="SELECT * FROM wszystkie_pozycje";
            if($rezultat=pg_query($polaczenie, $q))
            {
                echo('<select class="select" id="wybor_pozycja" onchange="WyborZawodnicy();">');
                echo('<option class="option" value="0"> --- Wybierz pozycję --- </option>');
                
                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<option class="option" value="'.$wiersz['id_pozycja'].'">'.$wiersz['nazwa_pozycja'].'</option>');
                }
                echo('</select>');

                pg_free_result($rezultat);
            }

            /**
            * SelectBox z wszystkimi narodowościami z bazy do wyboru
            */
            $q="SELECT * FROM wszystkie_narodowosci";
            if($rezultat=pg_query($polaczenie, $q))
            {
                echo('<select class="select" id="wybor_kraj" onchange="WyborZawodnicy();">');
                echo('<option class="option" value="0" >--- Wybierz narodowość ---</option>');

                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo('<option class="option" value="'.$wiersz['id_narodowosc'].'">'.$wiersz['kraj'].'</option>');
                }
                echo('</select>');

                pg_free_result($rezultat);
            }

         /**
         * SelectBox z wszystkimi możliwymi sortowaniami do wyboru
         */
         echo ('<select class="select" id="sortowanie" onchange="WyborZawodnicy();">');
         echo ('<option class="option" value="0"> --- Wybierz opcję sortowania ---</option>');
         echo ('<option class="option" value="1"> Alfabetycznie </option>');
         echo ('<option class="option" value="2"> Po drużynie </option>');
         echo ('<option class="option" value="3"> Po wieku </option>');
         echo ('</select>');
         echo ('</div>');
         
         /**
         * SelectBox z wszystkimi zawodnikami z bazy do wyboru
         */
         $q = "SELECT * FROM wszyscy_zawodnicy";

         if($rezultat=pg_query($polaczenie, $q))
         {
            $ile_osob = pg_num_rows($rezultat); 

            echo ('<div id="DivZawodnicy">');
            echo "<table class='tabelka'>  <th> NAZWISKO </th> <th> IMIĘ </th> <th> WIEK </th> <th> POZYCJA </th> <th> NUMER </th> <th> NARODOWOŚĆ </th> <th> KLUB </th>";

            while($wiersz=pg_fetch_assoc($rezultat))
            {            
               echo "<tr> <td>".$wiersz['nazwisko']."</td> <td>".$wiersz['imie']."</td> <td>".$wiersz['wiek']."</td> <td>".$wiersz['nazwa_pozycja']."</td> <td>".$wiersz['numer']."</td> <td>".$wiersz['kraj']."</td> <td>".$wiersz['nazwa_zespolu']."</td> </tr>";
            }
            echo "</table></div>";
            pg_free_result($rezultat);
         }
         else
         {
            echo "<p>Nic nie znalazlam.</p>";
         }
         pg_close($polaczenie);
      }
?>

   </div>

<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>

