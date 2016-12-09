<HTML>
<HEAD>
<title>Confirmation des dates d'exp�dition</title>
 <link rel="stylesheet" type="text/css"  href="./css/monstyle.css">
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
$P3EMAIL = $_SESSION ['P3EMAIL'];
$P3NFOU  = $_SESSION['P3NFOU'];


echo '<TABLE   border="2"> <caption>  <DIV class="haut"> liste des Commandes Déja  confirmées  de :    ' . $P3NFOU  . '</DIV>';
echo '</caption>';
echo '<tr><th>commande</th><th>vos refs</th><th>article</th><th>libelle art</th><th>nos refs </th><th>Date</th><th>confirmation</th><th>selection</th>  </tr>';

// la connexion a la BDD
include ("connexDB2.php");

$requet1 = "   with temp as ( SELECT p.adoc, p.alig , p.nart , P.lcdl , p.DCAR ,    p.ORFD , p.OLIG , c.vref , c.nref , p.fcar ,
			row_number() over( order by p.DCAR , c.vref , p.adoc) as cdefou from   euro4scd/cdflig6   P  inner  join cdfent c
		on p.adoc = c.adoc
            where  P.tygs = 'GCO' and  c.vref <> '  ' and c.atdo = 'CF' 
		 and P.FCAR = '1'  and  P.nfou = '";

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

$requet2 =   $P3NFOU . "' ";

$requet3 = "  ) select   t.adoc, t.vref , t.nref  , t.nart , t.lcdl , t.DCAR ,   t.ORFD , t.OLIG , t.alig ,t.fcar  from temp   t 
		where t.cdefou between " . $posdeb . "  and " . $posfin  . "  order by t.cdefou "    ;

$requet = $requet1 . $requet2 . $requet3;
//echo "req" . $requet ;

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
		echo $lignes ['ADOC'];
		echo "</td><td>";
		echo $lignes ['VREF'];
		echo "</td><td>";
		echo $lignes ['NART'];
		echo "</td><td>";
		echo $lignes ['LCDL'];
		echo "</td><td>";
		echo $lignes ['NREF'];
		echo "</td><td>";
		echo $lignes ['DCAR'];
		echo "</td><td>";
                    if ($lignes ['FCAR'] == 0) {
                    echo 'Non';}
                    else{ echo 'Oui';}
                echo "</td><td>";
		// je rajoute un formulaire pour d�tail BL
		echo '<form method = "POST" action="confirmannul.php" target="_self">';
		echo '<input type="hidden" name="ADOC"  value ="' . $lignes ['ADOC'] . '">';
		echo '<input type="hidden" name="ALIG"  value ="' . $lignes ['ALIG'] . '">';
		echo '<input type="submit" value=">>>"></input>';
		echo "</form>";
		echo "</td></tr>";
		
		$lignes = db2_fetch_assoc ( $result );
	}
}

db2_close ( $DB );
echo '</TABLE>';
// je rajoute un formulaire en arri�re
echo '<form method = "POST" action="ListeConfirm.php" target="_self">';
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="bas">';
echo '<input type="submit" value="<<<"></input>';
echo "</form>";
// je rajoute un formulaire en avant
echo '<form method = "POST" action="ListeConfirm.php" target="_self">';
echo '<input type="hidden" name="posdeb"  value ="' . $posdeb . '">';
echo '<input type="hidden" name="posfin"  value ="' . $posfin . '">';
echo '<input type="hidden" name="sens"  value ="haut">';
echo '<input type="submit" value=">>>"></input>';
echo "</form>";

?>
</BODY>
</HTML>