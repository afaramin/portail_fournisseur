<?php 
 session_start();
 ?>
<html>
<head>
<title>intranet SCD </title>
  <link rel="stylesheet"  type="text/css" href="./monstyle.css">
 </head>
 
<SCRIPT language="javascript">
	function AffCopyright() {
		document.write("<CENTER>Copyright SCD - Tous droits r�serv�s</CENTER>");	
	}
</SCRIPT>

 
 <?php 
 
 if (session_destroy() == TRUE )
 {
 	mail("andre.faramin@scd.fr" ,  "fin de session" , "�a marche, merci !"  );
 }
 ?>


<Frameset rows="15% ,*">
<frame src="bandeau.html"  name="bandeau" >
	<Frameset cols="15% ,*">
 		<frame src="menu_simple.html" name="menu">'
		<frame src="login.html" name ="principal">
	</Frameset>
</Frameset>


</html>