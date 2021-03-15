<?php

    session_start();
    require_once "connect.php";

    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: index.php');
		exit();
	}  
?>

<?php include('interface/naglowek.php') ?>

   <div id="panel_glowny">

        <div id='tytul'> DOŁĄCZ DO GRONA KIBICÓW! </div>
        
        <div class="formularz">

        <form action="dodaj.php" method="post">
        
            <input type="text" name="login" id="login" placeholder="Login"/> </br></br>
            <input type="text" name="imie" id="imie" placeholder="Imię"/> </br></br>
            <input type="text" name="nazwisko" id="nazwisko" placeholder="Nazwisko"/> </br></br>
            <input type="text" name="wiek" id="wiek" placeholder="Wiek"/> </br></br>
            <input type="password" name="haslo" id="haslo" placeholder="Hasło"/> </br></br>
            <input type="password" name="haslo2" id="haslo2" placeholder="Powtórz hasło"/> </br></br>

<?php 

    if($polaczenie)
    {       
        /**
         * SelectBox z wszystkimi zespołami z bazy do wyboru
         */
        $q="SELECT * FROM wszystkie_zespoly";
        if($rezultat=pg_query($polaczenie, $q))
        {
            echo('<select class="select" name="zespol">');
            echo('<option class="option" value="0" >--- Wybierz zespół ---</option>');

            while($wiersz=pg_fetch_assoc($rezultat))
            {
                echo('<option class="option" value="'.$wiersz['id_zespol'].'">'.$wiersz['nazwa_zespolu'].'</option>');
            }
            echo('</select>');

            pg_free_result($rezultat);
        }
       pg_close($polaczenie);
    }
?>

    </br></br>  

            <input type="submit" name="submit_rejestracja" value="Utwórz konto" class="search_button"/>

        </form>   
        </div>
   </div>

<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>
