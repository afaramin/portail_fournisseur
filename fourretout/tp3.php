<HTML>
<HEAD>
<title>Reponse</title>
</HEAD>
<BODY bgcolor="00FF00">


<TABLE   border="2">
<caption>Regions vitiloles  </caption>
<tr><th>code</th><th>region</th><th>pays</th></tr>


<?php 

// la connexion à la BDD
$DB =  odbc_connect("AS400", "" , "");
odbc_setoption($DB ,1,  SQL_ATTR_DBC_DAFAULT_LIB , BDVIN1);
$requet = "SELECT R.REGION_CODE, R.REGION, P.PAYS FROM bdvin1.regions R inner
join bdvin1.pays P using(pays_code) order by R.REGION_CODE";

$result = odbc_exec($DB, $requet ) ;
if ($result == 0){
	echo "<BR>";
	echo "ça va pas du tout /!";
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
echo $lignes['PAYS''];
echo "</td></tr>";
$lignes = odbc_fetch_array($result);
} }

odbc_close($DB);
?>
</TABLE>

</BODY>
</HTML>