<HTML>
<HEAD>
<title>Reponse</title>
 <link rel="stylesheet" type="text/css"  href="./css/monstyle.css">
</HEAD>
<BODY>


<?php 
// pour r�cup�rer le mot de passe et le compte user 
session_start(); 

echo '<TABLE   border="2">';
echo '<caption>Liste des usines  Plan </caption>';
echo '<tr><th>Code tarif </th><th> Tarif Plan </th> <th> selection </th></tr>';

// la connexion � la BDD
include("connexDB2.php");
 
$requet = "SELECT P4TAR , P4LIB FROM  SPECSCD/PLTARIF";
//$requet = "select  * from qgpl/tarifluisi";
//$stmt = db2_prepare($DB , $requet);
//$result = db2_execute($stmt) ;
$result = db2_exec($DB, $requet);

if (!$result){
	echo "<BR>";
	echo "�a va pas du tout !" . db2_conn_errormsg($DB);
	}else
	{
//1�lecture 
	include("utilitaire.php");
	$lignes = db2_fetch_assoc($result);
	$i = 0;
	//la boucle
	while ($lignes  != FALSE)
	{
	$i = $i + 1;
	echo "<tr BGCOLOR='" . row_color($i) . "'><td>";
	echo $lignes['P4TAR'];
	echo "</td><td>";
	echo $lignes['P4LIB'];
	echo "</td><td>";

	// le petit formulaire
	echo '<form method = "POST" action="ListeBL.php" target="_self">';
	echo '<input type="hidden" name="cod_tarif"  value ="' . $lignes['P4TAR'] . '">';  
	echo '<input type="submit" value=">>>"></input>';
	echo "</form>";
	echo "</td></tr>";

	$lignes = db2_fetch_assoc($result);
	} 
db2_close($DB);
}



echo '</TABLE>';
// Bouton retour 
echo '<form method = "POST" action="listCodeUsine.php" target="_self">';  
echo '<input type="submit" value="Retour"></input>';
echo "</form>";
echo "&nbsp";
// bouton sortir
echo '<form method = "POST" action="index.php" target="_top">';  
echo '<input type="submit" value="Sortir"></input>';
echo "</form>";


?>

</BODY>
</HTML>