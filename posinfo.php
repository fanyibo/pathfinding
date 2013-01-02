<?php
if($_GET['x']!=NULL && $_GET['y']!=NULL && $_GET['x']>=0 && $_GET['x']<=543 && $_GET['y']>=0 && $_GET['y']<=600)
{
	$x = $_GET['x'];
	$y = $_GET['y'];
	include "structure.php";
	echo $graph->getPosInfo($x,$y);
}
?>