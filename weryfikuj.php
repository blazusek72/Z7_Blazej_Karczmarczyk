<?php
session_start();
$ipaddress = $_SERVER["REMOTE_ADDR"];
$user=$_POST['user']; // login z formularza
$pass=$_POST['pass']; // has�o z formularza $link = mysqli_connect(localhost, nazwa_usera, has�o_usera, baza_usera); // po��czenie z BD � wpisa� swoje parametry !!!
$_SESSION['login'] = $_POST['user'];
$udane = '0';
$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
$link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if(!$link) { echo"B��d: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obs�uga b��du po��czenia z BD
mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znak�w
$result = mysqli_query($link, "SELECT * FROM users WHERE login='$user'"); // pobranie z BD wiersza, w kt�rym login=login z formularza
$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
if(!$rekord) //Je�li brak, to nie ma u�ytkownika o podanym loginie
{
mysqli_close($link);
echo "Brak uzytkownika o takim loginie !"; // UWAGA nie wy�wietlamy takich podpowiedzi dla haker�w
}
else
{
$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
$rezultat = mysqli_query($polaczenie, "SELECT * FROM logi WHERE login = '$user'") or die ("B��d zapytania do bazy: $dbname");
while ($wiersz = mysqli_fetch_array ($rezultat)){
		$udane = $wiersz[4];
}

if($udane < 2)
{
if($rekord['haslo']==$pass) // czy has�o zgadza si� z BD
{try
	{
		$udane = '0';
		$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
		$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
		if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				if ($polaczenie->query("INSERT INTO logi (login, IP, udane) VALUES ('$user', '$ipaddress', '$udane')"))
					{
						$_SESSION['udanelogowanie']=true;
						$_SESSION['login'] = $_POST['user'];
						header ('Location: wyswietl.php');	
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				
			$polaczenie->close();

			}
			
	}
	catch(Exception $e)
		{
			echo '<span style="color:red;">B��d serwera!</span>';
			echo '<br />Informacja: '.$e;
		}
		
	}
else
{		
		
		$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
		$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
		if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{	++$udane;
				if ($polaczenie->query("INSERT INTO logi (login, IP, udane) VALUES ('$user', '$ipaddress', '$udane')"))
					{
						$_SESSION['udanelogowanie']=false;
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				
			$polaczenie->close();

		}
		
mysqli_close($link);
echo "Bledne haslo!"; // UWAGA nie wy�wietlamy takich podpowiedzi dla haker�w
}
}
else{
	$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	$rezultat2 = mysqli_query($polaczenie, "SELECT * FROM logi WHERE login = '$user'") or die ("B��d zapytania do bazy: $dbname");
	while ($wiersz = mysqli_fetch_array ($rezultat2)){
	$czas = $wiersz[3];
	}
	$dattTime = new DateTime();
	$dateTime2 = new DateTime($czas);
	$interval = $dattTime->diff($dateTime2);
	$roznica = $interval->format('%i');
	if ($roznica < 1)
	{
	echo "Przekroczono ilosc prob. Konto zostalo zablokowane na minute.";
	}
	else
	{
		$udane = 0;
		if ($polaczenie->query("INSERT INTO logi (login, IP, udane) VALUES ('$user', '$ipaddress', '$udane')"))
					{
						$_SESSION['udanelogowanie']=true;
						$_SESSION['login'] = $_POST['user'];
						header ('Location: wyswietl.php');	
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
	}
}
}
?>

