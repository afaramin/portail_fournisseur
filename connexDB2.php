<?php 

//session_start();
$user = $_SESSION['CodeUser'];
 $pwd = $_SESSION['MotPasse'];
 
	// CONVENTION APPELATION SYSTEME : DB2_I5_NAMING_ON  (appelation avec / au lieu  de .) et 
	// utilisation des jobd 
$option = array('i5_libl'=>'EURO4SCDG SPECSCD EURO4SCD   EURO4PRG TEUR4SCD QTEMP' , 'i5_naming'=>DB2_I5_NAMING_ON,'i5_commit'=>DB2_I5_TXN_NO_COMMIT); 
$DB = db2_connect("IFS", $user , $pwd , $option);
if (!$DB) {
echo "<b>Connect Failed: " . db2_conn_errormsg() . "</b>";
}
 
?>