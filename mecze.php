<?php

    session_start();

    require_once "connect.php";
?>

<?php include('interface/naglowek.php') ?>

   <div id="panel_glowny">

    <div class="formularz">
        
<?php

    if($polaczenie)
    {
        /**
         * SelectBox z wszystkimi kolejkami z bazy do wyboru
         */
        $q="SELECT * FROM wszystkie_kolejki";
        if($rezultat=pg_query($polaczenie, $q))
        {
            echo('<select class="select" id="wybor_kolejka" onchange="WyborMecze();">');

            while($wiersz=pg_fetch_assoc($rezultat))
            {
                $konw_data = strtotime($wiersz['data']);
                $wiersz['data'] = date('d.m.Y', $konw_data);
                echo('<option class="option" value="'.$wiersz['id_kolejka'].'"> KOLEJKA '.$wiersz['id_kolejka'].' - '.$wiersz['data'].'</option>');
            }
            echo('</select>');

            pg_free_result($rezultat);
        }   

        /**
         * SelectBox z wszystkimi zespołami z bazy do wyboru
         */
        $q="SELECT * FROM wszystkie_zespoly";
        if($rezultat=pg_query($polaczenie, $q))
        {
            echo('<select class="select" id="wybor_zespol" onchange="WyborMecze();">');
            echo('<option class="option" value="0" >--- Wybierz zespół ---</option>');

            while($wiersz=pg_fetch_assoc($rezultat))
            {
                echo('<option class="option" value="'.$wiersz['id_zespol'].'">'.$wiersz['nazwa_zespolu'].'</option>');
            }
            echo('</select>');

            pg_free_result($rezultat);
        }     
        
        echo ('</div>');

        /**
         * zwracanie wszystkich meczów z pierwszej kolejki
         */
        $q = " SELECT * FROM SzukajMecz (1,0)";

        if($rezultat=pg_query($polaczenie, $q))
        {
           $ile=pg_num_rows($rezultat);

           echo "<div id='DivMecze'>";
        
           /**
            * zwracanie informacji na temat pierwszej kolejki
            */
           $q2 = "SELECT * FROM wybor_kolejki(1)";
           
           if($rezultat2 = pg_query($polaczenie, $q2))
           {
               while($wiersz2=pg_fetch_assoc($rezultat2))
               {
                   $konw_data = strtotime($wiersz2['data']);
                   $wiersz2['data'] = date('d.m.Y', $konw_data);
                    echo "<div id='tytul'> KOLEJKA ".$wiersz2['id_kolejka']." - ".$wiersz2['data']." </div>";
               }
               pg_free_result($rezultat2);
           }

           while($wiersz=pg_fetch_assoc($rezultat))
           {
                echo "<div class='mecz'>";

                    /**
                     * zwracanie zespołu zwycięskiego w danym meczu
                     */
                    $q2 = "SELECT * FROM zespol_w_meczu(".$wiersz['id_mecz'].", 'wygrany')";
                    
                    if($rezultat2 = pg_query($polaczenie, $q2))
                    {
                        while($wiersz2=pg_fetch_assoc($rezultat2))
                        {
                            echo "<div class='wygrany'>".$wiersz2['nazwa_zespolu']."</div>";
                        }
                        pg_free_result($rezultat2);
                    }

                    /**
                     * zwracanie wyniku danego meczu
                     */
                    echo "<div class='wynik'> ".$wiersz['wynik']." </div>";
                    
                    /**
                     * zwracanie zespołu przegranego w danym meczu
                     */
                    $q2 = "SELECT * FROM zespol_w_meczu(".$wiersz['id_mecz'].", 'przegrany')";
                    
                    if($rezultat2 = pg_query($polaczenie, $q2))
                    {
                        while($wiersz2=pg_fetch_assoc($rezultat2))
                        {
                            echo "<div class='przegrany'>".$wiersz2['nazwa_zespolu']."</div>";
                        }
                        pg_free_result($rezultat2);
                    }
                    echo "<div id='tabelki'>";

                    /**
                     * zwracanie sędziów, którzy sędziowali dany mecz
                     */
                    $q2 = " SELECT * FROM sedziowie_w_meczu (".$wiersz['id_mecz'].")";
                    
                    if($rezultat2 = pg_query($polaczenie, $q2))
                    {   
                        echo "<table class='mini_tabelka' >";
                        echo "<th colspan=5> SĘDZIOWIE </th>";
                        while($wiersz2=pg_fetch_assoc($rezultat2))
                        {
                            echo "<tr> <td>".$wiersz2['nazwisko']." ".$wiersz2['imie']."</td> <td> ".$wiersz2['wiek']." lat </td> </tr>";
                        }
                        echo "</table>";
                        pg_free_result($rezultat2);
                    }

                    /**
                     * zwracanie sponsorów danego meczu
                     */
                    $q2 = " SELECT * FROM sponsorzy_w_meczu (".$wiersz['id_mecz'].")";
                    
                    if($rezultat2 = pg_query($polaczenie, $q2))
                    {
                        echo "<table class='mini_tabelka' >";
                        echo "<th colspan=5> SPONSORZY </th>";
                        while($wiersz2=pg_fetch_assoc($rezultat2))
                        {
                            echo "<tr> <td>".$wiersz2['nazwa']."</td> <td> ".$wiersz2['branza']."</td> </tr>";
                        }
                        echo "</table>";
                        pg_free_result($rezultat2);
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
