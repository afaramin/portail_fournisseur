<html>
<head>
<title>Confirmation de Livraison</title>
</head>
<body>
	<h2>Confirmation de Livraison</h2>




	<br>
	<br>




<?php
session_start ();
$CodeUser = $_SESSION ['CodeUser'];
// r�cup info formulaire
$ADOC = $_POST ['ADOC'];
$ALIG = $_POST['ALIG'];

include ("connexDB2.php");



// LA Premiere fois modpgm est vide !
if (empty ( $_POST ['modpgm'] )) { // modif visualisation
	if (empty ( $ADOC )) {
		echo "Donnee non comprise";
	} else {
		// mode visu et donn�e correcte
		echo '<form method = "POST" action="confirm.php">';
		
		// tout va bien alors requete
		$requet = "SELECT ADOC ,  C.VREF , NART , LCDL , DCAR , ALIG , C.NREF FROM
		euro4scd/cdflig left join euro4scd/cdfent c  using(ADOC) 
	    WHERE ADOC = '" . $ADOC . "' and ALIG ='"  . $ALIG . "'";
		// echo  $requet;
		$result = db2_exec ( $DB, $requet );
		
		// lecture une fois
		$lignes = db2_fetch_assoc ( $result );
		if ($lignes != FALSE) {
			echo '<BR>';
			echo "<TABLE   border='2'> <caption> Confirmation Livraison " . $ADOC;
			echo "</caption>";
			
			echo '<tr><td>';
			echo '<b>Vos REFS    </b>' . $lignes ['VREF']  ;
			echo '<BR>';
			echo '</td></tr>';
			
			echo '<tr><td>';
			echo '<b>article    </b>  ' . $lignes ['NART']  ;
			echo '<BR>';
			echo '</td></tr>';
			
			echo '<tr><td>';
			echo '<b> libelle :   </b>' . $lignes ['LCDL'];
			echo '<BR>';
			echo '</td></tr>';
			
			echo '<tr><td>';
			echo '<b> Nos Ref :   </b>' . $lignes ['NREF'];
			echo '<BR>';
			echo '</td></tr>';			
			echo '<tr><td>';
                        echo 'valider ici</td><td>';
			echo '<input type="radio" name="statut" value="Oui" checked> Oui <br>';
			echo '<input type="radio" name="statut" value="Non"> Non <br>';
			//echo '<b> confirmer    </b> <input type="text" name="statut" value = "OUI">';
			echo '</td></tr>';
			
			echo '<tr><td>';
			$DLIV = $lignes ['DCAR'];
			// echo " gettype = " . gettype($DLIV);
			$annee = intval ( $DLIV / 10000 );
			$mois = intval ( ($DLIV - ($annee * 10000)) / 100 );
			$jour = intval ( $DLIV - (($annee * 10000) + ($mois * 100)) );
			$DLIV = $jour . '/' . $mois . '/' . $annee;
			echo '<b>date Livraison    </b> <input type="text" name="dateliv" value ="' . $DLIV . '">';
			echo '</td></tr>';
			echo '</TABLE>';
			
			
			echo '<input type="hidden" name="ADOC"  value ="' . $lignes ['ADOC'] . '">';
			echo '<input type="hidden" name="ALIG"  value ="' . $lignes ['ALIG'] . '">';
			echo '<input type="hidden" name="modpgm" value ="' . "modif" . '">';
			echo '<br>';
			echo '<input type="submit" value="valider"></input>';
			echo '</form>';
			
			echo '<form method = "POST" action="ListeConfirm.php">';
			 
			echo '<br>';
			echo '<input type="submit" value="retour liste BL"></input>';
			echo '</form>';
		}
	}
} else {
	
	$statut = $_POST['statut'];
	// gestion de l'update
	$DLIV = $_POST ['dateliv'];
	$date_explosee = explode ( "/", $DLIV );
	$jour = $date_explosee [0];
	$mois = $date_explosee [1];
	$annee = $date_explosee [2];
	$DLIV = $annee * 10000 + $mois * 100 + $jour;
	

   // mise à jour date
	$requet1 = "UPDATE euro4scd/cdflig  	set     DCAR = " . $DLIV;
	$requet2 = " where  ADOC  = '" . $ADOC . "' and ALIG = '" . $ALIG . "'";
	$requet = $requet1 . $requet2;
	$result = db2_exec($DB, $requet );
	// si date incorrect 
	if ($DLIV <= Date ( 'Ymd' )) {
		$sujet = 'date incorrect sur la commande Vimens' . $adoc;
		$desti = 'scda@scd.fr';
		$message = ' la date de livraison ne semble pas convenir ' . $DLIV;
		mail($desti, $sujet, $message ); }
		
	if ($result == FALSE) {		die ( "<br>connection using  " . $requet . " is not correct   </br>" );
               
        }
                
        if  ($statut == 'Oui') {
	// oui confirmé appel du programme de EUREKA 
            
        }
	    
	    
		
		
	
 
echo '<br>';
echo "Donnée  mise à jour ";
echo '<br>';
 
echo '<form method = "POST" action="ListeConfirm.php">';
echo '<br>';
echo '<input type="submit" value="retour liste"></input>';
echo '</form>';
}
?>

 
</body>
</html>