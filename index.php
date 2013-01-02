<!DOCTYPE HTML>
<html>
<head>
<style>
body {
	width: 1200px;
	padding: 0px;
	margin-top: 0px;
	margin-right: auto;
	margin-bottom: 0px;
	margin-left: auto;
}
.header {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	clear: both;
	height: auto;
	width: 100%;
	margin-top: 10px;
	margin-right: auto;
	margin-left: auto;
	text-align: center;
	float: left;
}
.footer {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	clear: both;
	height: auto;
	width: 100%;
	margin-top: 20px;
	margin-right: auto;
	margin-left: auto;
	text-align: center;
	float: left;
	margin-bottom: 15px;
	font-size: 11px;
	font-style: italic;
}
#workzone {
	width: 544px;
	height:625px;
	margin-top:20px;
	float:left;
	clear:none;
	background-image:url(images/map2.jpg);
	background-repeat:no-repeat;
}
#myCanvas {
	float: left;
}
.fontTitle {
	width:100%;
	height:auto;
	float:left;
	text-align:center;
	font-family: Georgia, 'Times New Roman', Times, serif;
	font-size: 14px;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #CCC;
	padding-top: 5px;
	padding-bottom: 5px;
	clear: both;
	background-color: #E0E8FF;
}
.locationArea {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	clear: left;
	float: left;
	height: 195px;
	width: 350px;
	padding-right: 5px;
	padding-left: 5px;
	padding-bottom: 10px;
	border-bottom-width: thin;
	border-bottom-style: solid;
	border-bottom-color: #CCC;
	background-color: #FAFEFF;
	padding-top: 5px;
}
.compareArea {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	background-color: #FFF9FE;
	float: left;
	height: auto;
	width: 580px;
	padding-top: 10px;
	padding-right: 5px;
	padding-left: 5px;
	clear: left;
}
.btStyle {
	font-family: Georgia, "Times New Roman", Times, serif;
	background-color: #8583FF;
	text-align: center;
	height: 30px;
	width: 80px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	margin-top: 10px;
	cursor: pointer;
	font-size: 12px;
}
.btStyle-focus {
	font-family: Georgia, "Times New Roman", Times, serif;
	background-color: #7958FF;
	text-align: center;
	height: 30px;
	width: 80px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	margin-top: 10px;
	cursor: pointer;
	font-size: 12px;
}
.infocontent {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	clear: both;
	float: left;
	height: auto;
	width: 100%;
	margin-top: 20px;
	margin-bottom: 20px;
}
.infoTitle {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: large;
	font-weight: bold;
	clear: both;
	float: left;
	height: auto;
	width: 100%;
}
.infoContent {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	clear: both;
	float: left;
	height: auto;
	width: 100%;
	margin-top: 10px;
	margin-bottom: 10px;
	font-size: small;
}
.infoBlockDescription {
	float: left;
	height: auto;
	width: 45%;
	padding: 5px;
	clear: left;
	margin-bottom: 20px;
}
.infoBlockAlgorithm {
	float: right;
	height: auto;
	width: 50%;
	padding: 5px;
	clear: right;
}
.fontMapinfo {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	font-size: x-small;
	text-align: center;
	height: auto;
	width: 100%;
	float: left;
	margin-top: 5px;
	font-style: normal;
}
.MapInfo {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
	float: right;
	height: 195px;
	width: 214px;
	clear: right;
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 10px;
	padding-left: 20px;
	border-bottom-width: thin;
	border-left-width: thin;
	border-bottom-style: solid;
	border-left-style: solid;
	border-bottom-color: #CCC;
	border-left-color: #CCC;
	background-color: #FFF;
}
</style>

<script>
var isStartChosen = false; 	/* if the start point has been chosen, it is true, otherwise, false */
var isEndChosen = false;	/* Destination has been chosen -> true; otherwise false */
var startX = -1;
var startY = -1;
var endX = -1;
var endY = -1;
var pointWidth = 8;
var pointHeight = 8;



function showPosInfo(x,y,type)
{
	if (x<0 || x>543 || y<0 || y>600)
	{ 
		if(type==1) // current position
    		{
				document.getElementById("currPosInfo").innerHTML="";
			}
			if(type==2) // start
    		{
				document.getElementById("startPosInfo").innerHTML="";
			}
			if(type==3) // goal
    		{
				document.getElementById("endPosInfo").innerHTML="";
			}
		return;
	}
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp = new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp.onreadystatechange=function()
  	{
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	{
			if(type==1) // current position
    		{
				document.getElementById("currPosInfo").innerHTML=xmlhttp.responseText;
			}
			if(type==2) // start
    		{
				document.getElementById("startPosInfo").innerHTML=xmlhttp.responseText;
			}
			if(type==3) // goal
    		{
				document.getElementById("endPosInfo").innerHTML=xmlhttp.responseText;
			}
    	}
  	}
	xmlhttp.open("GET","posinfo.php?x="+x+"&y="+y,true);
	xmlhttp.send();
}

/* 
Reset start location information
Including boolean value, coordinate value, and location info.
*/
function resetStartInfo()
{
	isStartChosen = false;
	var canvas = document.getElementById('myCanvas');
	var ctx = canvas.getContext('2d');
	ctx.clearRect(startX, startY, pointWidth, pointHeight);
	document.getElementById("startCoordinate").innerHTML = "";
	document.getElementById("resetStart").innerHTML = "";
	document.getElementById("startX").value = "";
	document.getElementById("startY").value = "";
	document.getElementById("startPosInfo").innerHTML="";
}
/* Reset destination info. */
function resetEndInfo()
{
	isEndChosen = false;
	var canvas = document.getElementById('myCanvas');
	var ctx = canvas.getContext('2d');
	ctx.clearRect(endX, endY, pointWidth, pointHeight);
	document.getElementById("endCoordinate").innerHTML = "";
	document.getElementById("resetEnd").innerHTML = "";
	document.getElementById("endX").value = "";
	document.getElementById("endY").value = "";
	document.getElementById("endPosInfo").innerHTML="";
}

function writeMessage(canvas, message) {
	var context = canvas.getContext('2d');
	context.clearRect(0, 0, canvas.width, canvas.height);
	context.font = '15pt Calibri';
	context.fillStyle = 'black';
	context.fillText(message, 10, 25);
}
/*
Get the coordiantes of the mouse in the canvas then return
*/
function getMousePos(canvas, evt) {
	var rect = canvas.getBoundingClientRect();
	var root = document.documentElement;	
	var mouseX = evt.clientX - rect.left - root.scrollTop;
	var mouseY = evt.clientY - rect.top - root.scrollLeft;
	return {
		x: mouseX,
		y: mouseY
	};
}

window.onload = function() {
	var canvas = document.getElementById('myCanvas');
	var context = canvas.getContext('2d');
	
	canvas.addEventListener('mousemove', function(evt) {
		var mousePos = getMousePos(canvas, evt);
		var message = "( " + mousePos.x + ", " + mousePos.y +" )";
		showPosInfo(mousePos.x,mousePos.y,1);
		document.getElementById("currLocCoordinate").innerHTML = message;
	}, false);
	
	canvas.addEventListener('mouseout', function(evt) {
		document.getElementById("currLocCoordinate").innerHTML = "Out of Map";
		document.getElementById("currPosInfo").innerHTML="";
	}, false);
	
	canvas.addEventListener('dblclick', function(evt) {
		var mousePos = getMousePos(canvas, evt);
		var message = "( " + mousePos.x + ", " + mousePos.y +" )";
		
		var c = document.getElementById("myCanvas");
		var ctx = c.getContext("2d");

		if(!isStartChosen)
		{
			document.getElementById("startCoordinate").innerHTML = message;
			document.getElementById("resetStart").innerHTML = "<img src='images/image30.png' width='16' height='16' alt='Reset' title='Reset' style='cursor:pointer' onclick='resetStartInfo();'/>";
			isStartChosen = true;
			startX = mousePos.x;
			startY = mousePos.y;
			ctx.fillStyle="#FF0000";
			ctx.fillRect(mousePos.x, mousePos.y, pointWidth, pointHeight);
			document.getElementById("startX").value = mousePos.x;
			document.getElementById("startY").value = mousePos.y;
			showPosInfo(mousePos.x,mousePos.y,2);
		}
		else if(!isEndChosen && isStartChosen)
		{
			document.getElementById("endCoordinate").innerHTML = message;
			document.getElementById("resetEnd").innerHTML = "<img src='images/image30.png' width='16' height='16' alt='Reset' title='Reset' style='cursor:pointer' onclick='resetEndInfo();'/>";
			isEndChosen = true;
			endX = mousePos.x;
			endY = mousePos.y;
			ctx.fillStyle="#FF0000";
			ctx.fillRect(mousePos.x, mousePos.y, pointWidth, pointHeight);
			document.getElementById("endX").value = mousePos.x;
			document.getElementById("endY").value = mousePos.y;
			showPosInfo(mousePos.x,mousePos.y,3);
		}
	}, false);
};

function getDirectionDijkstra()
{
	var sX = document.getElementById("startX").value;
	var sY = document.getElementById("startY").value;
	var eX = document.getElementById("endX").value;
	var eY = document.getElementById("endY").value;
	if(sX!="" && sY!="" && eX!="" && eY!="")
	{
		document.getElementById("Algorithm").value = "Dijkstra";
		document.getElementById("navinfo").submit();
	}
}
function getDirectionAStar()
{
	var sX = document.getElementById("startX").value;
	var sY = document.getElementById("startY").value;
	var eX = document.getElementById("endX").value;
	var eY = document.getElementById("endY").value;
	if(sX!="" && sY!="" && eX!="" && eY!="")
	{
		document.getElementById("Algorithm").value = "AStar";
		document.getElementById("navinfo").submit();
	}
}
function getDirectionBoth()
{
	var sX = document.getElementById("startX").value;
	var sY = document.getElementById("startY").value;
	var eX = document.getElementById("endX").value;
	var eY = document.getElementById("endY").value;
	if(sX!="" && sY!="" && eX!="" && eY!="")
	{
		document.getElementById("Algorithm").value = "BOTH";
		document.getElementById("navinfo").submit();
	}
}

</script>


<?php
class Point
{
	public $x;
	public $y;
	public function __construct($x,$y)
	{
		$this->x = $x;
		$this->y = $y;
	}
}

$Dfilename = $_SERVER['DOCUMENT_ROOT']."/AIProject/DijkstraPath.xml";
$Afilename = $_SERVER['DOCUMENT_ROOT']."/AIProject/AStarPath.xml";
$Bothfilename = $_SERVER['DOCUMENT_ROOT']."/AIProject/Path.xml";

$drawPath = false;
$startPt = NULL;
$endPt = NULL;
$DPathArr = array();
$APathArr = array();
$dCost = 0;
$aCost = 0;

$dEXND = 0;
$dREND = 0;
$dREED = 0;
$aEXND = 0;
$aREND = 0;
$aREED = 0;

$dtime = 0;
$atime = 0;

$startInfo = "";
$endInfo = "";

if (is_readable($Dfilename)) 
{
	$drawPath = true;
	$xml = simplexml_load_file($Dfilename);
	
	foreach($xml->children() as $child)
	{
		if($child->getName() == "From")
		{
			$startPt = new Point($child->Point->X,$child->Point->Y);
		}
		if($child->getName() == "To")
		{
			$endPt = new Point($child->Point->X,$child->Point->Y);
		}
		if($child->getName() == "FromInfo")
		{
			$startInfo = $child;
		}
		if($child->getName() == "EndInfo")
		{
			$endInfo = $child;
		}
		if($child->getName() == "Route")
		{
			foreach($child as $element)
			{
				array_push($DPathArr, new Point($element->X, $element->Y));
			}	
		}
		if($child->getName() == "Cost")
		{
			$dCost = $child;
		}
		
		if($child->getName() == "DExpandND")
		{
			$dEXND = $child;
		}
		if($child->getName() == "DReturnND")
		{
			$dREND = $child;
		}
		if($child->getName() == "DReturnED")
		{
			$dREED = $child;
		}
		if($child->getName() == "DTime")
		{
			$dtime = $child;
		}
	}
}
if (is_readable($Afilename)) 
{
	$drawPath = true;
	$xml = simplexml_load_file($Afilename);
	
	foreach($xml->children() as $child)
	{
		if($child->getName() == "From")
		{
			$startPt = new Point($child->Point->X,$child->Point->Y);
		}
		if($child->getName() == "To")
		{
			$endPt = new Point($child->Point->X,$child->Point->Y);
		}
		if($child->getName() == "FromInfo")
		{
			$startInfo = $child;
		}
		if($child->getName() == "EndInfo")
		{
			$endInfo = $child;
		}
		if($child->getName() == "Route")
		{
			foreach($child as $element)
			{
				array_push($APathArr, new Point($element->X, $element->Y));
			}
		}
		if($child->getName() == "Cost")
		{
			$aCost = $child;
		}
	
		if($child->getName() == "AExpandND")
		{
			$aEXND = $child;
		}
		if($child->getName() == "AReturnND")
		{
			$aREND = $child;
		}
		if($child->getName() == "AReturnED")
		{
			$aREED = $child;
		}
		if($child->getName() == "ATime")
		{
			$atime = $child;
		}
	}
}
if (is_readable($Bothfilename)) 
{
	$drawPath = true;
	$xml = simplexml_load_file($Bothfilename);
	
	foreach($xml->children() as $child)
	{
		if($child->getName() == "From")
		{
			$startPt = new Point($child->Point->X,$child->Point->Y);
		}
		if($child->getName() == "To")
		{
			$endPt = new Point($child->Point->X,$child->Point->Y);
		}
		if($child->getName() == "FromInfo")
		{
			$startInfo = $child;
		}
		if($child->getName() == "EndInfo")
		{
			$endInfo = $child;
		}
		if($child->getName() == "DRoute")
		{
			foreach($child as $element)
			{
				array_push($DPathArr, new Point($element->X, $element->Y));
			}	
		}
		if($child->getName() == "ARoute")
		{
			foreach($child as $element)
			{
				array_push($APathArr, new Point($element->X, $element->Y));
			}	
		}
		if($child->getName() == "DCost")
		{
			$dCost = $child;
		}
		if($child->getName() == "ACost")
		{
			$aCost = $child;
		}
	
		if($child->getName() == "DExpandND")
		{
			$dEXND = $child;
		}
		if($child->getName() == "DReturnND")
		{
			$dREND = $child;
		}
		if($child->getName() == "DReturnED")
		{
			$dREED = $child;
		}
		if($child->getName() == "AExpandND")
		{
			$aEXND = $child;
		}
		if($child->getName() == "AReturnND")
		{
			$aREND = $child;
		}
		if($child->getName() == "AReturnED")
		{
			$aREED = $child;
		}
		if($child->getName() == "DTime")
		{
			$dtime = $child;
		}
		if($child->getName() == "ATime")
		{
			$atime = $child;
		}
	}
}
?>


<title>Path-finding</title>
</head>
<body>
<div class="header">
	<span style="font:Georgia; font-size:18px"><strong>Implementation and Comparison of <em>A* Algorithm</em> &amp; <em>Dijkstra Algorithm</em> in Navigation</strong></span><br>
	<span>Yibo Fan</span>
<!-- End of header --></div>

<div onselectstart="return false" id="workzone">
	<canvas id="myCanvas" width="544" height="600"></canvas>
    <div class="fontMapinfo">Map: Syracuse University (screenshot from Google Map)</div>
<!-- End of canvas --></div>

<div id="runtime" style="width:600px; height:600px; margin-top:20px; float:right; clear:right; border:#CCCCCC thin solid; background: #FFF9FE;">
  <div class="fontTitle"><strong>Runtime Information</strong></div>
      <div class="MapInfo">
        <span><strong>Graph Information</strong><br>
        </span>
        <span>Intersection: 48<br>
        </span>
        <span>Roads: 21<br>
        </span>
        <span>Node Contructed: 87<br>
        </span>
        <span>Edge Contructed: 195<br></span>
        <br>
        <span><strong>Program Information</strong><br>
        </span>
        <span>HTML5 + PHP 5.3 + AJAX<br>
        </span>
        <br>
      </div>
  
	<div class="locationArea">
		<table width="340" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><em><strong>Current Pos:</strong></em></td>
				<td colspan="2"><span id="currLocCoordinate" style="color:#3366CC"></span></td>
			</tr>
			<tr>
                <td>&nbsp;</td>
                <td colspan="2"><span id="currPosInfo" style="color:#3366CC"></span></td>
            </tr>
            <tr>
                <td width="30%"><strong><u>Start Place</u>:</strong></td>
                <td width="53%">
                <span id="startCoordinate" style="color:#7369FF">
                <?php
				if($drawPath)
				{
					echo "(".$startPt->x.", ".$startPt->y.")";
				}
				?>
                </span>
                </td>
                <td width="17%" id="resetStart"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2"><span id="startPosInfo" style="color:#7369FF"><?php echo $startInfo; ?></span></td>
            </tr>
            <tr>
            	<td colspan="3">&nbsp;</td>
           	</tr>
            <tr>
            	<td><strong><u>Destination</u>:</strong></td>
            	<td>
                <span id="endCoordinate" style="color:#537FD4">
                <?php
                if($drawPath)
				{
					echo "(".$endPt->x.", ".$endPt->y.")";
				}
				?>
                </span></td>
            	<td id="resetEnd"></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            	<td colspan="2"><span id="endPosInfo" style="color:#537FD4"><?php echo $endInfo; ?></span></td>
            </tr>
            <tr>
            	<td colspan="3" align="right">
                <form id="navinfo" action="construction.php" method="get">
                    <input type="hidden" id="startX" name="startX" />
                    <input type="hidden" id="startY" name="startY" />
                    <input type="hidden" id="endX" name="endX" />
                    <input type="hidden" id="endY" name="endY" />
                    <input type="hidden" id="Algorithm" name="Algorithm" />
                    <input name="calculateDijkstra" type="button" id="calculateDijkstra" value="Dijkstra" class="btStyle" onMouseOver="this.className='btStyle-focus'" onMouseOut="this.className='btStyle'" onClick="getDirectionDijkstra()"/>
                    <input name="calculateAStar" type="button" id="calculateAStar" value="A-Star" class="btStyle" onMouseOver="this.className='btStyle-focus'" onMouseOut="this.className='btStyle'" onClick="getDirectionAStar()"/>
                    <input name="calculateBoth" type="button" id="calculateBoth" value="Dijkstra & A*" class="btStyle" onMouseOver="this.className='btStyle-focus'" onMouseOut="this.className='btStyle'" onClick="getDirectionBoth()"/>
                </form>
                </td>
            </tr>
        </table>
	<!-- End of locationArea --></div>
   
  <div class="compareArea">
	<div style="width:auto; height:auto; text-align:center; padding-bottom:5px; border-bottom:thin solid #CCC"><span style="color:#FF0000"><strong>Dijkstra's Algorithm</strong></span></div>
		<div style="width:auto; height:auto; padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:10px; float:left; clear:both; border-bottom:thin solid #CCC; margin-bottom:10px">
        
        <div style="width: 280px; height: 130px; border-right:thin #CCC solid; float:left; clear:left;">
        <span style="color:#F6C">
        <U>Path Cost:</U>
		<?php 
		echo $dCost;
		?>
        </span><br>
        <span style="color:#66F">
        <U>Time taken:</U>
        <?php
		echo $dtime;
        ?>
        <br></span>
        <span style="color:#603">
        <U>Number of Nodes Expanded:</U>
        <?php
		echo $dEXND;
        ?>
        <br></span>
        <span style="color:#06F">
        <U>Number of Nodes in Path:</U>
        <?php
		echo $dREND;
        ?>
        <br></span>
        <span style="color:#093">
        <U>Number of Edges in Path:</U>
        <?php
		echo $dREED;
        ?>
        <br></span>
        </div>
        <div style="width:270px; height:130px; float:left; margin-left: 15px;">
          <em>Time Complexity</em><br>
          <span style="font-size:12px">For any implementation of vertex set Q the running time is in <i>&Omicron;(|E|&middot;DKq+|V|&middot;EMq)</i>, where DKq and EMq are times needed to perform decrease key and extract minimum operations in set Q, respectively.</span>
        </div>
        </div>
        
        <div style="width:auto; height:auto; text-align:center; padding-bottom:5px; border-bottom:thin solid #CCC"><span style="color:#00F"><strong>A* Search Algorithm</strong></span></div>
		<div style="width:auto; height:auto; padding-left:5px; padding-right:5px; padding-top:5px; padding-bottom:10px; float:left; clear:both;">
        
        <div style="width: 280px; height: 120px; border-right:thin #CCC solid; float:left; clear:left;">
        <span style="color:#F6C">
        <U>Path Cost:</U>
		<?php 
		echo $aCost;
		?>
        <br></span>
        <span style="color:#66F">
        <U>Time taken:</U>
        <?php
		echo $atime;
        ?>
        <br></span>
        <span style="color:#603">
        <U>Number of Nodes Expanded:</U>
        <?php
		echo $aEXND;
        ?>
        <br></span>
        <span style="color:#06F">
        <U>Number of Nodes in Path:</U>
        <?php
		echo $aREND;
        ?>
        <br></span>
        <span style="color:#093">
        <U>Number of Edges in Path:</U>
        <?php
		echo $aREED;
        ?>
        <br></span>
        </div>
        <div style="width:270px; height:120px; float:left; margin-left: 15px;">
        <em>Time Complexity</em><br>
        <span style="font-size:12px">The time complexity of A* depends on the heuristic. The heuristic function h meets the following condition: <i> |h(x)-h*(x)| = &Omicron;(log(h*(x))) </i><br>[h* is the optimal heuristic]</span>
        </div>
        </div>
	<!-- End of compareArea --></div>
<!-- End of runtime --></div>

<div class="infocontent">
  <div class="infoBlockDescription">
	  <div class="infoTitle">Description</div>
	  <div class="infoContent">
      Navigaiton is popular nowadays and there are different knds of path-finding algorithms. <em>Dijkstra's Algorithm</em> and <em>A* Search Algorithm</em> are two basic and symbolic algorithms. Both <em>Dijkstra's Algorithm</em> and<em> A* Search Algorithm</em> can find the shortest path in a directed graph. But the idea and implementation of them are different. This program uses php to implement <em>Dijkstra's Algorithm</em> and <em>A* Search Algorithm</em> and compare them in  vehicle navigation (2D) based on North SU Campus Map
      .</div>
	<!-- End of infoBlockDescription --></div>
  <div class="infoBlockAlgorithm">
	<div class="infoTitle">Algorithms</div>
  		<div class="infoContent">
        <strong>Dijkstra's Algorithm</strong><br>
        Dijkstra's algorithm, conceived in 1956, is a graph search algorithm that solves the single-source path problem for a graph with nonnegative edge path costs and produces a shortest path tree.<br><br>
        We have a graph G = &lt;V, E&gt; in which V is the collection of vertices and E is all the edges. The Dijkstra's algorithm first picks up the the source vertex S and put it into set R. R is a set that contains all the vertices have already been visited and there is another set Q which contains all the vertices not in R (Q = V - R). Then the algorithm finds the vertex in Q that can be reached from S by the vertices in R with the shortest cost. And it removes this vertex from Q and add it into R. The algorithm continues doing this until the destination vertex is in set R.<br><br>
        The simplest implementation of the Dijkstra's algorithm stores vertices of set Q in an ordinary linked list or array, and extract minimum from Q is simply a linear search through all vertices in Q. In this case, the running time is O(|E|+|V|&sup2;)= O(|V|&sup2;).
For sparse graphs, that is, graphs with far fewer than O(|V|&sup2;) edges, Dijkstra's algorithm  runs in O((|E|+|V|)*log(|V|)).<br>
<br>
		Dijkstra’s Algorithm needs to know much about the graph and it goes into all direction, so it visits many nodes and many of them are not in the final solution. In figure 3.1, the pink square is the goal and the teal areas show what Dijkstra’s Algorithm scanned. The lightest teal areas are those farthest from the starting point, and thus form the “frontier” of the exploration.<br><br>
public function DijkstraAlgorithm($start, $goal) {<br>
&emsp;$this->initDijkstra(); /* initialize the gcost */<br>
&emsp;$start->gcost = 0;	/* the cost from start to start is 0 */<br>
&emsp;$Q = $this->nodes;	/* the set of all nodes in Graph*/<br>
&emsp;while(!empty($Q)) {<br>
&emsp;&emsp;$u = $this->getSmallestDistNode($Q); /* vertex in Q with smallest distance in dist[] */<br>
&emsp;&emsp;if($this->isSameNode($u,$goal)) /* reach goal */ {<br>
&emsp;&emsp;&emsp;break;<br>
&emsp;&emsp;}<br>
&emsp;&emsp;$Q = $this->deleteNode($u, $Q); /* remove u from Q */<br>
&emsp;&emsp;if($u->gcost == 999999) /* all remaining vertices are inaccessible from source */  {<br>
&emsp;&emsp;&emsp;break; <br>
&emsp;&emsp;}<br>
&emsp;&emsp;$list = $this->getLinkedND($u); /* get vertex u's neighbors */<br>
&emsp;&emsp;foreach($list as $v) {<br>
&emsp;&emsp;&emsp;$alt = $u->gcost + $this->getDistance($u->x,$u->y,$v->x,$v->y);<br>
&emsp;&emsp;&emsp;if($alt < $v->gcost) /* update */ {<br>
&emsp;&emsp;&emsp;&emsp;$v->gcost= $alt;<br>
&emsp;&emsp;&emsp;&emsp;$v->parent_x = $u->x;<br>
&emsp;&emsp;&emsp;&emsp;$v->parent_y = $u->y;<br>
&emsp;&emsp;&emsp;&emsp;$v->parent_gcost = $u->gcost;<br>
&emsp;&emsp;&emsp;}<br>
&emsp;&emsp;}<br>
&emsp;}	<br>
&emsp;$S = array(); /* path solution */<br>
&emsp;$u = $goal;<br>
&emsp;while($u->parent_x!=-1 && $u->parent_y!=-1) {<br>
&emsp;&emsp;array_unshift($S,$u);<br>
&emsp;&emsp;$u = $this->getNode($u->parent_x, $u->parent_y); /* Traverse from target to source */<br>
&emsp;}<br>
&emsp;array_unshift($S,$start);<br>
&emsp;return $S;<br>
}<br>

        
        
        </div>
        <div class="infoContent">
        <strong>A* Search Algorithm</strong><br>
A* Search Algorithm takes the advantages of both Dijkstra’s Algorithm and Greedy Best-First-Search Algorithm that are favoring vertices that are close to both the source and the goal. In order to accomplish this, A* uses f(n)=g(n)+h(n) where h(n) is the heuristic which estimates the cost from node n to the goal. A* not only examines less nodes, but also finds the shortest path.
The heuristic used in this program is max(Manhattan Distance, Diagonal Distance, Euclidean Distance).<br><br>
public function AStarAlgorithm($start, $goal)  {<br>
&emsp;if($this->isSameNode($start,$goal)) {<br>
&emsp;&emsp;return array($start);<br>
&emsp;}<br>
&emsp;$this->initAStar($goal);  /* initialization */<br>
&emsp;$openset = array();<br>
&emsp;$closedset = array();<br>
&emsp;$internd = array(); /* array of all the considered nodes (least fcost) */<br>	
&emsp;$current = $start;<br>
&emsp;array_push($openset, $current);<br>
&emsp;while(!empty($openset)) {<br>
&emsp;&emsp;$current = $this->getLowestFcNd($openset);<br>
&emsp;&emsp;array_push($internd, $current);<br>
&emsp;&emsp;if($this->isSameNode($current,$goal)) {<br>
&emsp;&emsp;&emsp;break;<br>
&emsp;&emsp;}<br>
&emsp;&emsp;$openset = $this->deleteNode($current, $openset);<br>
&emsp;&emsp;array_push($closedset, $current);<br>
&emsp;&emsp;$list = $this->getLinkedND($current);<br>
&emsp;&emsp;foreach($list as $neighbor) {<br>
&emsp;&emsp;&emsp;if($this->isNodeInArray($neighbor, $closedset)) {<br>
&emsp;&emsp;&emsp;&emsp;continue;<br>
&emsp;&emsp;&emsp;}<br>
&emsp;&emsp;&emsp;$tentative_g_score =  $current->gcost + $this->getDistance($current->x,$current->y,$neighbor->x,$neighbor->y);<br>
&emsp;&emsp;&emsp;if(!$this->isNodeInArray($neighbor, $openset) || ($tentative_g_score<=$neighbor->gcost)) {<br>
&emsp;&emsp;&emsp;&emsp;$neighbor->parent_x = $current->x;<br>
&emsp;&emsp;&emsp;&emsp;$neighbor->parent_y = $current->y;<br>
&emsp;&emsp;&emsp;&emsp;$neighbor->parent_gcost = $current->gcost;<br>
&emsp;&emsp;&emsp;&emsp;$neighbor->gcost = $tentative_g_score;<br>
&emsp;&emsp;&emsp;&emsp;$neighbor->fcost = $neighbor->gcost + $neighbor->hcost;<br>
&emsp;&emsp;&emsp;}<br>
&emsp;&emsp;&emsp;if(!$this->isNodeInArray($neighbor, $openset)) {<br>
&emsp;&emsp;&emsp;&emsp;array_push($openset, $neighbor);<br>
&emsp;&emsp;&emsp;}<br>
&emsp;&emsp;}<br>
&emsp;}<br>
&emsp;/* generate the path */	<br>
&emsp;$solution = array();  /* array of the nodes in order in solution */	<br>
&emsp;$temp = $current; // the goal	<br>
&emsp;while(!$this->isSameNode($temp,$start)) {<br>
&emsp;&emsp;array_unshift($solution, $temp);<br>
&emsp;&emsp;$temp = $this->findParent($temp, $internd);<br>
&emsp;}<br>
&emsp;array_unshift($solution, $temp);	<br>
&emsp;return $solution;<br>
}<br>
      </div>
	<!-- End of infoBlockDescription --></div>
  <div class="infoBlockDescription">
	<div class="infoTitle">Construction</div>
  		<div class="infoContent">
        <strong>Node</strong><br>
        This simulation of navigation is 2D based so it only need (x,y) coordinate to record the location.<br>
        struct <em>Node</em> has property <em>x</em> and <em>y</em> to represent  <em>x</em> and <em>y</em> coordinate of the node.<br>
        <em>fcost</em>/<em>gcost</em>/<em>hcost</em> are used when applying <em>A* Search Algorithm</em>.<br><br>
        class Node<br>
		{<br>
			&emsp;public $x; <br>
			&emsp;public $y;<br>
			&emsp;public $fcost;<br>
			&emsp;public $gcost; <br>
			&emsp;public $hcost; <br>
		}<br><br>
        <br><strong>Edge</strong><br>
        Struct <em>Edge</em> has start and end to represent the start node and the end node of the edge. Property <em>weight</em> stands for the weight of the edge.<br><br>
        class Edge <br>
        {<br>
			&emsp;public $start;<br>
			&emsp;public $end;<br>
			&emsp;public $weight;<br>
		}<br><br>
        <br><strong>Graph</strong><br>
        Let <em>G</em> represents the graph; <em>V</em> represents the vertices; and <em>E</em> represents the edges.<br>
        Then we have <em>G = &lt;V, E&gt;</em>.<br>
        The Graph in this program is a class and <em>V</em> is a collection of nodes (Type <em>Node</em>) and <em>E</em> is a collection of edges (Type <em>Edge</em>).<br><br>
        class Graph<br>
		{<br>
			&emsp;public $nodes = array();<br>
			&emsp;public $edges = array();<br>
            &emsp;......<br>
		}<br><br>
        In this program, each intersection is a node. Two nodes represent a part of a road. If node A and B are the two intersections in a  road and A and B are two adjacent intersections, then edge A to B can represent a part of this road. If the road is one-way, it only has one edge: A to B; If it is a two-way road, both A to B and B to A are the edges of the road.<br><br>
        <br><strong>Location</strong><br>
        When the user chooses the start location and the goal location that are all in coordinate form, the program first find the edge which is closest to the point, which means the distance from the point user chooses to the edge is the smallest among all the edge. This point should also be in the area of the edge which means the shadow of the point on the line (edge goes infinity in both directions) should be in the edge.
For example, if the shadow point is (X0, Y0) and the two endpoints of the edge is (X1, Y1) and (X2, Y2). Then (X0-X1) ×(X0-X2) ≤0 and (Y0-Y1) ×(Y0-Y2)≤0.<br>
Distance from point to line:<br>d=(|X2-X1| × |Y1-Y0|−|X1-X0| × |Y2-Y1|)÷(√((X2-X1)&sup2;+(Y2-Y1)&sup2;)).<br><br>
/* get the distance between point (x,y) and the line $edge */<br>
private function getPtLineDistance($x, $y, $edge) {<br>
&emsp;$x1 = $edge->start->x;<br>
&emsp;$y1 = $edge->start->y;<br>
&emsp;$x2 = $edge->end->x;<br>
&emsp;$y2 = $edge->end->y;<br>		
&emsp;return round(abs(($x2-$x1)*($y1-$y)-($x1-$x)*($y2-$y1)) / (sqrt(pow(($x2-$x1),2)+pow(($y2-$y1),2))), 2);	}<br><br>
/* return the shadow point coordnate of point (x,y) on line $edge */<br>
private function getPtShadowOnLine($x, $y, $edge) {<br>
&emsp;$x1 = $edge->start->x;<br>
&emsp;$y1 = $edge->start->y;<br>
&emsp;$x2 = $edge->end->x;<br>
&emsp;$y2 = $edge->end->y;<br>
&emsp;$a = $x2 - $x1;<br>
&emsp;$b = $y2 - $y1;<br>
&emsp;$c = $y * $b * $a;<br>
&emsp;$d = $x * $b * $a;<br>
&emsp;$e = ($x2 * $y1) - ($x1 * $y2);<br>
&emsp;$f = pow($a,2) + pow($b,2);<br>
&emsp;$newX = ($c+pow($a,2)*$x-$e*$b)/$f;<br>
&emsp;$newY = ($b*$c+$d*$a-$e*pow($b,2)+$f*$e)/($f*$a);<br>
&emsp;return new Node($newX,$newY);  }<br>
        </div>
	<!-- End of infoBlockDescription --></div>
<!-- End of infocontent --></div>

<div class="footer"><span>~ Dec 2012 ~</span></div>

<?php

if($drawPath) // draw path
{
	echo '<script>';
	echo 'var _context = document.getElementById("myCanvas").getContext("2d");';
	
	for($i=0;$i<count($DPathArr)-1;$i++)//($DPathArr as $element)
	{
		echo '_context.beginPath();';
  		echo '_context.moveTo('.$DPathArr[$i]->x.','.$DPathArr[$i]->y.');';
		echo '_context.lineTo('.$DPathArr[$i+1]->x.','.$DPathArr[$i+1]->y.');';
		echo '_context.lineWidth = 2;';
    	echo '_context.strokeStyle = "red";';
  		echo '_context.stroke();';
	}
	for($i=0;$i<count($APathArr)-1;$i++)//($DPathArr as $element)
	{
		echo '_context.beginPath();';
  		echo '_context.moveTo('.$APathArr[$i]->x.','.$APathArr[$i]->y.');';
		echo '_context.lineTo('.$APathArr[$i+1]->x.','.$APathArr[$i+1]->y.');';
		echo '_context.lineWidth = 1.5;';
    	echo '_context.strokeStyle = "blue";';
  		echo '_context.stroke();';
	}

	echo '</script>';
}

if(is_readable($Dfilename))
{
	unlink($Dfilename);
}
if(is_readable($Afilename))
{
	unlink($Afilename);
}
if(is_readable($Bothfilename))
{
	unlink($Bothfilename);
}

?>
</body>
</html>

