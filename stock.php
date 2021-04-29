<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0 ,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" type="text/css" href="files/main.css">
<link rel="stylesheet" type="text/css" href="files/center-both.css">
<title>Stock</title>
</head>
<body>
<?php
$stock = $_GET['stock'];

function getData($stock, $type='tse')
{
	$url = 'https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch='.$type.'_'.$stock.'.tw&json=1&delay=0';
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
	if($json && isset($json['msgArray']) && count($json['msgArray']))
	{
		echo '<a target="_blank" href='.$url.'>'.$url.'</a><br>';
		return $json['msgArray'][0];
	}
	else
		return NULL;
}
$data = [];
if(!empty($stock))
{
	$data = getData($stock);
	if(empty($data))
		$data = getData($stock,'otc');
}


$strStockName = htmlspecialchars($data['n'], ENT_COMPAT);
$strStockNum = $data['c'];
$strStockValPrev = $data['y'];
$strStockValCurr = $data['z'];
$strStockVal = ($strStockValCurr=='-')?$strStockValPrev:$strStockValCurr;
?>
	<div id="wrap">
		<div id="cell" style="display: block">
			<h2 id="stock_title"><?=$strStockName;?>(<?=$strStockNum;?>)</h2>
			<table border="0" align="center">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>	
				<tr>
					<td style="text-align:right">Current</td>
					<td style="text-align:left" id="stock_curr"><?=$strStockVal?></td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>
