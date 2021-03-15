<?php
    session_start();
    require_once "connect.php";   
?>

<?php include('interface/naglowek.php') ?>

<div id="panel_glowny">
    
<?php

    if($polaczenie)
    {
        error_reporting(0);

        /**
         * przesłanie formularza dodawania zespołu
         */
        if(!empty($_POST['submit_zespol']))
        {
            /**
             * dodawanie zespolu
             * */
            $nazwa_zespolu = $_POST['nowa_nazwa_zespolu'];
            $miasto = $_POST['nowe_miasto'];

            $q_zespol = "SELECT * FROM DodajZespol( '".$nazwa_zespolu."', '".$miasto."')";
            $rezultat = pg_query($polaczenie, $q_zespol);
            $wiersz = pg_fetch_row($rezultat);
            $id_zespol = $wiersz['0'];
            pg_free_result($rezultat);

            if ($rezultat === false) 
            {
                $data = pg_last_error($polaczenie);
                $text = explode(" ", $data);
                if($text[2] == 'duplicate' && $text[3]=='key')
                {
                    echo "<script> window.location.replace('dodaj_zespol.php'); alert('Podana nazwa zespołu jest zajęta!');  </script>";
                    exit;
                }
                else
                {
                    $text = explode(":", $data);
                    echo "<script> window.location.replace('dodaj_zespol.php'); alert('".$text[1]."');  </script>";
                    exit;
                }
            } 

            /**
             * dodawanie zawodnikow
             */
            for($i=1; $i<=7; $i++)
            {
                $imie = $_POST['nowe_imie'.$i];
                $nazwisko = $_POST['nowe_nazwisko'.$i];

                if (!empty($_POST['nowy_wiek'.$i]))
                    $wiek = $_POST['nowy_wiek'.$i];
                else
                    $wiek = 'NULL';
                if (!empty($_POST['nowy_numer'.$i]))
                    $numer = $_POST['nowy_numer'.$i];
                else
                    $numer = 'NULL';
                if($i==1)
                    $pozycja = 1;
                else if($i==2 || $i==3)
                    $pozycja = 2;
                else if($i==4 || $i==5)
                    $pozycja = 3;
                else if($i==6)
                    $pozycja = 4;
                else if($i==7)
                    $pozycja = 5;

                /**
                 * dodawanie nowej narodowosci
                 */
                if($_POST['nowa_nar'.$i]==-1)
                {
                    $nowa_narodowosc_nazwa = $_POST['dodana_nowa_nar'.$i];
                    $q_narodowosc = "SELECT * FROM DodajNarodowosc('".$nowa_narodowosc_nazwa."')";
                    $rezultat = pg_query($polaczenie, $q_narodowosc);
                    $wiersz = pg_fetch_row($rezultat);
                    $narodowosc = $wiersz['0'];
                    pg_free_result($rezultat);

                    if ($rezultat == false) 
                    {
                        $data = pg_last_error($polaczenie);
                        $text = explode(" ", $data);
                        if($text[2] == 'duplicate' && $text[3]=='key')
                        {
                            echo "<script> window.location.replace('dodaj_zespol.php'); alert('Ten kraj jest już w bazie!');  </script>";
                            exit;
                        }
                        else
                        {
                            $text = explode(":", $data);
                            echo "<script> window.location.replace('dodaj_zespol.php'); alert('".$text[1]."');  </script>";
                            exit;
                        }
                    }
                }
                else if($_POST['nowa_nar'.$i]==0)
                    $narodowosc = 'NULL';
                else
                    $narodowosc = $_POST['nowa_nar'.$i];
                
                /**
                 * dodawanie do tabeli osoba
                 */
                $q_osoba = "SELECT * FROM DodajOsoba('".$imie."', '".$nazwisko."', ".$wiek.")";
                $rezultat = pg_query($polaczenie, $q_osoba);
                $wiersz = pg_fetch_row($rezultat);
                $id_osoba = $wiersz['0'];
                pg_free_result($rezultat);

                if ($rezultat === false) 
                {
                    $data = pg_last_error($polaczenie);
                    $text = explode(" ", $data);
                    if($text[2] == 'null' && $text[3]=='value')
                    {
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('Uzupełnij wiek zawodnika ".$i."!');  </script>";
                        exit;
                    }
                    else if($text[2] == 'column' && $text[4] == 'does' && $text[5] == 'not')
                    {
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('Niepoprawny wiek zawodnika ".$i."!');  </script>";
                        exit;
                    }
                    else
                    {
                        $text = explode(":", $data);
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('".$text[1]."');  </script>";
                        exit;
                    }           
                } 
                /**
                 * dodawanie do tabeli zawodnik
                 */
                $q_zawodnik = "SELECT * FROM DodajZawodnik( ".$numer.", ".$id_osoba.", ".$pozycja.", ".$narodowosc.", ".$id_zespol.")";
                $rezultat = pg_query($polaczenie, $q_zawodnik);
                pg_free_result($rezultat);

                if ($rezultat === false) 
                {
                    $data = pg_last_error($polaczenie);
                    $text = explode(" ", $data);
                    if($text[2] == 'null' && $text[3]=='value')
                    {
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('Uzupełnij numer zawodnika ".$i."!');  </script>";
                        exit;
                    }
                    else if($text[2] == 'column' && $text[4] == 'does' && $text[5] == 'not')
                    {
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('Niepoprawny numer zawodnika ".$i."!');  </script>";
                        exit;
                    }
                    else
                    {
                        $text = explode(":", $data);
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('".$text[1]."');  </script>";
                        exit;
                    }           
                } 
            }                             
            /**
            * dodawanie trenerów
            */
            for($i=1; $i<=2; $i++)
            {
                $imie = $_POST['tr_nowe_imie'.$i];
                $nazwisko = $_POST['tr_nowe_nazwisko'.$i];
                if (!empty($_POST['tr_nowy_wiek'.$i]))
                    $wiek = $_POST['tr_nowy_wiek'.$i];
                else
                    $wiek = 'NULL';

                $funkcja = $_POST['tr_nowa_funkcja'.$i];

                /**
                 * dodawanie nowej narodowosci
                 */
                if($_POST['tr_nowa_nar'.$i] == -1)
                {
                    $j = $i+7;
                    $nowa_narodowosc_nazwa = $_POST['dodana_nowa_nar'.$j];
                    $q_narodowosc = "SELECT * FROM DodajNarodowosc('".$nowa_narodowosc_nazwa."')";
                    $rezultat = pg_query($polaczenie, $q_narodowosc);
                    $wiersz = pg_fetch_row($rezultat);
                    $narodowosc = $wiersz['0'];
                    pg_free_result($rezultat);

                    if ($rezultat == false) 
                    {
                        $data = pg_last_error($polaczenie);
                        $text = explode(" ", $data);
                        if($text[2] == 'duplicate' && $text[3]=='key')
                        {
                            echo "<script> window.location.replace('dodaj_zespol.php'); alert('Ten kraj jest już w bazie!');  </script>";
                            exit;
                        }
                        else
                        {
                            $text = explode(":", $data);
                            echo "<script> window.location.replace('dodaj_zespol.php'); alert('".$text[1]."');  </script>";
                            exit;
                        }
                    }
                }
                else if($_POST['tr_nowa_nar'.$i]==0)
                    $narodowosc = 'NULL';
                else
                    $narodowosc = $_POST['tr_nowa_nar'.$i];      

                /**
                 * dodawanie do tabeli osoba
                 */
                $q_osoba = "SELECT * FROM DodajOsoba('".$imie."', '".$nazwisko."', ".$wiek.")";
                $rezultat = pg_query($polaczenie, $q_osoba);
                $wiersz = pg_fetch_row($rezultat);
                $id_osoba = $wiersz['0'];
                pg_free_result($rezultat);

                if ($rezultat === false) 
                {
                    $data = pg_last_error($polaczenie);
                    $text = explode(" ", $data);
                    if($text[2] == 'null' && $text[3]=='value')
                    {
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('Uzupełnij wiek trenera ".$i."!');  </script>";
                        exit;
                    }
                    else if($text[2] == 'column' && $text[4] == 'does' && $text[5] == 'not')
                    {
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('Niepoprawny wiek trenera ".$i."!');  </script>";
                        exit;
                    }
                    else
                    {
                        $text = explode(":", $data);
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('".$text[1]."');  </script>";
                        exit;
                    }           
                } 

                /**
                 * dodawanie do tabeli trener 
                 */
                $q_trener = "SELECT * FROM DodajTrener (".$id_osoba.", ".$narodowosc.", ".$id_zespol.", '".$funkcja."')";
                $rezultat = pg_query($polaczenie, $q_trener);
                pg_free_result($rezultat);

                if ($rezultat === false) 
                {
                    $data = pg_last_error($polaczenie);
                    $text = explode(" ", $data);
                    if($text[2] == 'null' && $text[3]=='value')
                    {
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('Uzupełnij narodowość trenera ".$i."!');  </script>";
                        exit;
                    }
                    else
                    {
                        $text = explode(":", $data);
                        echo "<script> window.location.replace('dodaj_zespol.php'); alert('".$text[1]."');  </script>";
                        exit;
                    }           
                } 
            } 
            echo "<script> window.location.replace('index.php'); alert('Pomyślnie dodano drużynę!');  </script>";
            exit;   
        }

        /**
         * przesłanie formularza dodawania meczu
         */
        else if(!empty($_POST['submit_mecz']))
        {
            /**
            * pobranie id zespolow i wynik meczu
            */
            $id_wygrany = $_POST['zesp1'];
            $id_przegrany = $_POST['zesp2'];

            if($id_wygrany==0 || $id_przegrany==0)
            {
                echo "<script> window.location.replace('dodaj_mecz.php'); alert('Wybierz drużyny!');  </script>";
                exit;
            }

            if($id_wygrany != $id_przegrany)
            {
                /**
                 * dodawanie kolejki
                 */
                if($_POST['kol'] == -1)
                {   
                    /**
                     * dodanie do tabeli kolejka
                     */ 
                    $nowa_kol_data = $_POST['dodana_nowa_kol'];
                    $q_kolejka = "SELECT * FROM DodajKolejka ('".$nowa_kol_data."')";
                    $rezultat = pg_query($polaczenie, $q_kolejka);       
                    $wiersz = pg_fetch_row($rezultat);
                    $id_kolejka = $wiersz['0'];       
                    pg_free_result($rezultat);

                    if ($rezultat === false) 
                    {
                        $data = pg_last_error($polaczenie);
                        $text = explode(" ", $data);
                        if($text[2] == 'duplicate' && $text[3]=='key')
                        {
                            echo "<script> window.location.replace('dodaj_mecz.php'); alert('Tego dnia została już dodana kolejka!');  </script>";
                            exit;
                        }
                        else if($text[2] == 'invalid' && $text[3]=='input')
                        {
                            echo "<script> window.location.replace('dodaj_mecz.php'); alert('Niepoprawny format daty!');  </script>";
                            exit;
                        }
                        else
                        {
                            $text = explode(":", $data);
                            echo "<script> window.location.replace('dodaj_mecz.php'); alert('".$text[1]."');  </script>";
                            exit;
                        }
                    } 
                }
                else if($_POST['kol'] == 0)
                    $id_kolejka = 'NULL';
                else
                    $id_kolejka = $_POST['kol'];

                if($_POST['wynik']==1)
                    $wynik = '3:0';
                else if($_POST['wynik']==2)
                    $wynik = '3:1';
                else if($_POST['wynik']==3)
                    $wynik = '3:2';
                
                /**
                 * sprawdzenie, czy zespół wygrany grał już mecz w zadanej kolejce
                 */
                $q_sprawdz_wygrany = "SELECT * FROM sprawdzmeczkolejka(".$id_wygrany.",".$id_kolejka.")";
                if($rezultat1 = pg_query($polaczenie, $q_sprawdz_wygrany))
                    $ile1=pg_num_rows($rezultat1);
                
                /**
                 * sprawdzenie, czy zespół przegrany grał już mecz w zadanej kolejce
                 */
                $q_sprawdz_przegrany = "SELECT * FROM sprawdzmeczkolejka(".$id_przegrany.",".$id_kolejka.")";
                if($rezultat2 = pg_query($polaczenie, $q_sprawdz_przegrany))
                    $ile2=pg_num_rows($rezultat1);

                if($ile1 == 0 && $ile2 == 0)
                {
                    /**
                     * dodanie meczu
                     */
                    $q_mecz = "SELECT * FROM DodajMecz('".$wynik."', ".$id_kolejka.")";
                    $rezultat = pg_query($polaczenie, $q_mecz);
                    $wiersz = pg_fetch_row($rezultat);
                    $id_mecz = $wiersz['0'];
                    pg_free_result($rezultat);

                    if ($rezultat === false) 
                    {
                        $data = pg_last_error($polaczenie);
                        $text = explode(":", $data);
                        echo "<script> window.location.replace('dodaj_mecz.php'); alert('".$text[1]."');  </script>"; 
                        exit;   
                    } 

                    /**
                     * dodanie zwyciezcy w danym meczu do tabeli mecz_zespol
                     */
                    $q_mecz_zespol = "SELECT * FROM DodajMeczZespol (".$id_mecz.",".$id_wygrany.",'wygrany')";
                    $rezultat = pg_query($polaczenie, $q_mecz_zespol);
                    pg_free_result($rezultat);

                    /**
                     * dodanie przegranego w danym meczu do tabeli mecz_zespol
                     */
                    $q_mecz_zespol = "SELECT * FROM DodajMeczZespol (".$id_mecz.",".$id_przegrany.",'przegrany')";
                    $rezultat = pg_query($polaczenie, $q_mecz_zespol);
                    pg_free_result($rezultat);

                    /**
                     * wybieranie sedziów
                     */
                    for($i=1; $i<=2; $i++)
                    {
                        /**
                         * dodawanie nowego sędziego do bazy
                         */
                        if($_POST['sedzia'.$i] == -1)
                        {
                            $imie = $_POST['nowy_sedzia'.$i.'_imie'];
                            $nazwisko = $_POST['nowy_sedzia'.$i.'_nazwisko'];
                            if (!empty($_POST['nowy_sedzia'.$i.'_wiek']))
                                $wiek = $_POST['nowy_sedzia'.$i.'_wiek'];
                            else
                                $wiek = 'NULL';
                        
                            /**
                             * dodawanie do tabeli osoba
                             */
                            $q_osoba = "SELECT * FROM DodajOsoba('".$imie."', '".$nazwisko."', ".$wiek.")";
                            $rezultat = pg_query($polaczenie, $q_osoba);
                            $wiersz = pg_fetch_row($rezultat);
                            $id_osoba = $wiersz['0'];
                            pg_free_result($rezultat);
                            
                            if ($rezultat === false) 
                            {
                                $data = pg_last_error($polaczenie);
                                $text = explode(" ", $data);
                                if($text[2] == 'null' && $text[3]=='value')
                                {
                                    echo "<script> window.location.replace('dodaj_mecz.php'); alert('Uzupełnij wiek sędziego ".$i."!');  </script>";
                                    exit;
                                }    
                                else if($text[2] == 'column' && $text[4] == 'does' && $text[5] == 'not')
                                {
                                    echo "<script> window.location.replace('dodaj_mecz.php'); alert('Niepoprawny wiek sędziego ".$i."!');  </script>";
                                    exit;
                                }    
                                else
                                {
                                    $text = explode(":", $data);
                                    echo "<script> window.location.replace('dodaj_mecz.php'); alert('".$text[1]."');  </script>";
                                    exit;
                                }           
                            } 

                            /**
                             * dodawanie do tabeli sędzia
                             */
                            $q_sedzia = "SELECT * FROM DodajSedzia(".$id_osoba.")";
                            $rezultat = pg_query($polaczenie, $q_sedzia);
                            $wiersz = pg_fetch_row($rezultat);
                            $id_sedzia = $wiersz['0'];
                            pg_free_result($rezultat);
                        }
                        else if ($_POST['sedzia'.$i]==0)
                            $id_sedzia == 'NULL';
                        else
                            $id_sedzia = $_POST['sedzia'.$i];
                        
                        /**
                         * dodawanie sędziego danego meczu do tabeli mecz_sedzia
                         */
                        $q_mecz_sedzia = "SELECT * FROM DodajMeczSedzia (".$id_mecz.",".$id_sedzia.")";
                        $rezultat = pg_query($polaczenie, $q_mecz_sedzia);
                        pg_free_result($rezultat);   
                        
                        if ($rezultat === false) 
                        {
                            $data = pg_last_error($polaczenie);
                            $text = explode(" ", $data);
                            if($text[2] == 'null' && $text[3]=='value')
                            {
                                echo "<script> window.location.replace('dodaj_mecz.php'); alert('Wybierz sędziego ".$i."!');  </script>";
                                exit;
                            }
                            else
                            {
                                $text = explode(":", $data);
                                echo "<script> window.location.replace('dodaj_mecz.php'); alert('".$text[1]."');  </script>";
                                exit;
                            }           
                        } 
                    }   
                    
                    /**
                     * wybor sponsorów
                     */
                    for($i=1; $i<=2; $i++)
                    {
                        if($_POST['sponsor'.$i] == -1)
                        {
                            $nazwa = $_POST['nowy_sponsor'.$i.'_nazwa'];
                            $branza = $_POST['nowy_sponsor'.$i.'_branza'];

                            /**
                             * dodawanie nowego sponsora do bazy
                             */
                             $q_sponsor = "SELECT * FROM DodajSponsor('".$nazwa."', '".$branza."')";
                            $rezultat = pg_query($polaczenie, $q_sponsor);
                            $wiersz = pg_fetch_row($rezultat);
                            $id_sponsor = $wiersz['0'];
                            pg_free_result($rezultat);

                            if ($rezultat === false) 
                            {
                                $data = pg_last_error($polaczenie);
                                $text = explode(" ", $data);
                                if($text[2] == 'duplicate' && $text[3]=='key')
                                {
                                    echo "<script> window.location.replace('dodaj_mecz.php'); alert('Podany sponsor jest już w bazie!');  </script>";
                                    exit;
                                }
                                else
                                {
                                    $text = explode(":", $data);
                                    echo "<script> window.location.replace('dodaj_mecz.php'); alert('".$text[1]."');  </script>";
                                    exit;
                                }  
                            } 
                        }
                        else if($_POST['sponsor'.$i]==0)
                            $id_sponsor = 'NULL';
                        else
                            $id_sponsor = $_POST['sponsor'.$i];        
                            
                        /**
                         * dodawanie sponsora danego meczu do tabeli mecz_sponsor
                         */
                        $q_mecz_sponsor = "SELECT * FROM DodajMeczSponsor (".$id_mecz.",".$id_sponsor.")";
                        $rezultat = pg_query($polaczenie, $q_mecz_sponsor);
                        pg_free_result($rezultat);     
                        
                        if ($rezultat === false) 
                        {
                            $data = pg_last_error($polaczenie);
                            $text = explode(" ", $data);
                            if($text[2] == 'null' && $text[3]=='value')
                            {
                                echo "<script> window.location.replace('dodaj_mecz.php'); alert('Wybierz sponsora ".$i."!');  </script>";
                                exit;
                            }
                            else
                            {
                                $text = explode(":", $data);
                                echo "<script> window.location.replace('dodaj_mecz.php'); alert('".$text[1]."');  </script>";
                                exit;
                            }           
                        } 
                    }  
                }
                else
                {
                    echo "<script> window.location.replace('dodaj_mecz.php'); alert('Ta drużyna grała już mecz w tej kolejce');  </script>"; 
                    exit;
                }
            }
            else
            {    
                echo "<script> window.location.replace('dodaj_mecz.php'); alert('Podaj dwie różne drużyny!');  </script>";
                exit;
            }

            echo "<script> window.location.replace('index.php'); alert('Mecz został dodany!');  </script>"; 
            exit;
        }
        /**
        * przesłanie formularza rejestracji
        */
        else if(!empty($_POST['submit_rejestracja']))
        {
            $login = $_POST['login'];
            $imie = $_POST['imie'];
            $nazwisko = $_POST['nazwisko'];
            if(!empty($_POST['wiek']))
                $wiek = $_POST['wiek'];
            else
                $wiek = 'NULL';
            if(!empty($_POST['haslo']) && !empty($_POST['haslo2']) && $_POST['haslo'] == $_POST['haslo2'])
                $hash_haslo = md5($_POST['haslo']);
            else
                $hash_haslo = 'NULL';
                
            if($_POST['zespol'] != "0")
                $id_zespol = $_POST['zespol'];
            else
                $id_zespol = 'NULL';
            /**
             * dodawanie do tabeli osoba
             */
            $q_osoba = "SELECT * FROM DodajOsoba('".$imie."', '".$nazwisko."', ".$wiek.")";
            $rezultat = pg_query($polaczenie, $q_osoba);
            $wiersz = pg_fetch_row($rezultat);
            $id_osoba = $wiersz['0'];
            pg_free_result($rezultat);

            if ($rezultat === false) 
            {
                $data = pg_last_error($polaczenie);
                $text = explode(" ", $data);
                if($text[2] == 'null' && $text[3]=='value')
                    echo "<script> window.location.replace('rejestracja.php'); alert('Podaj wiek!');  </script>";
                else if($text[2] == 'column' && $text[4] == 'does' && $text[5] == 'not')
                    echo "<script> window.location.replace('rejestracja.php'); alert('Niepoprawny wiek!');  </script>";
                else
                {
                    $text = explode(":", $data);
                    echo "<script> window.location.replace('rejestracja.php'); alert('".$text[1]."');  </script>";
                }
            } 
            /**
             * dodawanie do tabeli kibic
             */
            $q_osoba = "SELECT * FROM DodajKibic( ".$id_osoba.", ".$id_zespol.", '".$login."', '".$hash_haslo."')";
            $rezultat = pg_query($polaczenie, $q_osoba);
            pg_free_result($rezultat);

            if ($rezultat == false) 
            {
                $data = pg_last_error($polaczenie);
                $text = explode(" ", $data);
                if($text[2] == 'duplicate' && $text[3]=='key')
                    echo "<script> window.location.replace('rejestracja.php'); alert('Podany login jest zajęty!');  </script>";
                else
                {
                    $text = explode(":", $data);
                    echo "<script> window.location.replace('rejestracja.php'); alert('".$text[1]."');  </script>";
                }
            }
            else
            {
                header('Location: logowanie.php');
            } 
        }
        /**
         * przesłanie formularza logowania
         */
        else if(!empty($_POST['submit_logowanie']))
        {
            $login = $_POST['login'];
            $hash_haslo = md5($_POST['haslo']);

            $q = "SELECT * FROM logowanie('".$login."', '".$hash_haslo."') ";
            
            if($rezultat=pg_query($polaczenie, $q))
            {
                $wiersz = pg_fetch_row($rezultat);
                $id_osoba = $wiersz['0'];

                $ile=pg_num_rows($rezultat);

                if($ile>0)
                {                    
                    $_SESSION['zalogowany'] = true;
                    header('Location: index.php');
                }
                else
                {
                    echo "<script> window.location.replace('logowanie.php'); alert('Podano błędny login lub hasło!');  </script>";
                    exit;
                }         
                pg_free_result($rezultat);
            }
   
        }
        /**
         * przesłanie formularza wylogowania
         */
        else if(!empty($_POST['submit_wyloguj']))
        {
            $_SESSION['zalogowany'] = false;
            header('Location: index.php');
            exit();
        }
        else
        {
            header('Location: index.php');
            exit();
        }
        pg_close($polaczenie);
    }


?>

   </div>


<?php include('interface/panel_boczny.php') ?>				
<?php include('interface/stopka.php') ?>
