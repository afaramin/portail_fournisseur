<html>
<head>
<title>Confirmation de Livraison</title>
 <link rel="stylesheet" type="text/css"  href="./css/monstyle.css">
</head>
<BODY>
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
		$requet = "SELECT ADOC , C.VREF , NART , LCDL , DCAR , ALIG , C.NREF , FQCD , a.CTFO FROM
		euro4scd/cdflig a left join euro4scd/cdfent c  using(ADOC) WHERE ADOC = '" . $ADOC . "' and ALIG ='"  . $ALIG . "'";
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
			echo '<b>article    </b>  ' . $lignes ['NART'] . '  ' . $lignes['CTFO']  ;
			echo '<BR>';
			echo '</td></tr>';
			
                        echo '<tr><td>';
			echo '<b>quantité    </b>  ' . $lignes ['FQCD']  ;
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
			$DLIV = $lignes ['DCAR'];
			// echo " gettype = " . gettype($DLIV);
			$annee = intval ( $DLIV / 10000 );
			$mois = intval ( ($DLIV - ($annee * 10000)) / 100 );
			$jour = intval ( $DLIV - (($annee * 10000) + ($mois * 100)) );
			$DLIV = $jour . '/' . $mois . '/' . $annee;
			echo '<b>date Livraison    </b> '  . $DLIV ;
			echo '</td></tr>';
			echo '</TABLE>';
			
			
			echo '<input type="hidden" name="ADOC"  value ="' . $lignes ['ADOC'] . '">';
			echo '<input type="hidden" name="ALIG"  value ="' . $lignes ['ALIG'] . '">';
			echo '<input type="hidden" name="modpgm" value ="' . "modif" . '">';
			echo '<br>';
			echo '<input type="submit" value="de-confirmer "></input>';
			echo '</form>';
			
			echo '<form method = "POST" action="ListedejaConfirme.php">';
			 
			echo '<br>';
			echo '<input type="submit" value="retour liste BL"></input>';
			echo '</form>';
		}
	}
} else {
	
	$statut = $_POST['statut'];
        
        
           if  ($statut === 'Non') {
	// gestion de la date pour  l'update
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
		
        if ($result === FALSE) {die ( "<br>connection using  " . $requet . " is not correct   </br>" );}
             }
             
                
        if  ($statut === 'Oui') {
	// oui confirmé appel du programme de EUREKA via l'appel d'une procedure SQL
            
            // gestion de la date pour  l'update
	$DLIV   = $_POST ['dateliv'];
	$date_explosee = explode ( "/", $DLIV );
	$jour   = $date_explosee [0];
	$mois   = $date_explosee [1];
	$annee  = $date_explosee [2];
	$DLIV   = $jour * 1000000 + $mois * 10000 + $annee;
        
        
        $ecritbdd = 'call xcdfarcc( ? , ? , ?   )';
	$stmt = db2_prepare($DB, $ecritbdd );
	$rc = db2_bind_param($stmt, 1, "ADOC" ,DB2_PARAM_IN);
        $rc = db2_bind_param($stmt, 2, "ALIG" ,DB2_PARAM_IN);
        $rc = db2_bind_param($stmt, 3, "DLIV" ,DB2_PARAM_IN);
        
        
	$rc = db2_execute($stmt);
	
	if ($rc == FALSE) {
		die ( "<br>connection using  " . $ecritbdd . " is not correct   </br>" . $ADOC . " " . $ALIG . "  " . $DLIV   );
	}
        
            
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