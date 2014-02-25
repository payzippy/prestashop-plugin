<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/payzippy.php');
$payzippy = new payzippy();
?>
<html>
<head>
<title>Payment Request</title>
<style>
.payzippy_no_refresh
{
background-color: #fcf8e3;
border: 2px solid #fbeed5;
color: #c09853;
padding: 25px;
width: 600px;
-moz-border-radius: 10px;
-webkit-border-radius: 10px;
-ms-border-radius: 10px;
border-radius: 10px;
-moz-text-shadow: 0 1px 0 rgba(255,255,255,0.5);
-webkit-text-shadow: 0 1px 0 rgba(255,255,255,0.5);
-ms-text-shadow: 0 1px 0 rgba(255,255,255,0.5);
text-shadow: 0 1px 0 rgba(255,255,255,0.5);
margin: 120px auto 0 auto;
text-align: center;
font: 25px/110% "Lucida Grande","Lucida Sans Unicode",Helvetica,Arial,Verdana,sans-serif;
}

</style>
</head>
<body>
		<div>
				<div id="detect-iframe" class="payzippy_no_refresh">
					<p>Please do not press stop, refresh or back button</p>
				</div>
				<form method="POST" action="<?php echo $payzippy->PayZippyUrl()?>" id="payzippy_request_Form">
<?php
					$str = '';
					ksort($_REQUEST);
					foreach ($_REQUEST as $key => $value)
{
echo "<input type='hidden' name='{$key}' value='{$value}'>";
$str = $str.$value.'|';
}
					$str = $str.Configuration::get('SECRET_KEY');
					$str = hash('SHA256', $str);
					echo "<input type='hidden' name='hash' value='$str'>";
					?>
				</form>
				<script>
					document.getElementById("payzippy_request_Form").submit();
				</script>
		</div>
	</body>
</html>