<?php
/**
 * Created by IntelliJ IDEA.
 * User: moshe.maor
 * Date: 1/2/2018
 * Time: 2:15 PM
 */
$compareIndex = null;
$fileName = $_GET['fileName'];
$orderBy = $_GET['orderBy'];
$orderDirection = $_GET['orderDirection'];
$partnerNameFiles="/home/dev/moshe/parnterIdName.txt";
$partnerIds = file($partnerNameFiles);
$partnerArray =array();
foreach($partnerIds as $partnerIdsLine)
{
	$item = explode("\t",$partnerIdsLine);
	$partnerId=$item[0];
	$name = trim(substr($item[1],0,15));
	$email = trim(substr($item[2],0,15));
	$partnerArray[$partnerId]=array("name"=>$name,"email"=>$email);
}
if(isset($orderBy))
{
	switch($orderBy)
	{
		case "PartnerId":
			$compareIndex=0;
		break;
		case "Trend":
			$compareIndex=1;
		break;
		case "Variance":
			$compareIndex=3;
		break;
		case "Average":
			$compareIndex=4;
		break;
		case "Median":
			$compareIndex=5;
		break;
		default:
			break;
	}
}

$lines = file ($fileName);
echo ("<!DOCTYPE html><html>
<head><title>The sales man tool!</title>
<link rel='stylesheet' href='bootstrap.min.css'>
<style>
table {
    border-spacing: 0;
    width: 100%;
    border: 1px solid #ddd;
}
th, td {
    text-align: left;
    padding: 12px;
}
tr:nth-child(even) {
    background-color: #f2f2f2
}
tr:hover {
	background-color: grey;
}
tr {
   line-height: 4px;
   min-height: 4px;
   height: 4px;
}
td.wide {
	 font-size: 1px;
	 min-width: 80px;
}
th.wide {
	 font-size: 1px;
	 min-width: 60px;
} 
 
.tooltiptext {
    visibility: hidden;
}
.tooltiptext:hover {
    visibility: visible;
}



</style>
<script>
function order(fieldName)
{
    var currentLocation = location.href;
    var index = currentLocation.indexOf('&orderBy=');
    var newLocation = currentLocation.substring(0,index)+'&orderBy='+fieldName; 
    window.location = newLocation;
}
function openGraph(partnerId)
{
    window.open(\"http://192.168.21.166:5601/app/kibana#/dashboard/bc11bce0-f092-11e7-996c-573762638943?_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-2y,mode:quick,to:now))&_a=(filters:!(),options:(darkTheme:!f),panels:!((col:7,id:a9b370d0-f087-11e7-bd44-df53f49f9bbe,panelIndex:3,row:4,size_x:6,size_y:3,type:visualization),(col:7,id:'34224430-f06a-11e7-a083-bf6f2deb126e',panelIndex:4,row:10,size_x:6,size_y:3,type:visualization),(col:1,id:'45bd9e20-f064-11e7-a784-e5fb0ea6c88d',panelIndex:5,row:7,size_x:6,size_y:3,type:visualization),(col:7,id:'0b983610-f064-11e7-a784-e5fb0ea6c88d',panelIndex:6,row:7,size_x:6,size_y:3,type:visualization),(col:1,id:'3bf2aa60-f092-11e7-9ca0-a33d89a7afb7',panelIndex:7,row:1,size_x:6,size_y:3,type:visualization),(col:7,id:a03d1aa0-f092-11e7-9ca0-a33d89a7afb7,panelIndex:8,row:1,size_x:6,size_y:3,type:visualization),(col:1,id:'29195230-f098-11e7-9b32-bbfe4122d785',panelIndex:9,row:4,size_x:6,size_y:3,type:visualization)),query:(query_string:(analyze_wildcard:!t,query:'partner_id:\"+partnerId+\"')),timeRestore:!f,title:'New%20-%20Kaltura%20Generic',uiState:(),viewMode:view)\");
}
function getGraphUrl(partnerId)
{
    window.open(\"http://192.168.21.166:5601/app/kibana#/dashboard/bc11bce0-f092-11e7-996c-573762638943?_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:now-2y,mode:quick,to:now))&_a=(filters:!(),options:(darkTheme:!f),panels:!((col:7,id:a9b370d0-f087-11e7-bd44-df53f49f9bbe,panelIndex:3,row:4,size_x:6,size_y:3,type:visualization),(col:7,id:'34224430-f06a-11e7-a083-bf6f2deb126e',panelIndex:4,row:10,size_x:6,size_y:3,type:visualization),(col:1,id:'45bd9e20-f064-11e7-a784-e5fb0ea6c88d',panelIndex:5,row:7,size_x:6,size_y:3,type:visualization),(col:7,id:'0b983610-f064-11e7-a784-e5fb0ea6c88d',panelIndex:6,row:7,size_x:6,size_y:3,type:visualization),(col:1,id:'3bf2aa60-f092-11e7-9ca0-a33d89a7afb7',panelIndex:7,row:1,size_x:6,size_y:3,type:visualization),(col:7,id:a03d1aa0-f092-11e7-9ca0-a33d89a7afb7,panelIndex:8,row:1,size_x:6,size_y:3,type:visualization),(col:1,id:'29195230-f098-11e7-9b32-bbfe4122d785',panelIndex:9,row:4,size_x:6,size_y:3,type:visualization)),query:(query_string:(analyze_wildcard:!t,query:'partner_id:\"+partnerId+\"')),timeRestore:!f,title:'New%20-%20Kaltura%20Generic',uiState:(),viewMode:view)\");
}
</script>
</head>
<body>
<table id=\"TrendTable\">
 <tr>
 	<th >#</th>
    <th  ondblclick=order('PartnerId')>Id</th>
    <th  ondblclick=order('Trend')>Trend</th>
    <th class 'wide'>Coordinates</th>
    <th ondblclick=order('Variance') >Var</th>
    <th ondblclick=order('Average')>Ave</th>
    <th ondblclick=order('Median')>Med</th>
    <th >Name</th>
    <th >Email</th>
    </tr>
");

$output = array();
foreach ($lines as $line)
{
	$responses = json_decode($line,true);

	foreach ($responses['responses'] as $response)
	{
		$buckets = $response['aggregations']['2']['buckets'];

		$startPoint = false;
		$index = 0;
		$valuesArray = array();
		for (; $index < count($buckets) - 1; $index++) {
			$docCount = $buckets[$index]['doc_count'];
			if ($docCount)
				$startPoint = true;
			if ($startPoint) {
				$valuesArray[] = $docCount;
			}
		}
		if (!count($valuesArray))
			continue;

		list($trend,$variance,$ave,$med) = calcTrend($valuesArray);
		$output[]=array($response['hits']['hits'][0]['_source']['partner_id'] , $trend,$valuesArray,$variance,$ave,$med);
	}

}
if($compareIndex)
	uasort($output,compare);

$index=1;
foreach ($output as $outLine)
{
	$partner_id = $outLine[0];
	$trend = $outLine[1];
	$valuesArray = $outLine[2];
	$shonut = $outLine[3];
	$ave=$outLine[4];
	$med=$outLine[5];

	echo("<tr >");

	echo("<td>".$index++."</td>");
	echo("<td ondblclick='openGraph($partner_id)'>");

	echo('<b >' . $partner_id . '</b>');
	echo("</td>");
	echo("<td>");
	if ($trend <= 0) {
		echo('<a style="color:Tomato;">');
	} else
		echo('<a style="color:MediumSeaGreen;">');
	echo($trend . '</a');
	echo("</td>");
	echo("<td class 'wide'>");
	printPoints($valuesArray);
	echo("</td>");
	echo("<td>".$shonut."</td>");
	echo("<td>".$ave."</td>");
	echo("<td>".$med."</td>");
	echo("<td>".$partnerArray[$partner_id]['name']."</td>");
	echo("<td>".$partnerArray[$partner_id]['email']."</td>");
}

echo ("</table>");
echo ("</body></html>");
function compare($a , $b)
{
	if($GLOBALS['orderDirection']=='down')
		return $a[$GLOBALS['compareIndex']] - $b[$GLOBALS['compareIndex']];
	return $b[$GLOBALS['compareIndex']]-$a[$GLOBALS['compareIndex']];
}

function printPoints(array $valuesArray)
{
	echo ('<a>');
	for ($index=0 ;$index < count($valuesArray) ; $index++)
	{
		$value = $valuesArray[$index];
		if($index>0)
		{
			if($value< $valuesArray[$index-1])
			{
				echo('<a style="color:Tomato;">');
			} else
				echo('<a style="color:MediumSeaGreen;">');
		}
		else
			echo ('<a>');
		echo ($value."</a> ");
	}
	echo ('</a>');
}

function calcTrend(array $valuesArray)
{
	//find the middle point. after which we change the sign.
	$middle = (count($valuesArray))/2;
	$trend = 0;
	$sqr=0;
	$sum=0;
	for ($index = 1 ; $index < count($valuesArray) ; $index++)
	{
		if ($index < $middle)
			$trend -= $valuesArray[$index];
		else
			$trend += $valuesArray[$index];
		$sum+=$valuesArray[$index];
	}
	$average=round($sum/$index);
	for ($index = 1 ; $index < count($valuesArray) ; $index++)
	{
		$diff = $valuesArray[$index] - $average;
		$sqr+=$diff*$diff;
	}
	$shonut = round(sqrt($sqr/$index));
	$median = $valuesArray[$middle];
	return array($trend,$shonut,$average,$median);
}
