<html>
<body> 
<?php session_start(); $user = $_SESSION['login'];?>	
Wybierz plik do przeslania: 			
<form action="odbierz_podkatalog.php" method="POST" ENCTYPE="multipart/form-data"> 
<input type="file" name="plik"/> <input type="submit" value="Wyslij plik"/>
</form>
Twoje pliki w podkatalogu <?php $podkatalog = $_GET['file']; echo "<b>".$_GET['file']."</b>"; ?>: <br>
</body> 
</html>

<?php
session_start(); 
$user = $_SESSION['login'];
$_SESSION['podkatalog'] = $_GET['file'];
$directory = $_SESSION['login'] .'/' .$_SESSION['podkatalog'] .'/';

$files = scandir($directory); 
foreach($files as $file)
{
    if(is_file($directory.$file)){
	echo "<a href='http://www.blazejkarczmarczyk.pl/z7/$directory" . "$file'>$file</a>"."  ";	
	  echo "<a href='pobierz_podkatalog.php?file=$file'>Pobierz</a>"; echo "  "  ;
	  echo "<a href='usun_podkatalog.php?usun=$file'>Usun</a>"."<br>";
	}
}
?>