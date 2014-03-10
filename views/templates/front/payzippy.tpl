<html>
<head>
<script>
//			$(document).ready(function(){
			var clicked = false;
                  //$("#my_a").on('click',function(){
                  	function iframe_function(){
                                        if(clicked==false){
                                        clicked = true;
                                        document.getElementById('ifr').src = "{$charging_url}";
                                        //$("#ifr").attr("src","{$charging_url}");
                                        //$("#iframe_div").show();
									    document.getElementById("iframe_div").style.display = 'block';
                                      	}
                                    	else{
                                    	//$("#iframe_div").hide();
                                    	document.getElementById("iframe_div").style.display = 'none';
                                    	clicked = false;
                                    	}
                                    }
                                        //});

//			});
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

<p class="payment_module">
{if $ui_mode == REDIRECT}
<form  class="payment_method_form" action="{$charging_url}" method="post" id="payment_method_form">
                
                {foreach $param as $key => $val}
                <input type=hidden name="{$key}" value="{$val}">
                {/foreach}

</form>
<a href="javascript:document.getElementById('payment_method_form').submit();"> <img src="{$module_template_dir}img/{$payment_button}.png" alt="Pay with your Credit/Debit card or with your PayZippy Account" style="vertical-align: middle;" >
	Pay with your Credit/Debit card or with your PayZippy Account</a>
{/if}
{if $ui_mode == IFRAME}
<a id="my_a" href="javascript:void(0)" onclick="iframe_function()"><img src="{$module_template_dir}img/{$payment_button}.png" alt="Pay with your Credit/Debit card or with your PayZippy Account" style="vertical-align: middle;" >
	Pay with your Credit/Debit card or with your PayZippy Account</a>
{/if}
<div id="iframe_div">
    <iframe id="ifr"></iframe> 
    </p>

</div>
</div>
</body>
</html>