var xmlHttp;

function getRequestObject()      
{
    if ( window.ActiveXObject)  
    {
        return ( new ActiveXObject("Microsoft.XMLHTTP")) ;
    } 
    else if (window.XMLHttpRequest)  
    {
        return (new XMLHttpRequest())  ;
    } 
    else 
    {
        return (null) ;
    } 
}

function SortowanieKibice($val)
{   
    xmlhttp = getRequestObject();

    if(xmlhttp)
    {
        try
        {
            xmlhttp.onreadystatechange = handleSortowanieKibice;
            xmlhttp.open("GET", "wyszukaj.php?opt=1&val=" + $val, true);
            xmlhttp.send(null);
        }
        catch (e) 
        {
            alert ("Nie mozna polaczyc sie z serwerem: " + e.toString()) ;
        }
    }
    else
    {
        alert("blad");
    }
}

function handleSortowanieKibice()
{
    if(xmlhttp.readyState == 4)
    {
        if(xmlhttp.status == 200)
        {
            var wyn = "<table class='tabelka'>";
            wyn += "<th> LOGIN </th><th> IMIĘ </th><th> NAZWISKO </th><th> WIEK </th><th> ZESPÓŁ </th>";
            wyn += xmlhttp.responseText;
            wyn += "</table>";
            document.getElementById("DivKibice").innerHTML = wyn;
        }
        else
            alert ('wystapil blad');
    }
}

function WyborZawodnicy()
{   
    var zespol = document.getElementById("wybor_zespol").value;
    var pozycja = document.getElementById("wybor_pozycja").value;
    var narodowosc = document.getElementById("wybor_kraj").value;
    var sortowanie = document.getElementById("sortowanie").value;

    xmlhttp = getRequestObject();

    if(xmlhttp)
    {
        try
        {
            var url = "wyszukaj.php?opt=2&zespol="+zespol+"&pozycja="+pozycja+"&narodowosc="+narodowosc+"&sortowanie=" + sortowanie;
            xmlhttp.onreadystatechange = handleWyborZawodnicy;
            xmlhttp.open("GET", url, true);
            xmlhttp.send(null);
        }
        catch (e) 
        {
            alert ("Nie mozna polaczyc sie z serwerem: " + e.toString()) ;
        }
    }
    else
    {
        alert("blad");
    }
}

function handleWyborZawodnicy()
{
    if(xmlhttp.readyState == 4)
    {
        if(xmlhttp.status == 200)
        {
            var wyn = "<table class='tabelka'>";
            wyn += "<th> NAZWISKO </th> <th> IMIĘ </th> <th> WIEK </th> <th> POZYCJA </th> <th> NUMER </th> <th> NARODOWOŚĆ </th> <th> KLUB </th>";
            wyn += xmlhttp.responseText;
            wyn += "</table>";
            document.getElementById("DivZawodnicy").innerHTML = wyn;
        }
        else
            alert ('wystapil blad');
    }
   
}

function WyborZespoly()
{   
    var zespol = document.getElementById("wybor_zespol").value;
    var trener = document.getElementById("wybor_trener").value;
    var sortowanie = document.getElementById("sortowanie").value;

    xmlhttp = getRequestObject();

    if(xmlhttp)
    {
        try
        {
            var url = "wyszukaj.php?opt=3&zespol="+zespol+"&trener="+trener+"&sortowanie=" + sortowanie;
            xmlhttp.onreadystatechange = handleWyborZespoly;
            xmlhttp.open("GET", url, true);
            xmlhttp.send(null);
        }
        catch (e) 
        {
            alert ("Nie mozna polaczyc sie z serwerem: " + e.toString()) ;
        }
    }
    else
    {
        alert("wystapil blad");
    }
}

function handleWyborZespoly()
{
    if(xmlhttp.readyState == 4)
    {
        if(xmlhttp.status == 200)
        {
            
            var wyn = xmlhttp.responseText;
            document.getElementById("DivZespoly").innerHTML = wyn;
        }
        else
            alert ('wystapil blad');
    }
}

function WyborMecze()
{   
    var kolejka = document.getElementById("wybor_kolejka").value;
    var zespol = document.getElementById("wybor_zespol").value;

    xmlhttp = getRequestObject();

    if(xmlhttp)
    {
        try
        {
            var url = "wyszukaj.php?opt=4&kolejka="+kolejka+"&zespol="+zespol;
            xmlhttp.onreadystatechange = handleWyborMecze;
            xmlhttp.open("GET", url, true);
            xmlhttp.send(null);
        }
        catch (e) 
        {
            alert ("Nie mozna polaczyc sie z serwerem: " + e.toString()) ;
        }
    }
    else
    {
        alert("wystapil blad");
    }
}

function handleWyborMecze()
{
    if(xmlhttp.readyState == 4)
    {
        if(xmlhttp.status == 200)
        {
            
            var wyn = xmlhttp.responseText;
            document.getElementById("DivMecze").innerHTML = wyn;
        }
        else
            alert ('wystapil blad');
    }
}

function DodajNarodowosc($i, $val)
{
    if($val==-1)
        document.getElementById("div_nar"+$i).innerHTML='<input type="text" name="dodana_nowa_nar'+$i+'" id="dodana_nowa_nar'+$i+'" placeholder="Wprowadź nazwę kraju"/>';

}   

function DodajKolejke($val)
{
    if($val==-1)
    {
        document.getElementById("div_kol").style.display='inline';
        document.getElementById("div_kol").innerHTML='<input type="text" name="dodana_nowa_kol" id="dodana_nowa_kol" placeholder="Wprowadź datę kolejki (YYYY-MM-DD)"/>';
    }
    else
        document.getElementById("div_kol").style.display='none';
}   

function DodajSedziego($i,$val)
{    
    if($val==-1)
    {
        document.getElementById("div_sedzia"+$i).style.display='inline';
        
        var imie = '<input type="text" name="nowy_sedzia'+$i+'_imie" id="nowy_sedzia'+$i+'_imie" placeholder="Imię"/>';
        var nazwisko = '<input type="text" name="nowy_sedzia'+$i+'_nazwisko" id="nowy_sedzia'+$i+'_nazwisko" placeholder="Nazwisko"/>';
        var wiek = '<input type="text" name="nowy_sedzia'+$i+'_wiek" id="nowy_sedzia'+$i+'_wiek" placeholder="Wiek"/>';
        

        var nowy_div = imie+nazwisko+wiek;
        document.getElementById("div_sedzia"+$i).innerHTML=nowy_div;
    }
    else
        document.getElementById("div_sedzia"+$i).style.display='none';
}   

function DodajSponsora($i,$val)
{
    if($val==-1)
    {
        document.getElementById("div_sponsor"+$i).style.display='inline';
        var nazwa = '<input type="text" name="nowy_sponsor'+$i+'_nazwa" id="nowy_sponsor'+$i+'_nazwa" placeholder="Nazwa"/>';
        var branza = '<input type="text" name="nowy_sponsor'+$i+'_branza" id="nowy_sponsor'+$i+'_branza" placeholder="Branża"/>';
            
        var nowy_div = nazwa+branza;
        document.getElementById("div_sponsor"+$i).innerHTML=nowy_div;
    }
    else
        document.getElementById("div_sponsor"+$i).style.display='none';
}        

function PokazInformacje(tekst)
{
    document.getElementById("info").innerHTML = tekst;
}

function ustawslajd(nrslajdu)
{
    clearTimeout(timer1);
    clearTimeout(timer2);
    numer = nrslajdu - 1;
            
    schowaj();
    setTimeout("zmienslajd()", 500);
}
        
function schowaj()
{
    $("#slajdy").fadeOut(500);
}
    
function zmienslajd()
{
    numer++; if (numer>5) numer=1;
            
    var plik = "<img src=\"photos/slajdy/slajd" + numer + ".jpg\" />";
            
    document.getElementById("slajdy").innerHTML = plik;
    $("#slajdy").fadeIn(500);
            
    timer1 = setTimeout("zmienslajd()", 5000);
    timer2 = setTimeout("schowaj()", 4500);
}