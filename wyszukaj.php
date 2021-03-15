 
 <?php

    session_start();
    require_once "connect.php";

    $opt = $_REQUEST["opt"];

    /**
     * zmiana zadanych parametrów filtrowania w wyszukiwar kibicow
     */
    if($opt == 1)
    {
        $sortowanie = $_REQUEST["val"];
 
        if($polaczenie)
        {
            /**
             * zwracanie wszystkich kibiców z bazy w zadanej kolejności sortowania
             */
            $q = "SELECT * FROM SzukajKibica (".$sortowanie.")";

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
    }
    /**
     * zmiana zadanych parametrów filtrowania w wyszukiwarce zawodników
     */
    else if($opt == 2)
    {
        $zespol = $_REQUEST["zespol"];
        $pozycja = $_REQUEST["pozycja"];
        $narodowosc = $_REQUEST["narodowosc"];
        $sortowanie = $_REQUEST["sortowanie"];

        if($polaczenie)
        {
            /**
             * zwracanie wszystkich zawodników z bazy spełniających zadane filtry w określonej kolejności sortowania
             */
            $q = "SELECT * FROM SzukajZawodnika(".$zespol.", ".$pozycja.", ".$narodowosc.", ".$sortowanie.")";

            if($rezultat=pg_query($polaczenie, $q))
            {
                $ile=pg_num_rows($rezultat);

                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo "<tr> <td>".$wiersz['nazwisko']."</td> <td>".$wiersz['imie']."</td> <td>".$wiersz['wiek']."</td> <td>".$wiersz['nazwa_pozycja']."</td> <td>".$wiersz['numer']."</td> <td>".$wiersz['kraj']."</td> <td>".$wiersz['nazwa_zespolu']."</td> </tr>";
                }
                pg_free_result($rezultat);
            }
            else
            {
                echo "Nic nie znalazłam";
            }

            pg_close($polaczenie);
        }
    }
    /**
     * zmiana zadanych parametrów filtrowania w wyszukiwarce zespołów
     */
    else if($opt == 3)
    {
        $zespol = $_REQUEST["zespol"];
        $trener = $_REQUEST["trener"];
        $sortowanie = $_REQUEST["sortowanie"];

        if($polaczenie)
        {
            /**
             * zwracanie wszystkich drużyn z bazy spełniających zadane filtry w określonej kolejności sortowania
             */
            $q = "SELECT * FROM SzukajDruzyne (".$zespol.",".$trener.",".$sortowanie.");";

            if($rezultat=pg_query($polaczenie, $q))
            {
                $ile=pg_num_rows($rezultat);

                while($wiersz=pg_fetch_assoc($rezultat))
                {
                    echo "<div id='DivZespoly'>";
                    echo "<div class='zespol'>";
                    echo "<span class='nazwa_zespolu'>".$wiersz['nazwa_zespolu']."</span></br></br>";
                    echo "<div class='zespol_dane'>";
                    echo "<b>Miasto: </b>".$wiersz['miasto']."</br>";
                    
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
                    echo "</div></div></div>";
                }
                pg_free_result($rezultat);

                if($ile == 0)
                    echo "<p>Brak wyników spełniających kryteria.</p>";
            }
            pg_close($polaczenie);
        }
    }
    /**
     * zmiana zadanych parametrów filtrowania w wyszukiwarce meczów
     */
    else if($opt == 4)
    {
        $kolejka = $_REQUEST["kolejka"];
        $zespol = $_REQUEST["zespol"];
        
        if($polaczenie)
        {
            /**
             * zwracanie meczu rozegranego przez zadaną drużynę (lub wszystkie, gdy nie wybrano zespołu) w zadanej kolejce 
             */
            $q = "SELECT * FROM SzukajMecz ( ".$kolejka.", ".$zespol." ) ";

            if($rezultat=pg_query($polaczenie, $q))
            {
                $ile=pg_num_rows($rezultat);

                echo "<div id='DivMecze'>";
                
                /**
                 * SelectBox z wszystkimi kolejkami z bazy do wyboru
                 */
                $q2 = "SELECT * FROM wybor_kolejki(".$kolejka.")";
                                
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
                        $q2 = "SELECT * FROM zespol_w_meczu (".$wiersz['id_mecz'].", 'wygrany')"; 
                        
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
                        $q2 = "SELECT * FROM zespol_w_meczu (".$wiersz['id_mecz'].", 'przegrany')"; 
                                                
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
                        $q2 = " SELECT * FROM sedziowie_w_meczu(".$wiersz['id_mecz'].")";
                        
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
                         * zwracanie sponsorów danego mecz
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
    }
    else
    {
        header('Location: index.php');
        exit();
    }
   
?>