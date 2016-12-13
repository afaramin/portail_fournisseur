<html>
<head>
<title>Confirmation de Livraison</title>
 <link rel="stylesheet" type="text/css"  href="./css/monstyle.css">
</head>
<BODY>
	<h2>De-Confirmation de Livraison</h2>




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
		echo '<form method = "POST" action="confirmannul.php">';
		
		// tout va bien alors requete
		$requet = "SELECT ADOC , C.VREF , NART , LCDL , DCAR , ALIG , C.NREF , FQCD , a.CTFO FROM
		euro4scd/cdflig a left join euro4scd/cdfent c  using(ADOC) WHERE ADOC = '" . $ADOC . "' and ALIG ='"  . $ALIG . "'";
		// echo  $requet;
		$result = db2_exec ( $DB, $requet );
		
		// lecture une fois
		$lignes = db2_fetch_assoc ( $result );
		if ($lignes != FALSE) {
			echo '<BR>';
			echo "<TABLE   border='2'> <caption class='haut'> DE-Confirmation Livraison " . $ADOC;
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
			
			echo '<form method = "POST" action="Listedejaconfirme.php">';
			 
			echo '<br>';
			echo '<input type="submit" value="retour liste BL"></input>';
			echo '</form>';
		}
	}
} else {
	
	     
        // mode modif = deconfirmation         
       
            
            // gestion de la date pour  l'update
	$DLIV   = '00000000';
        
        
        $ecritbdd = 'call xcdfdcnc( ? , ? , ?   )';
	$stmt = db2_prepare($DB, $ecritbdd );
	$rc = db2_bind_param($stmt, 1, "ADOC" ,DB2_PARAM_IN);
        $rc = db2_bind_param($stmt, 2, "ALIG" ,DB2_PARAM_IN);
        $rc = db2_bind_param($stmt, 3, "DLIV" ,DB2_PARAM_IN);
        
        
	$rc = db2_execute($stmt);
	
	if ($rc == FALSE) {
		die ( "<br>connection using  " . $ecritbdd . " is not correct   </br>" . $ADOC . " " . $ALIG . "  " . $DLIV   );
	
        
            
        }
	    
	    
		
		
	
 
echo '<br>';
echo "Donnée  mise à jour ";
echo '<br>';
 
echo '<form method = "POST" action="Listedejaconfirme.php">';
echo '<br>';
echo '<input type="submit" value="retour liste"></input>';
echo '</form>';
}
?>

 
</body>
</html>