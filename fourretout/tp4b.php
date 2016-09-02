<?php 
session_start();
?>

<HTML>
<HEAD>
<title>Reponse</title>
</HEAD>
<BODY bgcolor="00FF00">

<?php 
$critere =$_POST['pays'];
if (empty($critere))
 {
    	echo "Donnée non comprise";    	
 }	
?>

<TABLE   border="2">
<caption>Regions vitiloles  </caption>
<tr><th>code</th><th>region</th><th>pays</th> <th> selection </th></tr>


<?php 

if (!empty($critere))
 {
         	$_SESSION['nom_pays'] = $critere;
 
// la connexion à la BDD
$DB =  odbc_connect("AS400", "" , "");
odbc_setoption($DB ,1,  SQL_ATTR_DBC_DAFAULT_LIB , BDVIN1);
$requet1 = "SELECT R.REGION_CODE, R.REGION, P.PAYS FROM bdvin1.regions R inner
join bdvin1.pays P using(pays_code) ";
 
$requet2 = " where UPPER(P.pays) like '" . $critere . "%'   ";
$requet = $requet1 . $requet2 ;
echo $requet;
$result = odbc_exec($DB, $requet    ) ;
if ($result == 0){
	echo "<BR>";
	echo "ça va pas du tout !";
	}else
	{
//1°lecture 
$lignes = odbc_fetch_array($result);

//la boucle
while ($lignes  != FALSE)
{
echo "<tr><td>";
echo $lignes['REGION_CODE'];
echo "</td><td>";
echo $lignes['REGION'];
echo "</td><td>";
echo $lignes['PAYS'];
echo "</td><td>";

// le petit formulaire
echo '<form method = "POST" action="tp4c.php" target="_self">';
echo '<input type="hidden" name="cod_regions"  value ="' . $lignes['REGION_CODE'] . '">';  
echo '<input type="submit" value=">>>"></input>';
echo "</form>";
echo "</td></tr>";

$lignes = odbc_fetch_array($result);
} }
odbc_close($DB);
}


?>
</TABLE>
<?php 
// Bouton retour 
echo '<form method = "POST" action="tp4a.php" target="_self">';  
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