<?php
if (!defined('_PS_VERSION_'))
	exit;
include(dirname(__FILE__).'/config.php');

class payzippy extends PaymentModule
{
	private $errors = array();

	public function __construct()
	{
		//$config1 = new my_config();

		$this->name = 'payzippy';
		$this->tab = 'payments_gateways';
		$this->version = '1.0';
		$this->author = 'PayZippy';
		$this->currencies = true;
		$this->currencies_mode = 'radio';
		parent::__construct();
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('PayZippy');
		$this->description = $this->l('Accepts payments by PayZippy');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall PayZippy plugin and delete all the details ?');
		$this->module_key = '261f1a24ee67c9421fa653f90336c3f2';
	}

	public function PayZippyUrl()
	{
			return 'https://www.payzippy.com/payment/api/charging/v1';
	}

	public function install()
 {
			if (!parent::install()
			|| !Configuration::updateValue('MERCHANT_ID', null)
			|| !Configuration::updateValue('MERCHANT_KEY_ID', null)
			|| !Configuration::updateValue('SECRET_KEY', null)
			|| !Configuration::updateValue('PAYMENT_BUTTON', null)
			|| !Configuration::updateValue('UI_MODE', null)
			|| !$this->registerHook('payment')
			|| !$this->registerHook('paymentReturn'))
			return false;
			return true;
			}

	public function uninstall()
	{
		if (!Configuration::deleteByName('MERCHANT_ID')
		|| !Configuration::deleteByName('MERCHANT_KEY_ID')
		|| !Configuration::deleteByName('SECRET_KEY')
		|| !Configuration::deleteByName('PAYMENT_BUTTON')    	 
		|| !Configuration::deleteByName('UI_MODE')    	
		|| !parent::uninstall())
			return false;
		return true;
	}
    
	public function getContent()
	{
		if (isset($_REQUEST['update_settings']))
		{
			if (empty($_REQUEST['merchant_id']))
				$this->errors[] = $this->l('Merchant Id is required.');
			if (empty($_REQUEST['merchant_key_id']))
				$this->errors[] = $this->l('Merchant Key Id is required.');
			if (empty($_REQUEST['secretkey']))
				$this->errors[] = $this->l('Secret Key is required.');

			if (!sizeof($this->errors))
				$settings_updated = 1;
			else
				$settings_updated = 0;

        		Configuration::updateValue('MERCHANT_ID', $_REQUEST['merchant_id']);
				Configuration::updateValue('SECRET_KEY', $_REQUEST['secretkey']);
				Configuration::updateValue('MERCHANT_KEY_ID', $_REQUEST['merchant_key_id']);
				Configuration::updateValue('PAYMENT_BUTTON', $_REQUEST['payment_button']);
				Configuration::updateValue('UI_MODE', $_REQUEST['ui_mode']);

		}
            $checked1 = $checked2 = $checked3 = $checked4 = $checked5 = $selected1 = $selected2 = null;
			if (Configuration::get('PAYMENT_BUTTON') == 'Paybutton-1')
				$checked1 = 'checked';
			if (Configuration::get('PAYMENT_BUTTON') == 'Paybutton-2')
				$checked2 = 'checked';
			if (Configuration::get('PAYMENT_BUTTON') == 'Paybutton-3')
				$checked3 = 'checked';
			if (Configuration::get('PAYMENT_BUTTON') == 'Paybutton-4')
				$checked4 = 'checked';
			if (Configuration::get('PAYMENT_BUTTON') == 'Paybutton-5')
				$checked5 = 'checked';

			if (Configuration::get('UI_MODE') == 'REDIRECT')
				$selected1 = 'selected';
			if (Configuration::get('UI_MODE') == 'IFRAME')
				$selected2 = 'selected';

		//global $smarty;
		$this->smarty->assign(array(
		'URI' => $_SERVER['REQUEST_URI'],
		'merchant_id' => Configuration::get('MERCHANT_ID'),
		'merchant_key_id' => Configuration::get('MERCHANT_KEY_ID'),
		'secret_key' => Configuration::get('SECRET_KEY'),
		'error' => sizeof($this->errors),
		'error_name' => $this->errors,
		'settings_updated' => $settings_updated,
		'checked1' => $checked1,
		'checked2' => $checked2,
		'checked3' => $checked3,
		'checked4' => $checked4,
		'checked5' => $checked5,
		'selected1' => $selected1,
		'selected2' => $selected2,
		));
		return $this->display(__FILE__, '/views/templates/admin/configure_payzippy.tpl');
	}	

public function hookdisplayPayment($params)
	{
		$config = new my_config();
		if (!$this->active)
		return ;
	//global $smarty;
	//!$cart->OrderExists();
		$customer = new Customer($params['cart']->id_customer);
		$email_address = $customer->email;
		$address = new Address($params['cart']->id_address_invoice);
		$currency = trim($this->getCurrency()->iso_code);
		$Amount = number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 3), $this->getCurrency()), 2, '', '');
		$cartId = $params['cart']->id;

		if (!Validate::isLoadedObject($address) || !Validate::isLoadedObject($customer))
			return $this->l('Invalid address or customer)');

	$products = $params['cart']->getProducts();
	$quantity = '';
	$product_name = '';
	$product_count = count($products);
	for ($i = 0; $i < $product_count; $i++)
{
		$quantity .= $products[$i]['cart_quantity'].',';
		$product_name .= $products[$i]['name'].',';
}
$product_name = (Tools::strlen($product_name) > 100) ? Tools::substr($product_name,0,100) : $product_name;
$complete_address = $address->address1.' '.$address->address2;
$complete_address = (Tools::strlen($complete_address) > 100) ? Tools::substr($complete_address,0,100) : $complete_address;
$module_version = 'Presta'.'/'.Configuration::get('PS_INSTALL_VERSION').'|'.$config::CURRENT_VERSION;
$module_version = (Tools::strlen($module_version) > 20) ? Tools::substr($module_version,0,20) : $module_version;


if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' && $_SERVER['HTTPS'] != 'OFF')
{
$redirect_Url = 'https://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/payzippy/validation.php';
$payment_request = 'https://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/payzippy/payment_request.php';
} else {
$redirect_Url = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/payzippy/validation.php';
$payment_request = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/payzippy/payment_request.php';
}	

		$this->smarty->assign(array(
			'address' => $complete_address,
			'city' => $address->city,
			'zip' => $address->postcode,
			'country' => $address->country,
			'buyer_name' => $address->firstname.' '.$address->lastname,
			'email_address' => $email_address,
			'phone' => $address->phone_mobile,
			'MerchantId' => trim(Configuration::get('MERCHANT_ID')),
			'OrderId' => $cartId.'||'.date('Ymdhis'),
			'secure_key' => $customer->secure_key,
			'currencyType' => $currency,
			'PayZippyUrl' => $this->PayZippyUrl(),
			'Amount' => $Amount,
			'Redirect_Url' => $redirect_Url,
			'payment_request' => $payment_request,
			'merchant_key_id' => Configuration::get('MERCHANT_KEY_ID'),
			'prestashop_version' => $module_version,
			'product_info' => trim($product_name, ","),
			'quantity' => trim($quantity, ","),
			'payment_button' => Configuration::get('PAYMENT_BUTTON'),
			'ui_mode' => Configuration::get('UI_MODE')
		));
		return $this->display(__FILE__, '/views/templates/front/payzippy.tpl');
	}

	public function validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown', $response_message, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null)
	{
		if (!$this->active)
		return ;
		parent::validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method, $response_message, $extra_vars, $currency_special, true, $secure_key, null);
	}


}

?>