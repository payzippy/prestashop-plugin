<html>
<head>
<script type='text/javascript' src='http://code.jquery.com/jquery-1.10.2.js'></script>
<script>
			$(document).ready(function(){
			var clicked = false;
                  $("#my_a").on('click',function(){
                                        if(clicked==false){
                                        clicked = true;

                                        $("#ifr").attr("src","{$payment_request}?merchant_id={$MerchantId}&buyer_email_address={$email_address}&merchant_transaction_id={$OrderId}&transaction_type=SALE&transaction_amount={$Amount}&currency={$currencyType}&ui_mode={$ui_mode}&hash_method=SHA256&merchant_key_id={$merchant_key_id}&callback_url={$Redirect_Url}&buyer_unique_id={$secure_key}&shipping_address={$address}&shipping_city={$city}&shipping_zip={$zip}&shipping_country={$country}&source={$prestashop_version}&item_total={$quantity}&product_info1={$product_info}&buyer_phone_no={$phone}");
                                        $("#iframe_div").show();

                                      	}
                                    	else{
                                    	$("#iframe_div").hide();
                                    	clicked = false;
                                    	}
                                        });

			});
</script>
<style>
#iframe_div{
	display: none;
}
#ifr{
	overflow-y: scroll;
	overflow-x: scroll;
	width:675px; !important;
	height:400px !important;
	border-color: #d9d9d9;
	border: 1px solid #D6D4D4;
}
</style>
</head>
<body>
<div>
<form  class="payment_method_form" action="{$payment_request}" method="post" id="payment_method_form">
		<div class="hidden">
		<!-- Mandatory params -->
			<input type=hidden name="merchant_id"   		   value="{$MerchantId}">
			<input type=hidden name="buyer_email_address" 	   value="{$email_address}">
			<input type=hidden name="merchant_transaction_id"  value="{$OrderId}">
			<input type=hidden name="transaction_type"         value="SALE">
			<input type=hidden name="transaction_amount"       value="{$Amount}">
			<input type=hidden name="currency"                 value="{$currencyType}">
			<input type=hidden name="ui_mode"                  value="{$ui_mode}">
			<input type=hidden name="hash_method"              value="SHA256"> 
			<input type=hidden name="merchant_key_id"          value="{$merchant_key_id}">
			<input type=hidden name="callback_url"             value="{$Redirect_Url}">
		<!-- optional params but useful in fraud detection-->
			<input type=hidden name="buyer_unique_id"          value="{$secure_key}">
			<input type=hidden name="shipping_address"         value="{$address}">
			<input type=hidden name="shipping_city"            value="{$city}">
			<input type=hidden name="shipping_zip"             value="{$zip}">
			<input type=hidden name="shipping_country"         value="{$country}">
			<input type=hidden name="source"          		   value="{$prestashop_version}">
			<input type=hidden name="item_total"          	   value="{$quantity}">
			<input type=hidden name="product_info1"            value="{$product_info}">
			<input type=hidden name="buyer_phone_no"           value="{$phone}">
		</div>
</form>
<p class="payment_module">
{if $ui_mode == REDIRECT}
<a href="javascript:$('#payment_method_form').submit();"> <img src="{$module_template_dir}configure/{$payment_button}.png" alt="Pay with your Credit/Debit card or with your PayZippy Account" style="vertical-align: middle;" >
	Pay with your Credit/Debit card or with your PayZippy Account</a>
{/if}
{if $ui_mode == IFRAME}
<a id="my_a" href="javascript:void(0)"><img src="{$module_template_dir}configure/{$payment_button}.png" alt="Pay with your Credit/Debit card or with your PayZippy Account" style="vertical-align: middle;" >
	Pay with your Credit/Debit card or with your PayZippy Account</a>
{/if}
<div id="iframe_div">
    <iframe id="ifr"></iframe> 
    </p>

</div>
</div>
</body>
</html>