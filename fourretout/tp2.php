<HTML>
<HEAD>
<title>Reponse</title>
</HEAD>
<BODY>

<?php 


if (isset($_POST['CodeUSer'])  and !empty($_POST['MotPasse']))
 {
    echo  "bonjour&nbsp;" . $_POST['prenom'];
} else {		
	echo "Donnée non comprise";	
}
?>

</BODY>
</HTML>