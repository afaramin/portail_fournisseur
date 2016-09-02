<?php  
// fond d'écran en couleur
function row_color($i)
{
	$bgcolor1 = "white";
	$bgcolor2 = "yellow";
	if (($i % 2) == 0){
		return $bgcolor1 ;}
		else
		{return $bgcolor2 ;
	}
}
?>