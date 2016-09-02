<html>
<head>
<title>Detail produceturs </title>
</head>
<body>
<h2>Detail producteurs </h2>

<ol>
<li>ligne 1</li>
<li>ligne 2</li>
<li>ligne 3</li>
</ol>

  

 
<br><br>




<?php 
$critere =$_POST['cod_prod'];

if (empty($_POST['modpgm']))
{ // modif visualisation
if (empty($critere))
 {
    	echo "Donnée non comprise";    	
 }	else 
 {
 // mode visu et donnée correcte	
 echo '<form method = "POST" action="tp5c.php">';
 	// tout va bien alors requête
include("connex.php");
$requet = "SELECT PR_NOM, PR_TEL, PR_ADRESSE , APPEL_CODE FROM
bdvin1.producteurs WHERE PR_CODE = " . $critere;
echo $requet;
$result = odbc_exec($DB, $requet    ) ;

// lecture une fois
$lignes = odbc_fetch_array($result);
if (   $lignes  != FALSE) 
{
 echo '<BR>';
 echo '<b>Code Producteur   </b> <input type="text" name="codprod2" value ="' . $critere . '">';	
 echo '<BR>';
 echo '<b>nom  Producteur   </b> <input type="text" name="nomprod" value ="' . $lignes['PR_NOM'] . '">';
 echo '<input type="hidden" name="cod_prod" value ="'. $critere . '">';
 echo '<input type="hidden" name="modpgm" value ="'. "modif" . '">';
 echo '<input type="hidden" name="cod_regions"  value ="' . $_POST['cod_regions'] . '">';
 echo '<br>' ;
 echo '<input type="submit" value="valider"></input>' ;
 echo '</form>';
 }
 }
}else
{
	// gestion de l'update 
	echo 'je suis en update';
	$requet1 = 'UPDATE bdvin1.producteurs 
		set  PR_NOM ="' . $_POST['nomprod'] . '" '  ;
	$requet2 = " where  PR_CODE = " . $critere;
	$requet = $requet1 . $requet2 ;  
$result = odbc_exec($DB, $requet    );
echo $requet ;

 echo '<form method = "POST" action="tp4c.php">';
 echo '<br>' ;
 echo '<input type="hidden" name="cod_regions"  value ="' . $_POST['cod_regions'] . '">';
 echo '<input type="submit" value="valider"></input>' ;
 echo '</form>';


}
?>

 
</body>
</html>