<?php 
session_start();
?>


<html>
<head>
<title>choix pays </title>
</head>
<body>
<h2>Liste des régions par PAYS </h2>

 

  

 
<br><br>
<form method = "POST" action="tp4b.php" target="_self">
<?php 
$val =  ($_SESSION['nom_pays'] ); 
echo 'Entrez le  Nom du Pays    <input type="text" name="pays"  value="' . $val  . '">';
//Entrez le  Nom du Pays    <input type="text" name="pays"> 
?>


<br>
<input type="submit" value="valider"></input>
</form>
</body>
</html>