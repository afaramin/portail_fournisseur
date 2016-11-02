<HTML>
<HEAD>
<title>liste des Meubles</title>
 <link rel="stylesheet" type="text/css" href="./monstyle.css">
</HEAD>
<BODY>

<?php
// pour r�cup�rer le mot de passe et le compte user
session_start ();
$sens = $_POST ['sens'];
$posdeb = $_POST ['posdeb'];
$posfin = $_POST ['posfin'];

// filtre sesion
$P3LIB = $_SESSION ['P3LIB'];
$P3EAMIV = $_SESSION ['P3EAMIV'];
$P3EAMAV = $_SESSION ['P3EAMAV'];
$P3EMAIL = $_SESSION ['P3EMAIL'];
if ($P3EAMIV == '   ') {
	$P3EAMIV = "000";
}
if ($P3EAMAV == '   ') {
	$P3EAMAV = "099";
}

echo "<TABLE   border='2'> <caption>liste des Meubles  du statut :    " . $P3EAMIV . " � " . $P3EAMAV;
echo "</caption>";
echo "<tr><th>Document</th><th>B.L</th><th>client</th><th>Ref Client</th><th>N�aff</th><th>Flag</th><th>Date</th><th>selection</th></tr>";

// la connexion � la BDD
include ("connexDB2.php");

$requet1 = "   with temp as ( SELECT p.ndoc, p.nbul , p.ncli , p.refc , p.affa  ,    p.cde3 , p.dliv ,
			row_number() over( order by p.nbul) as bonliv from   euro4scd/bulent   P    where  P.nfac = '  ' 
		 and P.CDAF = '2'  and  P.TDOC in ('DM' , 'SM')  ";

if (empty ( $sens )) {
	$posdeb = 1;
	$posfin = 20;
} else {
	
	if ($sens == 'haut') {
		$posdeb = $posdeb + 20;
		$posfin = $posfin + 20;
	}
	if ($sens == 'bas') {
		if ($posdeb > 20) {
			$posdeb = $posdeb - 20;
			$posfin = $posfin - 20;
		} else {
			$posdeb = 1;
			$posfin = 20;
		}
	}
}

$requet2 = " and p.cde3 >=  '" . $P3EAMIV . "' and p.cde3 <=  '" . $P3EAMAV . "'";

$requet3 = "  ) select   ndoc , nbul , t.ncli , c.rssc ,  refc , t.affa  ,    cde3 , dliv   from temp   t inner join client c
		on t.ncli = c.ncli 
where bonliv between " . $posdeb . "  and " . $posfin  . "  order by dliv , nbul desc"    ;

$requet = $requet1 . $requet2 . $requet3;

$result = db2_exec ( $DB, $requet );
if ($result == 0) {
	echo "<BR>";
	echo "�a va pas du tout !";
	echo $requet;
} else {
	// 1�lecture
	include ("utilitaire.php");
	$lignes = db2_fetch_assoc ( $result );
	$i = 0;
	// la boucle
	while ( $lignes != FALSE ) {
		$i = $i + 1;
		echo "<tr BGCOLOR='" . row_color( $i ) . "'><td>";
		echo $lignes ['NDOC'];
		echo "</td><td>";
		echo $lignes ['NBUL'];
		echo "</td><td>";
		// je rajoute un libell� client
		echo $lignes ['NCLI'] . " " . $lignes ['RSSC'];
		echo "</td><td>";
		echo $lignes ['REFC'];
		echo "</td><td>";
		echo $lignes ['AFFA'];
		echo "</td><td>";
		echo $lignes ['CDE3'];
		echo "</td><td>";
		echo $lignes ['DLIV'];
		echo "</td><td>";
		// je rajoute un formulaire pour d�tail BL
		echo '<form method = "POST" action="ModifBL.php" target="_self">';
		echo '<input type="hidden" name="NBUL"  value ="' . $lignes ['NBUL'] . '">';
		echo '<input type="submit" value=">>>"></input>';
		echo "</form>";
		echo "</td></tr>";
		
		$lignes = db2_fetch_assoc ( $result );
	}
}

db2_close ( $DB );
echo '</TABLE>';
// je rajoute un formulaire en arri�re
echo '<form method = "POST" action="ListeMeuble.php" target="_self">';
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="bas">';
echo '<input type="submit" value="<<<"></input>';
echo "</form>";
// je rajoute un formulaire en avant
echo '<form method = "POST" action="ListeMeuble.php" target="_self">';
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="haut">';
echo '<input type="submit" value=">>>"></input>';
echo "</form>";

?>
</BODY>
</HTML>