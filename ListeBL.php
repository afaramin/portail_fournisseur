<HTML>
<HEAD>
<title>liste des Bons livraison </title>
</HEAD>
<BODY bgcolor="FAEBD7">

<?php 
// pour r�cup�rer le mot de passe et le compte user 

$critere = $_POST['cod_tarif'];
$sens  = $_POST['sens'];
$posdeb = $_POST['posdeb'];
$posfin = $_POST['posfin']; 


// filtre sesion
session_start();
$P3LIB 	=	$_SESSION['P3LIB'];
$P3EAMI =	$_SESSION['P3EAMI']; 	
$P3EAMA =	$_SESSION['P3EAMA'];
$P3EMAIL =	$_SESSION['P3EMAIL'];
if ($P3EAMI == '   ') { $P3EAMI = "000" ;} 
if ($P3EAMA == '   ') { $P3EAMA = "099" ;} 
if (empty($_POST['cod_tarif']))  { $critere =  	$_SESSION['P3TARIF'];}



if (empty($critere))
 {
     echo '<br>';
      echo "Donn�e non comprise";
     echo '<br>';
     	
 }	

echo "<TABLE   border='2'> <caption>liste des PLANS de travail  " . $critere   ." de    " . $P3EAMI . " � " . $P3EAMA ;
echo "</caption>";
echo "<tr><th>Document</th><th>B.L</th><th>client</th><th>Ref Client</th><th>N�aff</th><th>Flag</th><th>selection</th></tr>";


 

if (!empty($critere))
 {
         	
 
// la connexion � la BDD
include("connexDB2.php");

	
		$requet1 = "   with temp as ( SELECT p.ndoc, p.nbul , p.ncli , p.refc , p.affa  ,    p.cde3 ,
			row_number() over( order by p.nbul) as bonliv
 			from   euro4scd/bulent   P    where  P.nfac = '  '  and P.CDAF <> '3'  and 
			p.ctar = '"; 

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


$requet2 =    $critere . "' and p.cde3 >=  '" .  $P3EAMI . "' and p.cde3 <=  '" . $P3EAMA . "'" ;
$requet3 = "  ) select   ndoc , nbul , ncli , refc , affa  ,    cde3   from temp              
where bonliv between " . $posdeb . "  and " . $posfin ;                    
 
$requet = $requet1 . $requet2 . $requet3 ;

 
 
$result = db2_exec($DB, $requet);
if ($result == 0){
	echo "<BR>";
	echo "�a va pas du tout !";
	echo $requet;
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
echo $lignes['NDOC'];
echo "</td><td>";
echo $lignes['NBUL'];
echo "</td><td>";
// je rajoute un formulaire pour client L
echo '<form method = "POST" action="testWS.php" target="_self">';
echo '<input type="hidden" name="NCLI"  value ="' . $lignes['NCLI'] . '">';
echo '<input type="hidden" name="cod_tarif"  value ="' . $critere . '">';
echo '<input type="submit" value="' . $lignes['NCLI'] . '" ></input>';
echo "</form>";
// echo $lignes['NCLI'];
echo "</td><td>";
echo  $lignes['REFC']  ;
echo "</td><td>";
echo  $lignes['AFFA']  ;
echo "</td><td>";
echo  $lignes['CDE3']  ;

echo "</td><td>";
// je rajoute un formulaire pour d�tail BL 
echo '<form method = "POST" action="ModifBL.php" target="_self">';
echo '<input type="hidden" name="NBUL"  value ="' . $lignes['NBUL'] . '">';  
echo '<input type="hidden" name="cod_tarif"  value ="' . $critere . '">';
echo '<input type="submit" value=">>>"></input>';
echo "</form>";
echo "</td></tr>";

$lignes = db2_fetch_assoc($result);
} }

db2_close($DB);
}



echo '</TABLE>';

// je rajoute un formulaire en arri�re 
echo '<form method = "POST" action="ListeBL.php" target="_self">';
echo '<input type="hidden" name="cod_tarif"  value ="' . $critere . '">';  
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="bas">';
echo '<input type="submit" value="<<<"></input>';
echo "</form>";
// je rajoute un formulaire en avant 
echo '<form method = "POST" action="ListeBL.php" target="_self">';
echo '<input type="hidden" name="cod_tarif"  value ="' . $critere . '">';  
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="haut">';
echo '<input type="submit" value=">>>"></input>';
echo "</form>";


?>
</BODY>
</HTML>