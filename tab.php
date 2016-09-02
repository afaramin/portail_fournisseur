<?php
$Tab=explode(";","LI;OP;JI;");
$TYPE=explode(";","2;4;5;");
$i=0;
		$Balise_Nom_deb="<Nom>";
		$Balise_Nom_fin="</Nom>";
		$Balise_Type_deb="<Type>";
		$Balise_Type_fin="</Type>";
		$Liste_Portail="";
		foreach($Tab as $cle=>$valeur)
		{
		
		if($valeur!=""){
		
		$Liste_Portail=$Liste_Portail.$Balise_Nom_deb.$valeur.$Balise_Type_deb.$TYPE[$i].$Balise_Type_fin.$Balise_Nom_fin;
		}
		$i=$i+1;
		}
$result="<?xml version='1.0' encoding='UTF-8'?>";

		$result="$result<WebService><Liste_portail>$Liste_Portail</Liste_portail><Type_Portail></Type_Portail><message>MSG</message></WebService>";
		echo $result;
?>