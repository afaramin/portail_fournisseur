<?php
class Customer_Informations {
	/**
	 * @var string 
	 */
	public $CLIENT_NCLI;
	/**
	 * @var string 
	 */
	public $CLIENT_RSSC;
	/**
	 * @var string 
	 */
	public $CLIENT_ADR1;
	/**
	 * @var string 
	 */
	public $CLIENT_ADR2;
	/**
	 * @var string 
	 */
	public $CLIENT_CPOS;
	/**
	 * @var string 
	 */
	public $CLIENT_VILL;
	/**
	 * @var string 
	 */
	public $CLIENT_LIVR_RSSC;
	/**
	 * @var string 
	 */
	public $CLIENT_LIVR_ADR1;
	/**
	 * @var string 
	 */
	public $CLIENT_LIVR_ADR2;
	/**
	 * @var string 
	 */
	public $CLIENT_LIVR_CPOS;
	/**
	 * @var string 
	 */
	public $CLIENT_LIVR_VILL;
	/**
	 * @var string 
	 */
	public $CLIENT_LIVR_TELP;
	/**
	 * @var string 
	 */
	public $CLIENT_EMAIL;
	/**
	 * @var string 
	 */
	public $CLIENT_CMPA;
	/**
	 * @var string 
	 */
	public $CLIENT_CLDO;
	/**
	 * @var string 
	 */
	public $CODE_ERROR;
	/**
	 * @var string 
	 */
	public $MSG_ERROR;

}

require('../connection/connection.inc');
class WS_SCD {
	

/**
 * @param string $LOGI
 * @param string $PASSWD
 * @param string $XML
 * @return string
 */

public function Set_Orders_XML($LOGI,$PASSWD,$XML)
{

         $Nom_Fichier='XML';
	
		$Res=Init_config();

		$fp =  Debut_Trace($Nom_Fichier);
	$Trace=date("d-m-Y")." à ".date("H:i:s")." -> "."Reception Fichier";
Enreg_Trace($Nom_Fichier,$fp,$Trace);
	$ret=SaveFichier($XML);
	if($ret!="0")
 {
 $Trace="Erreur traitement du fichier";
Enreg_Trace($Nom_Fichier,$fp,$Trace);
			Fin_Trace($Nom_Fichier,$fp);
  return "1";
 }
			Fin_Trace($Nom_Fichier,$fp);
	return "0";
}



	/**
	 * @param string $LOGI
	 * @param string $PASSWD
	* @param string $NCLI
	* @return Customer_Informations
	*/


	public function Get_Customer_Informations($LOGI,$PASSWD,$NCLI) {

		

		$Nom_Fichier='SCD';

		$Res_connection=Init_Connection_SCD();

		$fp =  Debut_Trace($Nom_Fichier);
		if($Res_connection==1) {
			$resultat=new Customer_Informations();
			$resultat->CODE_ERROR="1";
			$resultat->MSG_ERROR="Server is not accessible";
			$Trace=date("d-m-Y")." à ".date("H:i:s")." -> "."Erreur=".$resultat->MSG_ERROR."
		";
   $Trace.=$GLOBALS['System_ini'].$GLOBALS['ConnectError'];
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
			Fin_Trace($Nom_Fichier,$fp);
			return $resultat;
		}
$conn=$GLOBALS['$conn'];
		$sql="";
$sql .= "SELECT NCLI, RSSC, ADR1, ADR2, CPOS, VILL, CMPA, CLDO, EMAI,CLIV,ADLI,TELP FROM EURO4SCD/CLIENT ";
$sql .= " WHERE NCLI = '".$NCLI."'";
$sql .= " FETCH FIRST 1 ROWS ONLY";
$stmt = db2_exec($conn, $sql);
if(!$stmt)
{
			$resultat=new Customer_Informations();
			$resultat->CODE_ERROR="1";
			$resultat->MSG_ERROR=db2_stmt_error();
			$Trace=date("d-m-Y")." à ".date("H:i:s")." -> "."Erreur=".$resultat->MSG_ERROR."
		";
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
			Fin_Trace($Nom_Fichier,$fp);
			return $resultat;
}
$i=0;
$items=array();
while($row = db2_fetch_array($stmt)){
	$Trace=$sql."
		";
	$Trace.="IP=".getenv("REMOTE_ADDR")." ".date("d-m-Y")." à ".date("H:i:s")." -> "."TELP=".$row[11]."
		";
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
			array_push( $items,$row[0],
			$row[1],
			$row[2],
			$row[3],
			$row[4],
			$row[5],
			$row[6],
			$row[7],
			$row[8],
			$row[9],
			$row[10],
			$row[11]
			);
$i++;
}
if($i==0)
{
			$resultat=new Customer_Informations();
			$resultat->CODE_ERROR="1";
			$resultat->MSG_ERROR="Aucune donnee pour le client   NCLI=".$NCLI;
			$Trace=date("d-m-Y")." à ".date("H:i:s")." -> "."Erreur=".$resultat->MSG_ERROR."
		";
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
			Fin_Trace($Nom_Fichier,$fp);
			return $resultat;
}
else
{
$resultat=new Customer_Informations();
			$resultat->CLIENT_NCLI=$items[0];
			$resultat->CLIENT_RSSC=$items[1];
			$resultat->CLIENT_ADR1=$items[2];
			$resultat->CLIENT_ADR2=$items[3];
			$resultat->CLIENT_CPOS=$items[4];
			$resultat->CLIENT_VILL=$items[5];
			$resultat->CLIENT_CMPA=$items[6];
			$resultat->CLIENT_CLDO=$items[7];
			$resultat->CLIENT_EMAIL=$items[8];
			$Trace="CLIV=".$items[9]." **ADLI=".$items[10]."**
			";
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
/* on rechercge l'adresse de livraison si demandé */
if(trim($items[9])==trim($NCLI) && trim($items[10])=="")
{
			$resultat->CLIENT_LIVR_RSSC=$items[1];
			$resultat->CLIENT_LIVR_ADR1=$items[2];
			$resultat->CLIENT_LIVR_ADR2=$items[3];
			$resultat->CLIENT_LIVR_CPOS=$items[4];
			$resultat->CLIENT_LIVR_VILL=$items[5];
			$resultat->CLIENT_LIVR_TELP=$items[11];
} 
elseif(trim($items[9])!=trim($NCLI) && trim($items[10])=="")
{
		$sql="";
		$sql .= "SELECT NCLI, RSSC, ADR1, ADR2, CPOS, VILL, TELP FROM EURO4SCD/CLIENT ";
		$sql .= " WHERE NCLI = '".$items[9]."'";
		$sql .= " FETCH FIRST 1 ROWS ONLY";
		$stmt = db2_exec($conn, $sql);
		$items_liv=array();
		$i=0;
		while($row = db2_fetch_array($stmt)){
			$Trace=$sql."
			";
		$Trace.="IP=".getenv("REMOTE_ADDR")." ".date("d-m-Y")." à ".date("H:i:s")." -> "."Recherche client livre=".$sql."
		";
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
			array_push( $items_liv,$row[0],
			$row[1],
			$row[2],
			$row[3],
			$row[4],
			$row[5],
			$row[6]
			);
		$i++;
		}
			$resultat->CLIENT_LIVR_RSSC=$items_liv[1];
			$resultat->CLIENT_LIVR_ADR1=$items_liv[2];
			$resultat->CLIENT_LIVR_ADR2=$items_liv[3];
			$resultat->CLIENT_LIVR_CPOS=$items_liv[4];
			$resultat->CLIENT_LIVR_VILL=$items_liv[5];
			$resultat->CLIENT_LIVR_TELP=$items_liv[6];
}
else
{
/* ici on a ADLI de renseigne*/
		$sql="";
		$sql .= "SELECT NTIE, RSSC, ADR1, ADR2, CPOS, VILL,TELP FROM EURO4SCD/GADRES ";
		$sql .= " WHERE TTIE='41' AND NTIE = '".$items[9]."' AND NADR = '".$items[10]."'";
		$sql .= " FETCH FIRST 1 ROWS ONLY";
		$stmt = db2_exec($conn, $sql);
		$items_liv=array();
		$i=0;
		while($row = db2_fetch_array($stmt)){
			$Trace=$sql."
			";
		$Trace.="IP=".getenv("REMOTE_ADDR")." ".date("d-m-Y")." à ".date("H:i:s")." -> "."Recherche Adresse liv=".$sql."
		";
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
			array_push( $items_liv,$row[0],
			$row[1],
			$row[2],
			$row[3],
			$row[4],
			$row[5],$row[6]
			);
		$i++;
		}
			$resultat->CLIENT_LIVR_RSSC=$items_liv[1];
			$resultat->CLIENT_LIVR_ADR1=$items_liv[2];
			$resultat->CLIENT_LIVR_ADR2=$items_liv[3];
			$resultat->CLIENT_LIVR_CPOS=$items_liv[4];
			$resultat->CLIENT_LIVR_VILL=$items_liv[5];
			$resultat->CLIENT_LIVR_TELP=$items_liv[6];
}
			
			
			$Trace="IP=".getenv("REMOTE_ADDR")." ".date("d-m-Y")." à ".date("H:i:s")." -> "."Demande NCLI=".$resultat->CLIENT_NCLI."
		";
			Enreg_Trace($Nom_Fichier,$fp,$Trace);
		
		Fin_Trace($Nom_Fichier,$fp);
}
		db2_close($conn);


		return $resultat;

	}


}

$server=new SoapServer('SOA_Eureka_SCD_WESTAG.wsdl',array('encoding'=>'ISO-8859-1'));
$server->setClass('WS_SCD');
$server->handle();

?>
	
