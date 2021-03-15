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

        <div id='tytul'> KIBICE </div>
        
        <div>
            <div class="formularz">
            <select class="select" name="sortowanie" onchange="SortowanieKibice(this.value);">
                <option class="option" value="0"> --- Sortuj --- </option>
                <option class="option" value="1"> Sortuj po nazwisku </option>
                <option class="option" value="2"> Sortuj po czasie dodania </option>
                <option class="option" value="3"> Sortuj po zespole </option>
            </select>

            </div>

            <div id="DivKibice"> 

            <table class='tabelka'>

            <th> LOGIN </th><th> IMIĘ </th><th> NAZWISKO </th><th> WIEK </th><th> ZESPÓŁ </th>
          
<?php
   
    if($polaczenie)
    {
        /**
         * zwracanie wszystkich kibiców z bazy do wyboru
         */
        $q = " SELECT * FROM wszyscy_kibice";      

        if($rezultat=pg_query($polaczenie, $q))
        {
            $ile=pg_num_rows($rezultat);

            while($wiersz=pg_fetch_assoc($rezultat))
            {
                echo "<tr> <td>".$wiersz['login']."</td> <td> ".$wiersz['imie']."</td> <td> ".$wiersz['nazwisko']." </td> <td> ".$wiersz['wiek']." lat </td> <td> ".$wiersz['nazwa_zespolu']." </td></tr>";
            }
            pg_free_result($rezultat);
       }
       else
       {
           echo "Nic nie znalazłam";
       }

       pg_close($polaczenie);
    }

   ?>
                </table>
            </div><br/>
        </div>
   </div>



<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>
