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

    <div id="tytul"> NOWY MECZ </div>
    
    <div class="formularz">
    <form action="dodaj.php" method="post">
     
    <?php
      
      if($polaczenie)
      {
        /**
         * SelectBox z wszystkimi kolejkami z bazy do wyboru
         */
          $q="SELECT * FROM wszystkie_kolejki";
          if($rezultat=pg_query($polaczenie, $q))
          {     
              echo('<select class="select" name="kol" onchange="DodajKolejke(this.value);">');
              echo('<option class="option" value="0" >--- Wybierz kolejkę ---</option>');
  
              while($wiersz=pg_fetch_assoc($rezultat))
              {
                  echo('<option class="option" value="'.$wiersz['id_kolejka'].'">'.$wiersz['data'].'</option>');
              }
            /**
             * opcja w SelectBox, umożliwiajaca dodanie nowej kolejki do bazy
             */
              echo('<option class="option" value="-1"> Dodaj kolejkę </option>');
              echo('</select>');
              echo('<span id="div_kol"></span>');
  
              pg_free_result($rezultat);
          }
  
          echo "<br/><br/>";
  
        /**
         * wybieranie zespołów pomiędzy którymi rozegrany został mecz
         */
          for($i=1; $i<=2; $i++)
          {
            /**
             * SelectBox z wszystkimi meczami z bazy do wyboru
             */
              $q="SELECT * FROM wszystkie_zespoly";
              if($rezultat=pg_query($polaczenie, $q))
              {
                  echo('<select class="select" name="zesp'.$i.'">');
                  if($i == 1)
                      echo('<option class="option" value="0" >--- Wybierz zwycięzce ---</option>');
                  if($i == 2)
                      echo('<option class="option" value="0" >--- Wybierz przegranego ---</option>');
                  while($wiersz=pg_fetch_assoc($rezultat))
                  {
                      echo('<option class="option" value="'.$wiersz['id_zespol'].'">'.$wiersz['nazwa_zespolu'].'</option>');
                  }
                  echo('</select>');
  
                  pg_free_result($rezultat);
              }
          }
  
          echo "<br/><br/>";
  
        /**
         * SelectBox z wszystkimi możliwymi wynikami końcowymi do wyboru
         */
          echo('<select class="select" name="wynik">');
          echo('<option class="option" value="0" >--- Wynik ---</option>');
          echo('<option class="option" value="1"> 3:0 </option>');
          echo('<option class="option" value="2"> 3:1 </option>');
          echo('<option class="option" value="3"> 3:2 </option>');
          echo('</select>');
  
          echo "<br/><br/>";
  
        /**
         * wybieranie sędziów, sędziujących dany mecz
         */
          for($i=1; $i<=2; $i++)
          {
              $q="SELECT * FROM wszyscy_sedziowie";
              if($rezultat=pg_query($polaczenie, $q))
              {
                  echo('<select class="select" name="sedzia'.$i.'" onchange="DodajSedziego('.$i.', this.value);">');
                  echo('<option class="option" value="0" >--- Wybierz '.$i.'. sedziego ---</option>');
  
                  while($wiersz=pg_fetch_assoc($rezultat))
                  {
                      echo('<option class="option" value="'.$wiersz['id_sedzia'].'">'.$wiersz['imie']." ".$wiersz['nazwisko'].'</option>');
                  }
                /**
                 * opcja w SelectBox, umożliwiajaca dodanie nowego sędziego do bazy
                 */
                  echo('<option class="option" value="-1"> Dodaj sędziego </option>');
                  echo('</select>');
                  echo('<span id="div_sedzia'.$i.'"></span>');
  
                  pg_free_result($rezultat);
              }
              echo "<br/><br/>";       
          }
        /**
         * wybieranie sponsorów danego meczu
         */
          for($i=1; $i<=2; $i++)
          {
              $q="SELECT * FROM wszyscy_sponsorzy";
              if($rezultat=pg_query($polaczenie, $q))
              {
                  echo('<select class="select" name="sponsor'.$i.'" onchange="DodajSponsora('.$i.',this.value);">');
                  echo('<option class="option" value="0" >--- Wybierz '.$i.'. sponsora ---</option>');
  
                  while($wiersz=pg_fetch_assoc($rezultat))
                  {
                      echo('<option class="option" value="'.$wiersz['id_sponsor'].'">'.$wiersz['nazwa']." - ".$wiersz['branza'].'</option>');
                  }
                /**
                 * opcja w SelectBox, umożliwiajaca dodanie nowego sponsora do bazy
                 */
                  echo('<option class="option" value="-1"> Dodaj sponsora </option>');
                  echo('</select>');
                  echo('<span id="div_sponsor'.$i.'"></span>');
  
                  pg_free_result($rezultat);
              }
              echo "<br/><br/>";
          }
  
          pg_close($polaczenie);
      }
            
  ?>

    </br>
        <input type="submit" name="submit_mecz" value="Utwórz" class="search_button"/>

     </form>   
    </div>


   </div>


<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>