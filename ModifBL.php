<html>
<head>
<title>Detail Bon de Livraison</title>
</head>
<body>
	<h2>Modification Bons de Livraison</h2>




	<br>
	<br>




<?php
session_start ();
$CodeUser = $_SESSION ['CodeUser'];
// récup info formulaire
$critere = $_POST ['NBUL'];
$cod_tarif = $_POST['cod_tarif'];

include ("connexDB2.php");



// LA Premiere fois modpgm est vide !
if (empty ( $_POST ['modpgm'] )) { // modif visualisation
	if (empty ( $critere )) {
		echo "Donnée non comprise";
	} else {
		// mode visu et donnée correcte
		echo '<form method = "POST" action="ModifBL.php">';
		
		// tout va bien alors requête
		$requet = "SELECT nbul ,  affa , cde3 , refc , dliv  FROM
		euro4scd/bulent  WHERE nbul = '" . $critere . "'";
		
		$result = db2_exec ( $DB, $requet );
		
		// lecture une fois
		$lignes = db2_fetch_assoc ( $result );
		if ($lignes != FALSE) {
			echo '<BR>';
			echo "<TABLE   border='2'> <caption> Modificaton du BL " . $critere;
			echo "</caption>";
			
			echo '<tr><td>';
			echo '<b>numero affaire   </b> <input type="text" name="nomaffa" value ="' . $lignes ['AFFA'] . '">';
			echo '<BR>';
			echo '</td></tr>';
			
			echo '<tr><td>';
			echo '<b>contremarque   </b> <input type="text" name="contremarque" value ="' . $lignes ['REFC'] . '"  size = 30>';
			echo '<BR>';
			echo '</td></tr>';
			
			echo '<tr><td>';
			echo '<b> ancienne valeur statut :   </b>' . $lignes ['CDE3'];
			echo '<BR>';
			echo '</td></tr>';
			
			if ($CodeUser != 'KN') {
			    echo '<tr><td>';
			    echo '<b> code tarif  plan :   </b>' . $cod_tarif;
			    echo '<BR>';
			    echo '</td></tr>';
			}
			
			echo '<tr><td>';
			echo '<b> nouveau statut    </b> <input type="text" name="statut" value = "011">';
			echo '</td></tr>';
			
			echo '<tr><td>';
			$DLIV = $lignes ['DLIV'];
			// echo " gettype = " . gettype($DLIV);
			$annee = intval ( $DLIV / 10000 );
			$mois = intval ( ($DLIV - ($annee * 10000)) / 100 );
			$jour = intval ( $DLIV - (($annee * 10000) + ($mois * 100)) );
			$DLIV = $jour . '/' . $mois . '/' . $annee;
			echo '<b>date Livraison    </b> <input type="text" name="dateliv" value ="' . $DLIV . '">';
			echo '</td></tr>';
			echo '</TABLE>';
			
			echo '<input type="hidden" name="NBUL"  value ="' . $lignes ['NBUL'] . '">';
			echo '<input type="hidden" name="modpgm" value ="' . "modif" . '">';
			echo '<input type="hidden" name="cod_tarif"  value ="' . $cod_tarif . '">';
			echo '<br>';
			echo '<input type="submit" value="valider"></input>';
			echo '</form>';
			
			if ($CodeUser == 'KN') {
				echo '<form method = "POST" action="ListeMEUBLE.php">';
			} else {
			  	echo '<form method = "POST" action="ListeBL.php">';
				echo '<input type="hidden" name="cod_tarif"  value ="' . $cod_tarif . '">';
			}
			echo '<br>';
			echo '<input type="submit" value="retour liste BL"></input>';
			echo '</form>';
		}
	}
} else {
	
	//$chgcurlib = "call qsys/qcmdexc ('chgcurlib curlib(euro4scdg)' 27)";
	//$ret = db2_exec($db , $chgcurlib);
	//if (!$ret) die("<br>chgcurlib command failed. errno= " .$chgcurlib);
	
	// $addlible = "call qcmdexc ('addlible euro4scdg' , 0000000018.00000)";
	// $result = db2_exec($db, $addlible); 
	// if (!$result) die("<br>chgcurlib command failed. errno= " . $addlible);
	
	// gestion de l'update
	$DLIV = $_POST ['dateliv'];
	$date_explosee = explode ( "/", $DLIV );
	$jour = $date_explosee [0];
	$mois = $date_explosee [1];
	$annee = $date_explosee [2];
	$DLIV = $annee * 10000 + $mois * 100 + $jour;
	
	$requet1 = "UPDATE euro4scd/bulent 	set  affa = '" . $_POST ['nomaffa'] . "'  ,  cde3 = '" . $_POST ['statut'] . "'" . " , dliv = " . $DLIV;
	$requet2 = " where  nbul  = '" . $critere . "'";
	$requet = $requet1 . $requet2;
	$result = db2_exec($DB, $requet );
	
	if ($DLIV <= Date ( 'Ymd' )) {
		
		$sujet = 'date incorrect sur le BL' . $critere;
		$desti = 'andre.faramin@scd.fr';
		$message = ' la date de livraison ne semble pas convenir ' . $DLIV;
		mail($desti, $sujet, $message );
		}
	
	// echo $requet;
	
	// ecrit la dtaara
	$ecritdtaara = 'call xkuhne(?)';
	$stmt = db2_prepare($DB, $ecritdtaara );
	$rc = db2_bind_param($stmt, 1, "critere" ,DB2_PARAM_IN);
	$rc = db2_execute($stmt);
	
	if ($rc == FALSE) {
		die ( "<br>connection using  " . $ecritdtaara . " is not correct   </br>" );
	}
 
echo '<br>';
echo "Donnée mise à jour ";
echo '<br>';

if ($CodeUser == 'KN') {
	echo '<form method = "POST" action="ListeMEUBLE.php">';
} else {
    echo '<form method = "POST" action="ListeBL.php">';
	echo '<input type="hidden" name="cod_tarif"  value ="' . $cod_tarif . '">';
}
echo '<br>';
echo '<input type="submit" value="retour liste"></input>';
echo '</form>';
}
?>

 
</body>
</html>