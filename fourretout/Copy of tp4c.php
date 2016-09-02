<HTML>
<HEAD>
<title>liste des producteurs </title>
</HEAD>
<BODY bgcolor="00FF00">

<?php 
$critere =$_POST['cod_regions'];
if (empty($critere))
 {
    	echo "Donnée non comprise";    	
 }	
?>

<TABLE   border="2">
<caption>liste des producteurs  </caption>
<tr><th>Nom</th><th>Ville</th><th>count(*)</th></tr>


<?php 

if (!empty($critere))
 {
         	
 
// la connexion à la BDD
$DB =  odbc_connect("AS400", "" , "");
odbc_setoption($DB ,1,  SQL_ATTR_DBC_DAFAULT_LIB , BDVIN1);
$requet1 = "   SELECT p.PR_NOM, p.PR_COMMUNE ,                                     
 count(*) zz FROM bdvin1.appellations a inner join bdvin1.producteurs p 
 using(APPEL_CODE) LEFT join bdvin1.vins v using(PR_CODE) WHERE     
 REGION_CODE = '"; 

$requet3 = "GROUP BY p.PR_NOM , p.PR_COMMUNE";                    

$requet2 =    $critere . "'   ";
$requet = $requet1 . $requet2 . $requet3 ;
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
echo $lignes['PR_NOM'];
echo "</td><td>";
echo $lignes['PR_COMMUNE'];
echo "</td><td>";

$val = $lignes['ZZ'] *10 ;
echo '<img src="barre_rouge.jpg" height ="15" width ="' .$val . '">' ;
echo  $lignes['ZZ']  ;
echo "</td></tr>";

$lignes = odbc_fetch_array($result);
} }

odbc_close($DB);
}
?>
</TABLE>

</BODY>
</HTML>