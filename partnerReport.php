<?php
/**
 * Created by IntelliJ IDEA.
 * User: moshe.maor
 * Date: 1/2/2018
 * Time: 2:15 PM
 */
require_once('/opt/kaltura/web/content/clientlibs/testsClient/KalturaClient.php');

$userName = $_GET['userName'];
$password = $_GET['password'];

function login($dc, $userName, $userPassword, $partnerId = null)
{
	$config = new KalturaConfiguration();
	$config->serviceUrl = $dc;
	$client = new KalturaClient($config);
	$loginId = $userName;
	$password = $userPassword;
	$expiry = null;
	$privileges = null;
	$ks = $client->user->loginbyloginid($loginId, $password, $partnerId, $expiry, $privileges);
	$client->setKs($ks);
	return $client;
}
try {
	$ret = login('pa-front-stg.kaltura.com', $userName, $password, -2);
}catch (Exception $e)
{
	die ($e->getMessage());
}

function compare($a , $b)
{
	if($GLOBALS['orderDirection']=='down')
		return $a[$GLOBALS['compareIndex']] - $b[$GLOBALS['compareIndex']];
	return $b[$GLOBALS['compareIndex']]-$a[$GLOBALS['compareIndex']];
}
$compareIndex = 1;
$fileName = "/home/dev/moshe/allPartnersLog.txt";
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
$lines = file ($fileName);
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
		$partnerId=$response['hits']['hits'][0]['_source']['partner_id'];
		$name =  utf8_encode($partnerArray[$partnerId]['name']);
		$email = utf8_encode($partnerArray[$partnerId]['email']);
		$output[]=array( $partnerId, $trend,$valuesArray,$variance,$ave,$med,$name,$email);
	}
}
if($compareIndex)
	uasort($output,compare);

$index=1;
function strip_carriage_returns($string)
{
	return str_replace(array("\n\r", "\n", "\r"), '', $string);
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
echo (json_encode($output));
