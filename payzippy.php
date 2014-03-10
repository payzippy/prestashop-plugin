<?php

if (!defined('_PS_VERSION_'))
    exit;

include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/lib/ChargingRequest.php');

class payzippy extends PaymentModule {

    private $errors = array();

    public function __construct() {
        $config1 = new my_config();

        $this->name = 'payzippy';
        $this->tab = 'payments_gateways';
        $this->version = $config1::CURRENT_VERSION;
        $this->author = 'PayZippy';
        $this->currencies = true;
        $this->currencies_mode = 'radio';
        parent::__construct();
        $this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('PayZippy');
        $this->description = $this->l($config1::DESCRIPTION);
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall PayZippy plugin and delete all the details ?');
        $this->module_key = '261f1a24ee67c9421fa653f90336c3f2';
    }

    public function install() {
        if (!parent::install() || !Configuration::updateValue('MERCHANT_ID', null) || !Configuration::updateValue('MERCHANT_KEY_ID', null) || !Configuration::updateValue('SECRET_KEY', null) || !Configuration::updateValue('PAYMENT_BUTTON', null) || !Configuration::updateValue('UI_MODE', null) || !$this->registerHook('payment') || !$this->registerHook('paymentReturn'))
            return false;
        return true;
    }

    public function uninstall() {
        if (!Configuration::deleteByName('MERCHANT_ID') || !Configuration::deleteByName('MERCHANT_KEY_ID') || !Configuration::deleteByName('SECRET_KEY') || !Configuration::deleteByName('PAYMENT_BUTTON') || !Configuration::deleteByName('UI_MODE') || !parent::uninstall())
            return false;
        return true;
    }

    public function getContent() {
        if (isset($_REQUEST['update_settings'])) {
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

        //global $smarty;
        $configure = array(
            'URI' => $_SERVER['REQUEST_URI'],
            'merchant_id' => Configuration::get('MERCHANT_ID'),
            'merchant_key_id' => Configuration::get('MERCHANT_KEY_ID'),
            'secret_key' => Configuration::get('SECRET_KEY'),
            'error' => sizeof($this->errors),
            'error_name' => $this->errors,
            'settings_updated' => $settings_updated,
            'paybutton' => Configuration::get('PAYMENT_BUTTON'),
            'uimode' => Configuration::get('UI_MODE')
        );
        $this->smarty->assign('configure',$configure);
        return $this->display(__FILE__, '/views/templates/admin/configure_payzippy.tpl');
    }

    public function hookdisplayPayment($params) {
        $config = new my_config();
        
        if (!$this->active)
            return;
        //!$cart->OrderExists();
        $customer = new Customer($params['cart']->id_customer);
        $email_address = $customer->email;
        $currency = trim($this->getCurrency()->iso_code);
        $currency_config = $config::CURRENCY;
        if ($currency != $currency_config)
        	return;

        $Amount = $params['cart']->getOrderTotal(true, 3) * 100;
        $cartId = $params['cart']->id;
        
        $address = new Address($params['cart']->id_address_invoice);

        $products = $params['cart']->getProducts();
        $quantity = '';
        $product_name = '';
        $product_count = count($products);
        for ($i = 0; $i < $product_count; $i++) {
            $quantity .= $products[$i]['cart_quantity'] . ',';
            $product_name .= $products[$i]['name'] . ',';
        }

        $product_name = (Tools::strlen($product_name) > 100) ? Tools::substr($product_name, 0, 100) : $product_name;
        $complete_address = $address->address1 . ' ' . $address->address2;
        $complete_address = (Tools::strlen($complete_address) > 100) ? Tools::substr($complete_address, 0, 100) : $complete_address;
        $module_version = 'Presta' . '-' . Configuration::get('PS_INSTALL_VERSION') . '|' . $config::CURRENT_VERSION;
        $module_version = (Tools::strlen($module_version) > 20) ? Tools::substr($module_version, 0, 20) : $module_version;

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' && $_SERVER['HTTPS'] != 'OFF') {
            //TODO:: callback url, validate
            $redirect_url = 'https://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__ . 'modules/payzippy/validation.php';
        } else {
            $redirect_url = 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__ . 'modules/payzippy/validation.php';
        }

        $ps_charging = new ChargingRequest();
        $ps_charging->set_buyer_email_address($email_address)
                ->set_merchant_id(trim(Configuration::get('MERCHANT_ID')))
                ->set_merchant_key_id(Configuration::get('MERCHANT_KEY_ID'))
                ->set_transaction_type('SALE')
                ->set_ui_mode(Configuration::get('UI_MODE'))
                ->set_hash_method('SHA256')
                ->set_currency('INR')
                ->set_buyer_unique_id($customer->secure_key)
                ->set_buyer_phone_no($address->phone_mobile)
                ->set_billing_name($address->firstname . ' ' . $address->lastname)
                ->set_shipping_address($complete_address)
                ->set_shipping_city($address->city)
                ->set_shipping_country($address->country)
                ->set_shipping_zip($address->postcode)
                ->set_merchant_transaction_id($cartId . '||' . date('his') )
                ->set_transaction_amount($Amount)
                ->set_payment_method('PAYZIPPY')
                ->set_callback_url($redirect_url)
                ->set_product_info1(trim($product_name, ","))
                ->set_source($module_version)
                ->set_item_total(trim($quantity, ","))
                ->set_udf1($customer->secure_key)
                ->charge();

        //$hash_get = $ps_charging->get_hash();
        $request_url = $ps_charging->get_request_url();
        $param = $ps_charging->get_params();

$this->smarty->assign('param', $param);
$this->smarty->assign(array(
	'payment_button' => Configuration::get('PAYMENT_BUTTON'),
	'charging_url' => $request_url,
	'ui_mode' => Configuration::get('UI_MODE')
	));
        return $this->display(__FILE__, '/views/templates/front/payzippy.tpl');
    }

    public function validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown', $response_message, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null) {
        if (!$this->active)
            return;
        parent::validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method, $response_message, $extra_vars, $currency_special, true, $secure_key, null);
    }

}

?>