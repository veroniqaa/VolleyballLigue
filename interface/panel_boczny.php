
<div id="panel_boczny">
    <div>

<?php
        if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
        {
            echo '<a href="dodaj_zespol.php"> <div class="panel_boczny_button"><p> DODAJ ZESPÓŁ</p> </div></a>';
            echo '<a href="dodaj_mecz.php"> <div class="panel_boczny_button"><p> DODAJ MECZ</p> </div></a>';
            echo '<form action="dodaj.php" method="POST">';
            echo '<input type="submit" name="submit_wyloguj" value="WYLOGUJ SIĘ" class="panel_boczny_button"/>';
            echo '</form>';
        }
        else
        {
            echo '<a href="logowanie.php"> <div class="panel_boczny_button"><p> ZALOGUJ SIĘ</p> </div></a>';
            echo '<a href="rejestracja.php"> <div class="panel_boczny_button"><p> ZAREJESTRUJ SIĘ</p> </div></a>';
        }					               
?>         

	</div>
</div>