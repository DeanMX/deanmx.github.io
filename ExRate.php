<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0 ,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" type="text/css" href="files/main.css">
<link rel="stylesheet" type="text/css" href="files/center-both.css">
<title>ExRate</title>
</head>
<body>
<?php
$c = $_GET['c'];
$t = $_GET['t'];
function getData($c, $t)
{
	/*
	https://tw.rter.info/howto_currencyapi.php

	*/

	$url = 'https://tw.rter.info/capi.php';
	$userAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36';
	$http_header[] = "Accept-Language: zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7";
	
	/* initial the curl object */
	$curl = @curl_init();
	@curl_setopt($curl , CURLOPT_URL , $url);
	@curl_setopt($curl , CURLOPT_GET , true );
	@curl_setopt($curl , CURLOPT_USERAGENT, $userAgent);
	@curl_setopt($curl , CURLOPT_FORBID_REUSE , true );// persistent, keepalive
	@curl_setopt($curl , CURLOPT_RETURNTRANSFER , true );
	//@curl_setopt($curl , CURLOPT_SSL_VERIFYPEER , false );// ssl verify
	@curl_setopt($curl , CURLOPT_FAILONERROR, true);

	@curl_setopt($curl , CURLOPT_HTTPHEADER  , $http_header);
	//@curl_setopt($curl , CURLOPT_POSTFIELDS , $http_content );
	$contents = @curl_exec( $curl );
	if(@curl_errno($curl))
	{	
		$error_msg = @curl_error($curl);
	}
	if(isset($error_msg))
	{
		echo "\$error_msg=$error_msg<br>";
		return NULL;
	}
		
	$json = json_decode($contents, true);
	$strUSD2C = 'USD'.$c;
	$strUSD2T = 'USD'.$t;

	if(isset($json[$strUSD2C]) && isset($json[$strUSD2T]))
	{
		$exRateC = $json[$strUSD2C]['Exrate'];
		$exRateT = $json[$strUSD2T]['Exrate'];
		return 1.0 * $exRateT / $exRateC;
	}

	return NULL;
}
$exRate = NULL;
$strVal = '';
if(!empty($c) && !empty($t))
{
	$exRate = getData($c, $t);
}
$strName = $c.' to '.$t;
if(!empty($exRate))
{
	$strVal = sprintf('%.04f', $exRate);
}
?>
	<div id="wrap">
		<div id="cell" style="display: block">
			<h2 id="stock_title"><?=$strName;?></h2>
			<table border="0" align="center">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>	
				<tr>
					<td style="text-align:right">Current</td>
					<td style="text-align:left" id="stock_curr"><?=$strVal?></td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>
