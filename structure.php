<?php
/*
* structure.php
* Author: Yibo Fan
* 
*/


/*
* Struct Node
*/
class Node
{
	public $x; /* x: horizontal coordinate of the node */
	public $y; /* y: vertical coordinate of the node */
	
	public $fcost; /* f-cost */
	public $gcost; /* g-cost */
	public $hcost; /* h-cost */
	
	public $parent_x; /* x coordinate of the parent, used in A-Star*/
	public $parent_y; /* y coordiante of the parent */
	public $parent_gcost;
	
	public function __construct($_x, $_y, $fcost=0, $gcost=0, $hcost=0, $parent_x=-1, $parent_y=-1, $parent_gcost=-1)
	{
		$this->x = $_x;
		$this->y = $_y;
		
		$this->fcost = $fcost;
		$this->gcost = $gcost;
		$this->hcost = $hcost;
		$this->parent_x = $parent_x;
		$this->parent_y = $parent_y;
		$this->parent_gcost = $parent_gcost;
	}
}


/*
Class Edge.
start: 	start node
end:	end node
weight:	weight of thr edge
*/
class Edge {	
	public $start;  /* type Node */
	public $end;	/* type Node */
	public $weight;	/* weight */
	public $info;	/* infomation of the edge (name of the road) */
	public function __construct($start, $end, $weight=0, $info="") 
	{
		$this->start = $start;
		$this->end = $end;
		$this->weight = $weight;
		$this->info = $info;
	}
}

class Graph
{
	public $nodes = array(); /* :=Array<Node>: all the nodes of the graph */
	public $edges = array(); /* :=Array<Edge>: all the edges of the graph */
	
	public $nodeDExpand = 0; /* Number of nodes that Dijkstra's Algorithm expands */
	public $nodeAExpand = 0; /* Number of nodes that A* Algorithm expands */
	public $nodeDReturn = 0; /* Number of nodes that Dijkstra's Algorithm returns */
	public $nodeAReturn = 0; /* Number of nodes that A* Algorithm returns */
	public $edgeDReturn = 0; /* Number of edges that Dijkstra's Algorithm returns */
	public $egdeAReturn = 0; /* Number of edges that A* Algorithm returns */
	public $DTime = 0;
	public $ATime = 0;
	
	public function insertNode($node)	/* insert a node into the graph */
	{
		array_push($this->nodes, $node);
	}
		
	private function deleteNode($node,$array) /* remove a node from the specific Array<Node> */
	{
		$index = -1;
		foreach($array as $key => $value)
		{
			if($this->isSameNode($node,$value))
			{
				$index = $key;
				break;
			}
		}
		if($index>-1)
		{
			unset($array[$index]);
		}			
		return $array;
	}
	
	/* remove Edge which starts at start and ends at end from edge list edges */
	private function removeEdge($start,$end,$edges)
	{
		$index = -1;
		foreach($edges as $key => $value)
		{
			if($this->isSameNode($start,$value->start) && $this->isSameNode($end,$value->end))
			{
				$index = $key;
				break;
			}
		}
		if($index>-1)
		{
			unset($edges[$index]);
		}	
		return $edges;		
	}
	
	/* insert edge which starts at $from, ends in $to, and has weight $weight */
	public function insertEdge($from, $to, $weight, $info)
	{
		array_push($this->edges, new Edge($from, $to, $weight, $info));
	}
	
	/* get the weight of edge which starts at $from, ends in $to */
	private function getEdgeWeight($from, $to)
	{
		foreach($this->edges as $edge)
		{
			if(($this->isSameNode($edge->start,$from)) && ($this->isSameNode($edge->end,$to)))
			{
				return $edge->weight;
			}
		}
	}
	
	/* check if there is an edge which starts at $from, ends in $to */
	private function isEdge($from, $to)
	{
		foreach($this->edges as $edge)
		{
			if($this->isSameNode($edge->start,$from) && $this->isSameNode($edge->end,$to))
			{
				return true;
			}
		}
		return false;
	}
	
	/* check if the two nodes are the same (by comparing the coordinates) */
	private function isSameNode($nodea, $nodeb)
	{
		if(($nodea->x==$nodeb->x) && ($nodea->y==$nodeb->y))
		{
			return true;
		}
		return false;
	}
	
	/* check if node $node is in the array $array */
	private function isNodeInArray($node, $array)
	{
		foreach($array as $element)
		{
			if($this->isSameNode($element,$node))
			{
				return true;
			}
		}
		return false;
	}
	
	/* use straight-line distance to fill the edge's weight */
	public function fillEdgeWeight()
	{
		foreach ($this->edges as $edge)
		{
			$startX = $edge->start->x;
			$startY = $edge->start->y;
			$endX = $edge->end->x;
			$endY = $edge->end->y;
			$edge->weight = round((sqrt(pow(($endX-$startX),2)+pow(($endY-$startY),2))), 2);
		}
	}
	
	/* initialize the gcost of each node (used as the shortest path cost from start to each node in Dijkstra) */
	private function initDijkstra()
	{
		foreach ($this->nodes as $node)
		{
			$node->gcost = 999999; // 999999 stands for positive infinity
			$node->fcost = 0;
			$node->parent_x = -1;
			$node->parent_y = -1;
			$node->parent_gcost = -1;
		}
	}
	
	/* return the edge which starts at node from and ends at node to */
	private function getEdge($from, $to)
	{
		foreach($this->edges as $edge)
		{
			if($this->isSameNode($edge->start,$from) && $this->isSameNode($edge->end,$to))
			{
				return $edge;
			}
		}
		return NULL;
	}
	
	/* get the node with the smallest gcost(dist value in Dijkstra) in set */
	private function getSmallestDistNode($set)
	{
		if(!empty($set))
		{
			reset($set);
			$result = current($set);
			foreach($set as $node)
			{
				if(($node->gcost) < ($result->gcost))
				{
					$result = $node;
				}
			}
			return $result;
		}
		return NULL;
	}
	
	/* get the node whose coordinate us (x,y) */
	private function getNode($x, $y)
	{
		foreach($this->nodes as $node)
		{
			if($node->x == $x && $node->y == $y)
			{
				return $node;
			}
		}
		return false;
	}
	
	/*
	* Shortest path finding algorithm - Dijkstra's Algorithm
	*/
	public function DijkstraAlgorithm($start, $goal)
	{
		$time_start = microtime(true);
		$this->initDijkstra(); /* initialize the gcost */
		$start->gcost = 0;	/* the cost from start to start is 0 */
      	$Q = $this->nodes;	/* the set of all nodes in Graph*/
		
		$this->nodeDExpand = 0;
		
		while(!empty($Q))
		{
			$u = $this->getSmallestDistNode($Q); /* vertex in Q with smallest distance in dist[] */
			$this->nodeDExpand++;
			if($this->isSameNode($u,$goal)) /* reach goal */
			{
				break;
			}
			$Q = $this->deleteNode($u, $Q); /* remove u from Q */
			if($u->gcost == 999999)	/* all remaining vertices are inaccessible from source */
			{
				break; 
			}
         	$list = $this->getLinkedND($u); /* get vertex u's neighbors */
			foreach($list as $v)
			{
				$alt = $u->gcost + $this->getDistance($u->x,$u->y,$v->x,$v->y);
				if($alt < $v->gcost) /* update */
				{
					$v->gcost= $alt;
                  	$v->parent_x = $u->x;
					$v->parent_y = $u->y;
					$v->parent_gcost = $u->gcost;
					$this->nodeDExpand++;
				}
			}
		}
		
		$S = array(); /* path solution */
		$u = $goal;
		if($u->parent_x==-1 && $u->parent_y==-1) /* no path found */
		{
			return NULL;
		}
		while($u->parent_x!=-1 && $u->parent_y!=-1)
		{
			array_unshift($S,$u);
      		$u = $this->getNode($u->parent_x, $u->parent_y); /* Traverse from target to source */
		}
		array_unshift($S,$start);
		
		$this->nodeDReturn = count($S);
		$this->edgeDReturn = $this->nodeDReturn-1;
		$time_end = microtime(true);
		$this->DTime = $time_end - $time_start;
		return $S;
	}
	
		
	/* calculate straight-line distance from every node to the goal and initialize the f/gcost to zero*/
	private function initAStar($goal)
	{
		$gx = $goal->x;
		$gy = $goal->y;
		foreach($this->nodes as $node)
		{
			$x = $node->x;
			$y = $node->y;
			$straight_line = sqrt(pow(($gx-$x),2)+pow(($gy-$y),2)); // Eclucidean Distance
			$diagonalDis = max(abs($gx-$x),abs($gy-$y));	// Diagonal Distance max(|x1-x2|,|y1-y2|)
			$manhattanDis = abs($gx-$x)+abs($gy-$y); // Manhattan Distance |x1-x2|+|y1-y2|
			
			$node->hcost = max($straight_line, $diagonalDis, $manhattanDis);
			$node->gcost = 0;
			$node->fcost = $node->gcost + $node->hcost;
			
			$node->parent_x = 0;
			$node->parent_y = 0;
			$node->parent_gcost = 0;
		}
	}
	
	/* get the node in $array with the lowest f-cost value */
	private function getLowestFcNd($array)
	{
		if(!empty($array))
		{
			reset($array);
			$result = current($array);
			//$result = array_shift(array_values($array));
			foreach($array as $node)
			{
				if(($node->fcost) < ($result->fcost))
				{
					$result = $node;
				}
			}
			return $result;
		}
		return NULL;
	}
	
	/* get all nodes that are connected with $node */
	private function getLinkedND($node)
	{
		$result = array();
		foreach($this->edges as $edge)
		{
			if($this->isSameNode($edge->start, $node))
			{
				array_push($result, $edge->end);				
			}
		}
		return $result;
	}
	
	/* find the parent node of node $temp in $path (the list of extended nodes in A*) */
	private function findParent($temp, $path)
	{
		foreach($path as $element)
		{
			if(	($element->x==$temp->parent_x) && 
				($element->y==$temp->parent_y) && 
				($element->gcost==$temp->parent_gcost))
			{
				return $element;
			}
		}
		return NULL;
	}
	
	/*
	* A-Star Search Algorithm
	* f(n) = g(n) + h(n)
	* g(n): the actual cost from the start node to node n
	* h(n): heuristic function -> use straight-line distance between node n and the goal
	* always choose the node with shortest cost (smallest f(n))
	*/
	public function AStarAlgorithm($start, $goal)
	{
		$time_start = microtime(true);
		if($this->isSameNode($start,$goal))
		{
			return array($start);
		}
		
		$this->initAStar($goal); /* initialization */
		$openset = array();
		$closedset = array();
		$internd = array(); /* array of all the considered nodes (has the least fcost in every loop) */
		
		$start->fcost = $start->gcost + $start->hcost;		
		$current = $start;
		$this->nodeAExpand = 0;
		array_push($openset, $current);
		while(!empty($openset))
		{
			$current = $this->getLowestFcNd($openset);
			$this->nodeAExpand++;
			array_push($internd, $current);
			if($this->isSameNode($current,$goal))
			{
				break;
			}
			$openset = $this->deleteNode($current, $openset);
			array_push($closedset, $current);
			$list = $this->getLinkedND($current);
			foreach($list as $neighbor)
			{
				if($this->isNodeInArray($neighbor, $closedset))
				{
					continue;
				}
             	$tentative_g_score =  $current->gcost + $this->getDistance($current->x,$current->y,$neighbor->x,$neighbor->y);
 
             	if(!$this->isNodeInArray($neighbor, $openset) || ($tentative_g_score<=$neighbor->gcost))
				{
					$neighbor->parent_x = $current->x;
					$neighbor->parent_y = $current->y;
					$neighbor->parent_gcost = $current->gcost;
					$neighbor->gcost = $tentative_g_score;
					$neighbor->fcost = $neighbor->gcost + $neighbor->hcost;
					$this->nodeAExpand++;
				}			
                if(!$this->isNodeInArray($neighbor, $openset))
				{
					array_push($openset, $neighbor);
				}
			}
		}
		if(empty($openset)) /* no path found */
		{
			return NULL;
		}
		/* generate the path */
		/* array of the nodes in order in solution */
		$solution = array();
		$temp = $current; // the goal
		
		while(!$this->isSameNode($temp,$start))
		{
			array_unshift($solution, $temp);
			$temp = $this->findParent($temp, $internd);
		}
		array_unshift($solution, $temp);
		
		$this->nodeAReturn = count($solution);
		$this->edgeAReturn = $this->nodeAReturn-1;
		$time_end = microtime(true);
		$this->ATime = $time_end - $time_start;
		return $solution;	
	}
	

	// the following functions are maths function used to generate 
	// the start point which is in an edge according to the start choice of user
	
	/* get distance between (x1,y1) to (x2,y2) */
	private function getDistance($x1, $y1, $x2, $y2)
	{
		return round((sqrt(pow(($x2-$x1),2)+pow(($y2-$y1),2))), 2);
	}
	
	/* get the distance between point (x,y) and the line $edge */
	private function getPtLineDistance($x, $y, $edge)
	{
		$x1 = $edge->start->x;
		$y1 = $edge->start->y;
		$x2 = $edge->end->x;
		$y2 = $edge->end->y;
		
		return round(abs(($x2-$x1)*($y1-$y)-($x1-$x)*($y2-$y1)) / (sqrt(pow(($x2-$x1),2)+pow(($y2-$y1),2))), 2);	
	}
	
	/* return the shadow point coordnate of point (x,y) on line $edge */
	private function getPtShadowOnLine($x, $y, $edge)
	{
		$x1 = $edge->start->x;
		$y1 = $edge->start->y;
		$x2 = $edge->end->x;
		$y2 = $edge->end->y;
		
		$a = $x2 - $x1;
		$b = $y2 - $y1;
		$c = $y * $b * $a;
		$d = $x * $b * $a;
		$e = ($x2 * $y1) - ($x1 * $y2);
		$f = pow($a,2) + pow($b,2);
		
		$newX = ($c+pow($a,2)*$x-$e*$b)/$f;
		$newY = ($b*$c+$d*$a-$e*pow($b,2)+$f*$e)/($f*$a);
		return new Node($newX,$newY);
	}
	
	/* generate the closest start point which is in a road and update the nodes and edges */
	public function generateNearestRdPtAndUpdate($x, $y)
	{
		/* find the edges which has a least distance between (x,y) and itself */
		reset($this->edges);
		$closestEd = NULL;
		$dis = 999999;
		foreach($this->edges as $edge)
		{
			$shadow = $this->getPtShadowOnLine($x, $y, $edge);
			if(($shadow->x >= $edge->start->x && $shadow->x <= $edge->end->x) ||
				($shadow->x >= $edge->end->x && $shadow->x <= $edge->start->x))
			{/* the shadow point must locate between (x1,y1) and (x2,y2) which means in the line */
				$tempDis = $this->getPtLineDistance($x, $y, $edge);
				if($tempDis < $dis)
				{
					$closestEd = $edge;;
					$dis = $tempDis;
				}
			}
		}
		
		/* find all the edges which has start/end of closestEd as endpoint (in different directions) */	
		$closestEdList = array();
		foreach($this->edges as $edge)
		{
			if(($this->isSameNode($edge->start,$closestEd->start)&&$this->isSameNode($edge->end,$closestEd->end)) ||
			($this->isSameNode($edge->start,$closestEd->end)&&$this->isSameNode($edge->end,$closestEd->start)))
			{
				array_push($closestEdList,$edge);
			}
		}
		
		/* now we cut the edge at point shadow and create new edges */
		$shadow = $this->getPtShadowOnLine($x, $y, $closestEd);
		$shadow->x = round($shadow->x, 2);
		$shadow->y = round($shadow->y, 2);
		foreach($closestEdList as $edge)
		{
			$this->insertNode($shadow); /* insert shadow into the node list */
			$this->insertEdge(	$edge->start,
								$shadow, 
								$this->getDistance($edge->start->x, $edge->start->y, $shadow->x, $shadow->y),
								$edge->info); /* insert edge start->shadow */
			$this->insertEdge( $shadow,
								$edge->end,
								$this->getDistance($edge->end->x, $edge->end->y, $shadow->x, $shadow->y),
								$edge->info);	 /* insert edge shadow->end */								
			$this->edges = $this->removeEdge($edge->start,$edge->end,$this->edges); /* remove edge */
		}
		return $shadow; /* Att: shadow is the new start/end node used to generate the path */
	}
	
	/* get the information about the point (x,y) */
	public function getPosInfo($x, $y)
	{
		/* find the edges which has a least distance between (x,y) and itself */
		reset($this->edges);
		$closestEd = NULL;
		$dis = 999999;
		foreach($this->edges as $edge)
		{
			$shadow = $this->getPtShadowOnLine($x, $y, $edge);
			if(($shadow->x >= $edge->start->x && $shadow->x <= $edge->end->x) ||
				($shadow->x >= $edge->end->x && $shadow->x <= $edge->start->x))
			{/* the shadow point must locate between (x1,y1) and (x2,y2) which means in the line */
				$tempDis = $this->getPtLineDistance($x, $y, $edge);
				if($tempDis < $dis)
				{
					$closestEd = $edge;;
					$dis = $tempDis;
				}
			}
		}	
		return $closestEd->info; /* Att: shadow is the new start/end node used to generate the path */
	}
	
}
/* End of Graph */



	$node1 = new Node(18,24);
	$node2 = new Node(81,24);
	$node3 = new Node(139,25);
	$node4 = new Node(243,26);
	$node5 = new Node(316,28);
	$node6 = new Node(347,27);
	$node7 = new Node(423,28);
	$node8 = new Node(498,29);
	$node9 = new Node(17,122);
	$node10 = new Node(80,123);
	$node11 = new Node(138,122);
	$node12 = new Node(241,125);
	$node13 = new Node(315,126);
	$node14 = new Node(344,125);
	$node15 = new Node(429,127);
	$node16 = new Node(505,127);
	$node17 = new Node(7,177);
	$node18 = new Node(81,184);
	$node19 = new Node(137,165);
	$node20 = new Node(138,192);
	$node21 = new Node(240,192);
	$node22 = new Node(313,192);
	$node23 = new Node(343,192);
	$node24 = new Node(434,194);
	$node25 = new Node(509,196);
	$node26 = new Node(11,235);
	$node27 = new Node(80,236);
	$node28 = new Node(81,258);
	$node29 = new Node(135,259);
	$node30 = new Node(238,260);
	$node31 = new Node(312,260);
	$node32 = new Node(342,260);
	$node33 = new Node(439,263);
	$node34 = new Node(513,263);
	$node35 = new Node(0,325);
	$node36 = new Node(73,329);
	$node37 = new Node(139,330);
	$node38 = new Node(148,357);
	$node39 = new Node(171,369);
	$node40 = new Node(203,376);
	$node41 = new Node(237,330);
	$node42 = new Node(311,332);
	$node43 = new Node(313,365);
	$node44 = new Node(336,414);
	$node45 = new Node(372,334);
	$node46 = new Node(442,335);
	$node47 = new Node(520,335);
	$node48 = new Node(6,448);
	$node49 = new Node(61,445);
	$node50 = new Node(120,446);
	$node51 = new Node(187,444);
	$node52 = new Node(209,421);
	$node53 = new Node(122,479);
	$node54 = new Node(184,484);
	$node55 = new Node(204,498);
	$node56 = new Node(244,534);
	$node57 = new Node(320,522);
	$node58 = new Node(367,542);
	$node59 = new Node(385,534);
	$node60 = new Node(455,532);
	$node61 = new Node(10,559);
	$node62 = new Node(12,600);
	$node63 = new Node(66,558);
	$node64 = new Node(75,567);
	$node65 = new Node(87,575);
	$node66 = new Node(75,586);
	$node67 = new Node(67,587);
	$node68 = new Node(65,576);
	$node69 = new Node(205,585);
	$node70 = new Node(241,585);
	$node71 = new Node(460,597);
	$node72 = new Node(501,599);
	$node73 = new Node(495,529);
	$node74 = new Node(538,598);
	$node84 = new Node(530,527);
	$node85 = new Node(524,423);
	$node86 = new Node(83,1);
	$node87 = new Node(140,1);
	$node88 = new Node(242,1);
	$node89 = new Node(345,1);
	$node90 = new Node(419,1);
	$node91 = new Node(496,1);
	$node92 = new Node(139,278);
	$node93 = new Node(136,313);
	$node94 = new Node(0,22);
	$node95 = new Node(0,122);
	$node136 = new Node(110,585);
	
	
	$graph = new Graph;
	$graph->insertNode($node1);
	$graph->insertNode($node2);
	$graph->insertNode($node3);
	$graph->insertNode($node4);
	$graph->insertNode($node5);
	$graph->insertNode($node6);
	$graph->insertNode($node7);
	$graph->insertNode($node8);
	$graph->insertNode($node9);
	$graph->insertNode($node10);
	$graph->insertNode($node11);
	$graph->insertNode($node12);
	$graph->insertNode($node13);
	$graph->insertNode($node14);
	$graph->insertNode($node15);
	$graph->insertNode($node16);
	$graph->insertNode($node17);
	$graph->insertNode($node18);
	$graph->insertNode($node19);
	$graph->insertNode($node20);
	$graph->insertNode($node21);
	$graph->insertNode($node22);
	$graph->insertNode($node23);
	$graph->insertNode($node24);
	$graph->insertNode($node25);
	$graph->insertNode($node26);
	$graph->insertNode($node27);
	$graph->insertNode($node28);
	$graph->insertNode($node29);
	$graph->insertNode($node30);
	$graph->insertNode($node31);
	$graph->insertNode($node32);
	$graph->insertNode($node33);
	$graph->insertNode($node34);
	$graph->insertNode($node35);
	$graph->insertNode($node36);
	$graph->insertNode($node37);
	$graph->insertNode($node38);
	$graph->insertNode($node39);
	$graph->insertNode($node40);
	$graph->insertNode($node41);
	$graph->insertNode($node42);
	$graph->insertNode($node43);
	$graph->insertNode($node44);
	$graph->insertNode($node45);
	$graph->insertNode($node46);
	$graph->insertNode($node47);
	$graph->insertNode($node48);
	$graph->insertNode($node49);
	$graph->insertNode($node50);
	$graph->insertNode($node51);
	$graph->insertNode($node52);
	$graph->insertNode($node53);
	$graph->insertNode($node54);
	$graph->insertNode($node55);
	$graph->insertNode($node56);
	$graph->insertNode($node57);
	$graph->insertNode($node58);
	$graph->insertNode($node59);
	$graph->insertNode($node60);
	$graph->insertNode($node61);
	$graph->insertNode($node62);
	$graph->insertNode($node63);
	$graph->insertNode($node64);
	$graph->insertNode($node65);
	$graph->insertNode($node66);
	$graph->insertNode($node67);
	$graph->insertNode($node68);
	$graph->insertNode($node69);
	$graph->insertNode($node70);
	$graph->insertNode($node71);
	$graph->insertNode($node72);
	$graph->insertNode($node73);
	$graph->insertNode($node74);
	$graph->insertNode($node84);
	$graph->insertNode($node85);
	$graph->insertNode($node86);
	$graph->insertNode($node87);
	$graph->insertNode($node88);
	$graph->insertNode($node89);
	$graph->insertNode($node90);
	$graph->insertNode($node91);
	$graph->insertNode($node92);
	$graph->insertNode($node93);
	$graph->insertNode($node94);
	$graph->insertNode($node95);
	$graph->insertNode($node136);
	// Edges
	
	// Harrison St
	$graph->insertEdge($node94,$node1,0,"Harison St");
	$graph->insertEdge($node1,$node2,0,"Harison St");
	$graph->insertEdge($node2,$node3,0,"Harison St");
	$graph->insertEdge($node3,$node4,0,"Harison St");
	$graph->insertEdge($node4,$node5,0,"Harison St");
	$graph->insertEdge($node5,$node6,0,"Harison St");
	$graph->insertEdge($node6,$node7,0,"Harison St");
	$graph->insertEdge($node7,$node8,0,"Harison St");
	$graph->insertEdge($node8,$node7,0,"Harison St");
	$graph->insertEdge($node7,$node6,0,"Harison St");
	$graph->insertEdge($node6,$node5,0,"Harison St");
	$graph->insertEdge($node5,$node4,0,"Harison St");
	$graph->insertEdge($node4,$node3,0,"Harison St");
	$graph->insertEdge($node3,$node2,0,"Harison St");
	$graph->insertEdge($node2,$node1,0,"Harison St");
	$graph->insertEdge($node1,$node94,0,"Harison St");
	// E Adams St
	$graph->insertEdge($node95,$node9,0,"E Adams St");
	$graph->insertEdge($node9,$node10,0,"E Adams St");
	$graph->insertEdge($node10,$node11,0,"E Adams St");
	$graph->insertEdge($node11,$node12,0,"E Adams St");
	$graph->insertEdge($node12,$node13,0,"E Adams St");
	$graph->insertEdge($node13,$node14,0,"E Adams St");
	$graph->insertEdge($node14,$node15,0,"E Adams St");
	$graph->insertEdge($node15,$node16,0,"E Adams St");
	// Crouse Hospital
	$graph->insertEdge($node17,$node18,0,"Crouse Hospital");
	$graph->insertEdge($node18,$node17,0,"Crouse Hospital");
	$graph->insertEdge($node26,$node27,0,"Crouse Hospital");
	$graph->insertEdge($node27,$node26,0,"Crouse Hospital");
	// Marshall St
	$graph->insertEdge($node20,$node21,0,"Marshall St");
	$graph->insertEdge($node21,$node22,0,"Marshall St");
	$graph->insertEdge($node22,$node23,0,"Marshall St");
	$graph->insertEdge($node23,$node24,0,"Marshall St");
	$graph->insertEdge($node24,$node25,0,"Marshall St");
	$graph->insertEdge($node25,$node24,0,"Marshall St");
	$graph->insertEdge($node24,$node23,0,"Marshall St");
	$graph->insertEdge($node23,$node22,0,"Marshall St");
	// Waverly Ave
	$graph->insertEdge($node28,$node29,0,"Waverly Ave");
	$graph->insertEdge($node29,$node30,0,"Waverly Ave");
	$graph->insertEdge($node30,$node31,0,"Waverly Ave");
	$graph->insertEdge($node31,$node32,0,"Waverly Ave");
	$graph->insertEdge($node32,$node33,0,"Waverly Ave");
	$graph->insertEdge($node33,$node34,0,"Waverly Ave");
	$graph->insertEdge($node34,$node33,0,"Waverly Ave");
	$graph->insertEdge($node33,$node32,0,"Waverly Ave");
	$graph->insertEdge($node32,$node31,0,"Waverly Ave");
	$graph->insertEdge($node31,$node30,0,"Waverly Ave");
	$graph->insertEdge($node30,$node29,0,"Waverly Ave");
	$graph->insertEdge($node29,$node28,0,"Waverly Ave");
	// University Pl
	$graph->insertEdge($node35,$node36,0,"University Pl");
	$graph->insertEdge($node36,$node37,0,"University Pl");
	$graph->insertEdge($node37,$node41,0,"University Pl");
	$graph->insertEdge($node41,$node42,0,"University Pl");
	$graph->insertEdge($node42,$node45,0,"University Pl");
	$graph->insertEdge($node45,$node46,0,"University Pl");
	$graph->insertEdge($node46,$node47,0,"University Pl");
	$graph->insertEdge($node47,$node46,0,"University Pl");
	$graph->insertEdge($node46,$node45,0,"University Pl");
	$graph->insertEdge($node45,$node42,0,"University Pl");
	$graph->insertEdge($node42,$node41,0,"University Pl");
	$graph->insertEdge($node41,$node37,0,"University Pl");
	$graph->insertEdge($node37,$node36,0,"University Pl");
	$graph->insertEdge($node36,$node35,0,"University Pl");
	// Euclid Ave
	$graph->insertEdge($node59,$node60,0,"Euclid Ave");
	$graph->insertEdge($node60,$node73,0,"Euclid Ave");
	$graph->insertEdge($node73,$node84,0,"Euclid Ave");
	$graph->insertEdge($node84,$node73,0,"Euclid Ave");
	$graph->insertEdge($node73,$node60,0,"Euclid Ave");
	$graph->insertEdge($node60,$node59,0,"Euclid Ave");
	// E Raynor Ave
	$graph->insertEdge($node61,$node63,0,"E Raynor Ave");
	$graph->insertEdge($node63,$node61,0,"E Raynor Ave");
	// Sims Dr
	$graph->insertEdge($node70,$node56,0,"Sims Dr");
	$graph->insertEdge($node56,$node57,0,"Sims Dr");
	$graph->insertEdge($node57,$node58,0,"Sims Dr");
	$graph->insertEdge($node58,$node59,0,"Sims Dr");
	$graph->insertEdge($node59,$node58,0,"Sims Dr");
	$graph->insertEdge($node58,$node57,0,"Sims Dr");
	$graph->insertEdge($node57,$node56,0,"Sims Dr");
	$graph->insertEdge($node56,$node70,0,"Sims Dr");
	// Van Buren St
	$graph->insertEdge($node48,$node49,0,"Van Buren St");
	$graph->insertEdge($node49,$node48,0,"Van Buren St");
	// Elizabeth Blackwell St
	$graph->insertEdge($node9,$node1,0,"Elizabeth Blackwell St");
	// Irving Ave
	$graph->insertEdge($node86,$node2,0,"Irving Ave");
	$graph->insertEdge($node2,$node86,0,"Irving Ave");
	$graph->insertEdge($node2,$node10,0,"Irving Ave");
	$graph->insertEdge($node10,$node18,0,"Irving Ave");
	$graph->insertEdge($node18,$node27,0,"Irving Ave");
	$graph->insertEdge($node27,$node28,0,"Irving Ave");
	$graph->insertEdge($node28,$node36,0,"Irving Ave");
	$graph->insertEdge($node36,$node49,0,"Irving Ave");
	$graph->insertEdge($node49,$node63,0,"Irving Ave");
	$graph->insertEdge($node63,$node49,0,"Irving Ave");
	$graph->insertEdge($node49,$node36,0,"Irving Ave");
	$graph->insertEdge($node36,$node28,0,"Irving Ave");
	$graph->insertEdge($node28,$node27,0,"Irving Ave");
	$graph->insertEdge($node27,$node18,0,"Irving Ave");
	$graph->insertEdge($node18,$node10,0,"Irving Ave");
	$graph->insertEdge($node10,$node2,0,"Irving Ave");
	// South Crouse Ave
	$graph->insertEdge($node29,$node20,0,"South Crouse Ave");
	$graph->insertEdge($node20,$node19,0,"South Crouse Ave");
	$graph->insertEdge($node19,$node11,0,"South Crouse Ave");
	$graph->insertEdge($node11,$node3,0,"South Crouse Ave");
	$graph->insertEdge($node3,$node87,0,"South Crouse Ave");
	$graph->insertEdge($node29,$node92,0,"South Crouse Ave");
	$graph->insertEdge($node92,$node93,0,"South Crouse Ave");
	$graph->insertEdge($node93,$node37,0,"South Crouse Ave");
	$graph->insertEdge($node37,$node93,0,"South Crouse Ave");
	$graph->insertEdge($node93,$node92,0,"South Crouse Ave");
	$graph->insertEdge($node92,$node29,0,"South Crouse Ave");
	// Crouse Dr
	$graph->insertEdge($node37,$node38,0,"Crouse Dr");
	$graph->insertEdge($node38,$node39,0,"Crouse Dr");
	$graph->insertEdge($node39,$node40,0,"Crouse Dr");
	$graph->insertEdge($node40,$node52,0,"Crouse Dr");
	$graph->insertEdge($node52,$node51,0,"Crouse Dr");
	$graph->insertEdge($node51,$node50,0,"Crouse Dr");
	$graph->insertEdge($node50,$node53,0,"Crouse Dr");
	$graph->insertEdge($node53,$node54,0,"Crouse Dr");
	$graph->insertEdge($node54,$node55,0,"Crouse Dr");
	$graph->insertEdge($node55,$node69,0,"Crouse Dr");
	$graph->insertEdge($node69,$node55,0,"Crouse Dr");
	$graph->insertEdge($node55,$node54,0,"Crouse Dr");
	$graph->insertEdge($node54,$node53,0,"Crouse Dr");
	$graph->insertEdge($node53,$node50,0,"Crouse Dr");
	$graph->insertEdge($node50,$node51,0,"Crouse Dr");
	$graph->insertEdge($node51,$node52,0,"Crouse Dr");
	$graph->insertEdge($node52,$node40,0,"Crouse Dr");
	$graph->insertEdge($node40,$node39,0,"Crouse Dr");
	$graph->insertEdge($node39,$node38,0,"Crouse Dr");
	$graph->insertEdge($node38,$node37,0,"Crouse Dr");
	// University Ave
	$graph->insertEdge($node4,$node88,0,"University Ave");
	$graph->insertEdge($node88,$node4,0,"University Ave");
	$graph->insertEdge($node4,$node12,0,"University Ave");
	$graph->insertEdge($node12,$node21,0,"University Ave");
	$graph->insertEdge($node21,$node30,0,"University Ave");
	$graph->insertEdge($node30,$node21,0,"University Ave");
	$graph->insertEdge($node21,$node12,0,"University Ave");
	$graph->insertEdge($node12,$node4,0,"University Ave");
	// Walnut Pl
	$graph->insertEdge($node5,$node13,0,"Walnut Pl");
	$graph->insertEdge($node13,$node22,0,"Walnut Pl");
	$graph->insertEdge($node22,$node31,0,"Walnut Pl");
	// Walnut Ave
	$graph->insertEdge($node32,$node23,0,"Walnut Ave");
	$graph->insertEdge($node23,$node14,0,"Walnut Ave");
	$graph->insertEdge($node14,$node6,0,"Walnut Ave");
	$graph->insertEdge($node6,$node89,0,"Walnut Ave");
	// Smith Dr
	$graph->insertEdge($node42,$node43,0,"Smith Dr");
	$graph->insertEdge($node43,$node44,0,"Smith Dr");
	$graph->insertEdge($node44,$node43,0,"Smith Dr");
	$graph->insertEdge($node43,$node42,0,"Smith Dr");
	// College Pl
	$graph->insertEdge($node45,$node59,0,"College Pl");
	$graph->insertEdge($node59,$node45,0,"College Pl");
	// Comstock Ave
	$graph->insertEdge($node33,$node24,0,"Comstock Ave");
	$graph->insertEdge($node24,$node15,0,"Comstock Ave");
	$graph->insertEdge($node15,$node7,0,"Comstock Ave");
	$graph->insertEdge($node7,$node90,0,"Comstock Ave");
	$graph->insertEdge($node33,$node46,0,"Comstock Ave");
	$graph->insertEdge($node46,$node60,0,"Comstock Ave");
	$graph->insertEdge($node60,$node71,0,"Comstock Ave");
	$graph->insertEdge($node71,$node60,0,"Comstock Ave");
	$graph->insertEdge($node60,$node46,0,"Comstock Ave");
	$graph->insertEdge($node46,$node33,0,"Comstock Ave");
	// Ostrom Pl
	$graph->insertEdge($node72,$node73,0,"Ostrom Pl");
	$graph->insertEdge($node73,$node72,0,"Ostrom Pl");
	// Ostrom Ave
	$graph->insertEdge($node8,$node91,0,"Ostrom Ave");
	$graph->insertEdge($node91,$node8,0,"Ostrom Ave");
	$graph->insertEdge($node8,$node16,0,"Ostrom Ave");
	$graph->insertEdge($node16,$node25,0,"Ostrom Ave");
	$graph->insertEdge($node25,$node34,0,"Ostrom Ave");
	$graph->insertEdge($node34,$node47,0,"Ostrom Ave");
	$graph->insertEdge($node47,$node85,0,"Ostrom Ave");
	$graph->insertEdge($node85,$node84,0,"Ostrom Ave");
	$graph->insertEdge($node84,$node74,0,"Ostrom Ave");
	$graph->insertEdge($node74,$node84,0,"Ostrom Ave");
	$graph->insertEdge($node84,$node85,0,"Ostrom Ave");
	$graph->insertEdge($node85,$node47,0,"Ostrom Ave");
	$graph->insertEdge($node47,$node34,0,"Ostrom Ave");
	$graph->insertEdge($node34,$node25,0,"Ostrom Ave");
	$graph->insertEdge($node25,$node16,0,"Ostrom Ave");
	$graph->insertEdge($node16,$node8,0,"Ostrom Ave");
	// Forestry Dr
	$graph->insertEdge($node63,$node64,0,"Forestry Dr");
	$graph->insertEdge($node64,$node65,0,"Forestry Dr");
	$graph->insertEdge($node65,$node136,0,"Forestry Dr");
	$graph->insertEdge($node136,$node69,0,"Forestry Dr");
	$graph->insertEdge($node69,$node70,0,"Forestry Dr");
	$graph->insertEdge($node70,$node69,0,"Forestry Dr");
	$graph->insertEdge($node69,$node136,0,"Forestry Dr");
	$graph->insertEdge($node136,$node65,0,"Forestry Dr");
	$graph->insertEdge($node65,$node64,0,"Forestry Dr");
	$graph->insertEdge($node64,$node63,0,"Forestry Dr");
	$graph->insertEdge($node65,$node66,0,"Forestry Dr");
	$graph->insertEdge($node66,$node67,0,"Forestry Dr");
	$graph->insertEdge($node67,$node68,0,"Forestry Dr");
	$graph->insertEdge($node68,$node64,0,"Forestry Dr");
	// Stadium Pl
	$graph->insertEdge($node48,$node61,0,"Stadium Pl");
	$graph->insertEdge($node61,$node62,0,"Stadium Pl");
	$graph->insertEdge($node62,$node61,0,"Stadium Pl");
	$graph->insertEdge($node61,$node48,0,"Stadium Pl");
?>