<html>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script> 
<script type="text/javascript" src="./js/jquery.dataTables.js"></script> 
 <style type="text/css" title="currentStyle">
			@import "css/jquery.dataTables.css";
			
		</style>


<body>
<?php
 header('Content-Type: text/html;charset=UTF-8');
function SaveFichier($Fich_xml)
{
$handle = fopen("c:\\SCDXML\\SCDXML.xml", "a");
if(!$handle)
echo "Impossible d'ouvrir le fichier";
else {
fwrite($handle, $Fich_xml);
fclose($handle); 
}
}
?>
<?php
// Modify the URL here - note the "?wsdl" at the end
$client = new SoapClient("http://eurekadm:10088/Eurekaprim/WS_PHP_Eurekaprim.wsdl");
//$res=$client->Test_Serveur();

//echo  $res."<br>";

$result=$client->Liste_Portail("ees@eureka-solutions.fr","EES");
foreach($result->RESU as $cle=>$valeur)
{
if($valeur!="")
	{
		
		try {
			//echo 'PORTAIL112='.$PORTAIL1;
			$PRIMI='[PA/REQ.'.$valeur.']';
			$result_primi=$client->Resoud_Primitives($PRIMI);
			if($result_primi->RESU=="")
			$result_primi->RESU=$valeur;
			//$result_primi->RESU=$valeur;
		}
		catch (Exception $exception)
		{echo 'Exception Resoud primitive:'.$exception;}


		echo "<b>".$result_primi->RESU."</b></br>";
		
	}
}

$PORTAIL='EESTA';

try {
$result_por=$client->Resoud_Portail($PORTAIL, '0', '20', '1', 'ees@eureka-solutions.fr', 'AFF', 'TEST', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');
}
		catch (Exception $exception)
		{echo 'Exception Resoud primitive:'.$exception;}


foreach($result_por->RESU as $cle=>$valeur_ret)
{
	if($valeur_ret!="")
	{
		if(strstr($valeur_ret,'#TITLE')){
		$valeur_ret = str_replace('#TITLE=', '', $valeur_ret);
		echo '<b>'.$valeur_ret.'</b></br>';
		}
		else
		{
		if(strstr($valeur_ret,'#COL'))
		{
		$valeur_ret = str_replace('#COL=', '', $valeur_ret);
		$Tab_head = explode ( ';', $valeur_ret );
		?>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example"> 
		<thead> 
        <tr> 
		<?php
		foreach($Tab_head as $Value)
		{ echo '<th>'.$Value.'</th>'; }
		?>
            
        </tr> 
    </thead>
	<tbody>
	<?php
		}
		else
		 {
		 $Tab_Chel = explode ( ';', $valeur_ret );?>
		 		  <tr class="gradeX"> 
		 <?php
		 foreach($Tab_Chel as $Value)
		{ echo '<td>'.$Value.'</td>'; }
		?>
		</tr>
		<?php
		 }
		}




}
}
?>
 <tfoot> 
        <tr> 
		<?php
		foreach($Tab_head as $Value)
		{ echo '<th>'.$Value.'</th>'; } ?>
		</tr> 
    </tfoot> 


  
<script type="text/javascript">
$(document).ready(function() {
    $('#example').dataTable();
} );</script>



</body>
</html>