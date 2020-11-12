<?php
session_start();
$ipaddress = $_SERVER["REMOTE_ADDR"];
$user=$_POST['user']; // login z formularza
$pass=$_POST['pass']; // has³o z formularza $link = mysqli_connect(localhost, nazwa_usera, has³o_usera, baza_usera); // po³¹czenie z BD – wpisaæ swoje parametry !!!
$_SESSION['login'] = $_POST['user'];
$udane = '0';
$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
$link = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if(!$link) { echo"B³¹d: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obs³uga b³êdu po³¹czenia z BD
mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
$result = mysqli_query($link, "SELECT * FROM users WHERE login='$user'"); // pobranie z BD wiersza, w którym login=login z formularza
$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
if(!$rekord) //Jeœli brak, to nie ma u¿ytkownika o podanym loginie
{
mysqli_close($link);
echo "Brak uzytkownika o takim loginie !"; // UWAGA nie wyœwietlamy takich podpowiedzi dla hakerów
}
else
{
$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
$rezultat = mysqli_query($polaczenie, "SELECT * FROM logi WHERE login = '$user'") or die ("B³¹d zapytania do bazy: $dbname");
while ($wiersz = mysqli_fetch_array ($rezultat)){
		$udane = $wiersz[4];
}

if($udane < 2)
{
if($rekord['haslo']==$pass) // czy has³o zgadza siê z BD
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
			echo '<span style="color:red;">B³¹d serwera!</span>';
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
echo "Bledne haslo!"; // UWAGA nie wyœwietlamy takich podpowiedzi dla hakerów
}
}
else{
	$dbhost="mysql01.blazejkarczmarczyk.beep.pl"; $dbuser="karczmarczyk"; $dbpassword="Zadanie7ABC"; $dbname="zadanie7";
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	$rezultat2 = mysqli_query($polaczenie, "SELECT * FROM logi WHERE login = '$user'") or die ("B³¹d zapytania do bazy: $dbname");
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

