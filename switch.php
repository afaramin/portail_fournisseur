1.<?php
session_start();


  
	    if (isset($_SESSION['P3TARIF']))
	    {
			$P3TARIF =	$_SESSION['P3TARIF'];
	    	switch($P3TARIF) {

 	        	case '   ' :
 	        		header('location: listCodeUsine.php');
 	        	    break;

 	        	default :
 	        	    header('location: ListeBL.php');
 	        	    break;
 	        		    	        }}
 		else
 		{
 			header('location: listCodeUsine.php');
 		}
?>
 	       