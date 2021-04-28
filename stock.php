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
$url = 'https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_'+$stock+'.tw&json=1';
/* initial the curl object */
$curl = @curl_init();
@curl_setopt($curl , CURLOPT_URL , $url);
@curl_setopt($curl , CURLOPT_POST , true );
@curl_setopt($curl , CURLOPT_FORBID_REUSE , true );// persistent, keepalive
@curl_setopt($curl , CURLOPT_RETURNTRANSFER , true );
//@curl_setopt($curl , CURLOPT_SSL_VERIFYPEER , false );// ssl verify

@curl_setopt($curl , CURLOPT_HTTPHEADER  , $http_header);
@curl_setopt($curl , CURLOPT_POSTFIELDS , $http_content );
$contents = @curl_exec( $curl );
$json = json_decode($contents, true);
$data = $json['msgArray'][0];
?>
	<div id="wrap">
		<div id="cell" style="display: block">
			<h2 id="stock_title"><?=$data['nf']?></h2>
			<table border="0" align="center">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>	
				<tr>
					<td style="text-align:right">Current</td>
					<td style="text-align:left" id="stock_curr"><?=$data['z']?></td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>
