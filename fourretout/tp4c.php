<HTML>
<HEAD>
<title>liste des producteurs </title>
</HEAD>
<BODY bgcolor="00FF00">

<?php 
$critere =$_POST['cod_regions'];
$sens  = $_POST['sens'];
$posdeb = $_POST['posdeb'];
$posfin = $_POST['posfin']; 

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
include("connex.php");
$requet1 = "   with temp as( SELECT p.PR_NOM, p.PR_COMMUNE , PR_CODE , count(*) zz ,    
row_number() over( order by p.PR_NOM) as ligne   from            
bdvin1.appellations a inner join bdvin1.producteurs p          
using(APPEL_CODE) inner join bdvin1.vins v using(PR_CODE) WHERE
REGION_CODE = '"; 

if (empty($sens))
{
$posdeb = 1;
$posfin = 20;

} else
{ 
	 
	if ($sens == 'haut')
	{
		$posdeb = $posdeb + 20 ;
		$posfin = $posfin + 20 ;
	}
	if ($sens == 'bas')
	{
		if ($posdeb >20)
		{
		$posdeb = $posdeb - 20 ;
		$posfin = $posfin - 20 ;
		} else
		{
			$posdeb = 1;
			$posfin = 20;
		}
	}
}

$requet3 = "GROUP BY p.PR_NOM , p.PR_COMMUNE , PR_CODE ) select      
 PR_NOM , PR_COMMUNE , zz  , PR_CODE       from temp                     
where ligne between " . $posdeb . "  and " . $posfin ;                    

$requet2 =    $critere . "'   ";
$requet = $requet1 . $requet2 . $requet3 ;
echo $requet;
echo $sens;
 
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
echo "</td><td>";
// je rajoute un formulaire en arrière 
echo '<form method = "POST" action="tp5c.php" target="_self">';
echo '<input type="hidden" name="cod_prod"  value ="' . $lignes['PR_CODE'] . '">';  
echo '<input type="hidden" name="cod_regions"  value ="' . $critere . '">';
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
// je rajoute un formulaire en arrière 
echo '<form method = "POST" action="tp4c.php" target="_self">';
echo '<input type="hidden" name="cod_regions"  value ="' . $critere . '">';  
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="bas">';
echo '<input type="submit" value="<<<"></input>';
echo "</form>";
// je rajoute un formulaire en avant 
echo '<form method = "POST" action="tp4c.php" target="_self">';
echo '<input type="hidden" name="cod_regions"  value ="' . $critere . '">';  
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="haut">';
echo '<input type="submit" value=">>>"></input>';
echo "</form>";

?>
</BODY>
</HTML>