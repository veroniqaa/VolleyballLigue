<?php

    session_start();
    
    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	  {
		header('Location: index.php');
		exit();
    }
    
    require_once "connect.php";
?>

<?php include('interface/naglowek.php') ?>


   <div id="panel_glowny">
 
        <div id="info"></div>
        <div id='tytul'> LOGOWANIE </div>
        
        <div class="formularz">

        <form action="dodaj.php" method="post">
        
            <input type="text" name="login" id="login" placeholder="Login"/> </br></br>
            <input type="password" name="haslo" id="haslo" placeholder="Hasło"/> </br></br>

            <input type="submit" name="submit_logowanie" value="Zaloguj" class="search_button"/>

        </form>   
        </div>

   </div>

<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>
