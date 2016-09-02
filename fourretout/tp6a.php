<HTML>
<HEAD>
<title>Reponse</title>
</HEAD>
<BODY bgcolor="00FF00">



<?php 
// déclaration des variables
$date = "                                ";
     	
 
// la connexion à la BDD
include("connexDB2.php");
//la requete
$requete= "CALL  DATETIME (?)";
 // le prepare et le bind des variables
$stmt = db2_prepare($DB  , $requete);

 $rc = db2_bind_param($stmt ,1 , 'date', DB2_PARAM_OUT);
// exécution
$rc= db2_execute($stmt);
// lecture une fois dans un tableau
//   $lignes = db2_fetch_assoc($stmt);



if (   $rc != FALSE) 
{
echo "il est :" . $date; 
 // le petit formulaire
 echo '<form method = "POST" action="tp6a.php" target="_self">';
 echo '<br>' ;
 echo '<input type="submit" value="reaffich"></input>' ;
 echo '</form>';
 }
 else
 {
 	echo'ça va pas du tout';
 }
 db2_close($DB);
?>
 