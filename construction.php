<?php
if($_GET['startX']==NULL || $_GET['startY']==NULL || $_GET['endX']==NULL || $_GET['endY']==NULL || $_GET['Algorithm']==NULL)
{
	
}
else
{
	$startX = $_GET['startX'];
	$startY = $_GET['startY'];
	$endX = $_GET['endX'];
	$endY = $_GET['endY'];
	$algorithm = $_GET['Algorithm'];
	$bothAlgorithm = false;
	if($algorithm == "BOTH")
	{
		$bothAlgorithm = true;
	}
	
	include "structure.php";

	// Edge Total: 284.
	$graph->fillEdgeWeight();
	
	$startNode = $graph->generateNearestRdPtAndUpdate($startX,$startY);
	$endNode = $graph->generateNearestRdPtAndUpdate($endX,$endY);		
	$saveFileName = NULL;
	
	if(!$bothAlgorithm)
	{
		$pathresult = NULL;
		$pathcost = NULL;
		if($algorithm == "Dijkstra")
		{
			$pathresult = $graph->DijkstraAlgorithm($startNode,$endNode);
			if($pathresult!=NULL)
			{
				$pathcost = end($pathresult)->gcost;
			}
			else
			{
				$pathcost = "No Path Found!";
			}
			$saveFileName = $_SERVER['DOCUMENT_ROOT']."/AIProject/DijkstraPath.xml";
		}
		if($algorithm == "AStar")
		{
			$pathresult = $graph->AStarAlgorithm($startNode,$endNode);
			if($pathresult!=NULL)
			{
				$pathcost = end($pathresult)->fcost;
			}
			else
			{
				$pathcost = "No Path Found!";
			}
			$saveFileName = $_SERVER['DOCUMENT_ROOT']."/AIProject/AStarPath.xml";
		}
		
		
		$xmlstring = "<?xml version='1.0' encoding='utf-8'?>\n";
		$xmlstring = $xmlstring."<Path>\n";
		$xmlstring = $xmlstring."<From>\n";
		$xmlstring = $xmlstring.	"<Point>\n";
		$xmlstring = $xmlstring.		"<X>".$startNode->x."</X>\n";
		$xmlstring = $xmlstring.		"<Y>".$startNode->y."</Y>\n";
		$xmlstring = $xmlstring.	"</Point>\n";
		$xmlstring = $xmlstring."</From>\n";
		$xmlstring = $xmlstring."<To>\n";
		$xmlstring = $xmlstring.	"<Point>\n";
		$xmlstring = $xmlstring.		"<X>".$endNode->x."</X>\n";
		$xmlstring = $xmlstring.		"<Y>".$endNode->y."</Y>\n";
		$xmlstring = $xmlstring.	"</Point>\n";
		$xmlstring = $xmlstring."</To>\n";
		$xmlstring = $xmlstring."<FromInfo>".$graph->getPosInfo($startX,$startY)."</FromInfo>\n";
		$xmlstring = $xmlstring."<EndInfo>".$graph->getPosInfo($endX,$endY)."</EndInfo>\n";
		$xmlstring = $xmlstring."<Route>\n";
		foreach($pathresult as $path)
		{
			$xmlstring = $xmlstring.	"<Point>\n";
			$xmlstring = $xmlstring.		"<X>".$path->x."</X>\n";
			$xmlstring = $xmlstring.		"<Y>".$path->y."</Y>\n";
			$xmlstring = $xmlstring.	"</Point>\n";
		}
		$xmlstring = $xmlstring."</Route>\n";
	
		$xmlstring = $xmlstring."<Cost>".$pathcost."</Cost>\n";	
		$xmlstring = $xmlstring."<DExpandND>".$graph->nodeDExpand."</DExpandND>\n";
		$xmlstring = $xmlstring."<DReturnND>".$graph->nodeDReturn."</DReturnND>\n";	
		$xmlstring = $xmlstring."<DReturnED>".$graph->edgeDReturn."</DReturnED>\n";	
		$xmlstring = $xmlstring."<AExpandND>".$graph->nodeAExpand."</AExpandND>\n";
		$xmlstring = $xmlstring."<AReturnND>".$graph->nodeAReturn."</AReturnND>\n";	
		$xmlstring = $xmlstring."<AReturnED>".$graph->edgeAReturn."</AReturnED>\n";	
		$xmlstring = $xmlstring."<DTime>".$graph->DTime."</DTime>\n";	
		$xmlstring = $xmlstring."<ATime>".$graph->ATime."</ATime>\n";	
		$xmlstring = $xmlstring."</Path>\n";
		
		$file = fopen($saveFileName,"w+");
		fwrite($file,$xmlstring);
		fclose($file);
		
		unset($pathresult);
		unset($pathcost);
	}
	else
	{
		$Dresult = $graph->DijkstraAlgorithm($startNode,$endNode);
		$Aresult = $graph->AStarAlgorithm($startNode,$endNode);
		$saveFileName = $_SERVER['DOCUMENT_ROOT']."/AIProject/Path.xml";

		$xmlstring = "<?xml version='1.0' encoding='utf-8'?>\n";
		$xmlstring = $xmlstring."<Path>\n";
		$xmlstring = $xmlstring."<From>\n";
		$xmlstring = $xmlstring.	"<Point>\n";
		$xmlstring = $xmlstring.		"<X>".$startNode->x."</X>\n";
		$xmlstring = $xmlstring.		"<Y>".$startNode->y."</Y>\n";
		$xmlstring = $xmlstring.	"</Point>\n";
		$xmlstring = $xmlstring."</From>\n";
		$xmlstring = $xmlstring."<To>\n";
		$xmlstring = $xmlstring.	"<Point>\n";
		$xmlstring = $xmlstring.		"<X>".$endNode->x."</X>\n";
		$xmlstring = $xmlstring.		"<Y>".$endNode->y."</Y>\n";
		$xmlstring = $xmlstring.	"</Point>\n";
		$xmlstring = $xmlstring."</To>\n";
		$xmlstring = $xmlstring."<FromInfo>".$graph->getPosInfo($startX,$startY)."</FromInfo>\n";
		$xmlstring = $xmlstring."<EndInfo>".$graph->getPosInfo($endX,$endY)."</EndInfo>\n";
		$xmlstring = $xmlstring."<DRoute>\n";
		foreach($Dresult as $path)
		{
			$xmlstring = $xmlstring.	"<Point>\n";
			$xmlstring = $xmlstring.		"<X>".$path->x."</X>\n";
			$xmlstring = $xmlstring.		"<Y>".$path->y."</Y>\n";
			$xmlstring = $xmlstring.	"</Point>\n";
		}
		$xmlstring = $xmlstring."</DRoute>\n";
		
		$xmlstring = $xmlstring."<ARoute>\n";
		foreach($Aresult as $path)
		{
			$xmlstring = $xmlstring.	"<Point>\n";
			$xmlstring = $xmlstring.		"<X>".$path->x."</X>\n";
			$xmlstring = $xmlstring.		"<Y>".$path->y."</Y>\n";
			$xmlstring = $xmlstring.	"</Point>\n";
		}
		$xmlstring = $xmlstring."</ARoute>\n";
		if($Dresult==NULL)
		{
			$xmlstring = $xmlstring."<DCost>No Path Found!</DCost>\n";	
		}
		else
		{
			$xmlstring = $xmlstring."<DCost>".end($Dresult)->gcost."</DCost>\n";	
		}
		if($Aresult==NULL)
		{
			$xmlstring = $xmlstring."<ACost>No Path Found!</ACost>\n";	
		}
		else
		{
			$xmlstring = $xmlstring."<ACost>".end($Aresult)->fcost."</ACost>\n";
		}		
		$xmlstring = $xmlstring."<DExpandND>".$graph->nodeDExpand."</DExpandND>\n";
		$xmlstring = $xmlstring."<DReturnND>".$graph->nodeDReturn."</DReturnND>\n";	
		$xmlstring = $xmlstring."<DReturnED>".$graph->edgeDReturn."</DReturnED>\n";	
		$xmlstring = $xmlstring."<AExpandND>".$graph->nodeAExpand."</AExpandND>\n";
		$xmlstring = $xmlstring."<AReturnND>".$graph->nodeAReturn."</AReturnND>\n";	
		$xmlstring = $xmlstring."<AReturnED>".$graph->edgeAReturn."</AReturnED>\n";	
		$xmlstring = $xmlstring."<DTime>".$graph->DTime."</DTime>\n";	
		$xmlstring = $xmlstring."<ATime>".$graph->ATime."</ATime>\n";				
		$xmlstring = $xmlstring."</Path>\n";
		
		$file = fopen($saveFileName,"w+");
		fwrite($file,$xmlstring);
		fclose($file);
		
		unset($Dresult);
		unset($Aresult);
	}
	
	unset($graph->nodes);
	unset($graph->edges);
	unset($graph);
	
	echo "<script>";
	echo "window.location = 'http://localhost/AIProject/index.php';";
	echo "</script>";
}
?>