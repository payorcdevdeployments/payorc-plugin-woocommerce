<?php
/**
 * PayOrc
 *
 * @package PayOrc
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * PayOrc Class
 */
class WC_PayOrc_Payment_Gateway extends WC_Payment_Gateway {
    /**
     * Country codes with their dialing codes
     */
    public $countries = ["AW"=>"297","AF"=>"93","AO"=>"244","AI"=>"1-264","AX"=>"358-18","AL"=>"355","AD"=>"376","AR"=>"54","AM"=>"374","AS"=>"1-684","AQ"=>"672","TF"=>"262","AG"=>"1-268","AU"=>"61","AT"=>"43","AZ"=>"994","BI"=>"257","BE"=>"32","BJ"=>"229","BQ"=>"599","BF"=>"226","BD"=>"880","BG"=>"359","BH"=>"973","BS"=>"1-242","BA"=>"387","BL"=>"590","BY"=>"375","BZ"=>"501","BM"=>"1-441","BO"=>"591","BR"=>"55","BB"=>"1-246","BN"=>"673","BT"=>"975","BV"=>"47","BW"=>"267","CF"=>"236","CA"=>"1","CC"=>"61","CH"=>"41","CL"=>"56","CN"=>"86","CI"=>"225","CM"=>"237","CD"=>"243","CG"=>"242","CK"=>"682","CO"=>"57","KM"=>"269","CV"=>"238","CR"=>"506","CU"=>"53","CW"=>"599","CX"=>"61","KY"=>"1-345","CY"=>"357","CZ"=>"420","DE"=>"49","DJ"=>"253","DM"=>"1-767","DK"=>"45","DO"=>"1-809","DZ"=>"213","EC"=>"593","EG"=>"20","ER"=>"291","EH"=>"212","ES"=>"34","EE"=>"372","ET"=>"251","FI"=>"358","FJ"=>"679","FK"=>"500","FR"=>"33","FO"=>"298","FM"=>"691","GA"=>"241","GB"=>"44","GE"=>"995","GG"=>"44","GH"=>"233","GI"=>"350","GN"=>"224","GP"=>"590","GM"=>"220","GW"=>"245","GQ"=>"240","GR"=>"30","GD"=>"1-473","GL"=>"299","GT"=>"502","GF"=>"594","GU"=>"1-671","GY"=>"592","HK"=>"852","HM"=>"61","HN"=>"504","HR"=>"385","HT"=>"509","HU"=>"36","ID"=>"62","IM"=>"44","IN"=>"91","IO"=>"246","IE"=>"353","IR"=>"98","IQ"=>"964","IS"=>"354","IL"=>"972","IT"=>"39","JM"=>"1-876","JE"=>"44","JO"=>"962","JP"=>"81","KZ"=>"7","KE"=>"254","KG"=>"996","KH"=>"855","KI"=>"686","KN"=>"1-869","KR"=>"82","KW"=>"965","LA"=>"856","LB"=>"961","LR"=>"231","LY"=>"218","LC"=>"1-758","LI"=>"423","LK"=>"94","LS"=>"266","LT"=>"370","LU"=>"352","LV"=>"371","MO"=>"853","MF"=>"590","MA"=>"212","MC"=>"377","MD"=>"373","MG"=>"261","MV"=>"960","MX"=>"52","MH"=>"692","MK"=>"389","ML"=>"223","MT"=>"356","MM"=>"95","ME"=>"382","MN"=>"976","MP"=>"1-670","MZ"=>"258","MR"=>"222","MS"=>"1-664","MQ"=>"596","MU"=>"230","MW"=>"265","MY"=>"60","YT"=>"262","NA"=>"264","NC"=>"687","NE"=>"227","NF"=>"672","NG"=>"234","NI"=>"505","NU"=>"683","NL"=>"31","NO"=>"47","NP"=>"977","NR"=>"674","NZ"=>"64","OM"=>"968","PK"=>"92","PA"=>"507","PN"=>"64","PE"=>"51","PH"=>"63","PW"=>"680","PG"=>"675","PL"=>"48","PR"=>"1-787","KP"=>"850","PT"=>"351","PY"=>"595","PS"=>"970","PF"=>"689","QA"=>"974","RE"=>"262","RO"=>"40","RU"=>"7","RW"=>"250","SA"=>"966","SD"=>"249","SN"=>"221","SG"=>"65","GS"=>"500","SH"=>"290","SJ"=>"47","SB"=>"677","SL"=>"232","SV"=>"503","SM"=>"378","SO"=>"252","PM"=>"508","RS"=>"381","SS"=>"211","ST"=>"239","SR"=>"597","SK"=>"421","SI"=>"386","SE"=>"46","SZ"=>"268","SX"=>"1-721","SC"=>"248","SY"=>"963","TC"=>"1-649","TD"=>"235","TG"=>"228","TH"=>"66","TJ"=>"992","TK"=>"690","TM"=>"993","TL"=>"670","TO"=>"676","TT"=>"1-868","TN"=>"216","TR"=>"90","TV"=>"688","TW"=>"886","TZ"=>"255","UG"=>"256","UA"=>"380","UM"=>"1","UY"=>"598","US"=>"1","UZ"=>"998","VA"=>"379","VC"=>"1-784","VE"=>"58","VG"=>"1-284","VI"=>"1-340","VN"=>"84","VU"=>"678","WF"=>"681","WS"=>"685","YE"=>"967","ZA"=>"27","ZM"=>"260","ZW"=>"263"];

    /**
     * Test mode
     *
     * @var string
     */
    public $testmode;

    /**
     * Merchant key
     *
     * @var string
     */
    public $merchant_key;

    /**
     * Merchant secret
     *
     * @var string
     */
    public $merchant_secret;

    /**
     * Checkout mode
     *
     * @var string
     */
    public $checkout_mode;

    /**
     * Action type
     *
     * @var string
     */
    public $action_type;

    /**
     * Capture method
     *
     * @var string
     */
    public $capture_method;

    /**
     * API base URL
     *
     * @var string
     */
    public $api_base_url;

    /**
     * Constructor for the gateway
     */
    public function __construct() {
        $this->id                 = 'payorc';
        $this->icon              = apply_filters('woocommerce_payorc_icon', '');
        $this->has_fields        = false;
        $this->method_title      = __('PayOrc - Pay with Visa / MasterCard / Amex / Apple Pay', 'payorc-payments');
        $this->method_description = __('Accept payments through PayOrc', 'payorc-payments');
        
        // Load the settings
        $this->init_form_fields();
        $this->init_settings();
        
        // Define user set variables
        $this->title            = $this->get_option('title', __('PayOrc - Pay with Visa / MasterCard / Amex / Apple Pay', 'payorc-payments'));
        $this->description      = $this->get_option('description');
        $this->enabled          = $this->get_option('enabled');
        $this->testmode        = 'yes' === $this->get_option('testmode');
        $this->merchant_key    = $this->testmode ? $this->get_option('test_merchant_key') : $this->get_option('live_merchant_key');
        $this->merchant_secret = $this->testmode ? $this->get_option('test_merchant_secret') : $this->get_option('live_merchant_secret');
        $this->checkout_mode   = $this->get_option('checkout_mode');
        $this->action_type     = $this->get_option('action_type');
        $this->capture_method  = $this->get_option('capture_method');
        
        // API endpoints
        $this->api_base_url    = 'https://nodeserver.payorc.com/api/v1';
        
        // Actions
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_api_wc_payorc_payment_gateway', array($this, 'webhook'));
        add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));
        add_action('wp_ajax_payorc_get_transaction', array($this, 'ajax_get_transaction'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        
        // Only add payment validation for actual payment processing
        if (is_checkout() && !is_wc_endpoint_url('order-pay')) {
            add_action('wp_ajax_payorc_validate_payment', array($this, 'validate_payment'));
            add_action('wp_ajax_nopriv_payorc_validate_payment', array($this, 'validate_payment'));
        }
        
        // Add test card information for test mode
        if ($this->testmode === 'yes') {
            $this->description .= '<br/><br/>' . __('TEST MODE ENABLED<br/>Please use below test card info:<br/>4012 0010 3714 1112 (for success payment)<br/>4012 0010 3716 7778 (3D Secure authentication)<br/>You can use any expiry date as 01/30 and CVC code as 123 for card.', 'payorc-payments');
        }
    }

    /**
     * Get country code
     */
    private function get_country_code($country) {
        return isset($this->countries[$country]) ? $this->countries[$country] : '';
    }

    /**
     * Process the payment
     */
    public function process_payment($order_id) {
        global $woocommerce;
        
        try {
            
            $order = wc_get_order($order_id);
            if (!$order) {
                error_log('PayOrc Error: Invalid order ID ' . $order_id);
                throw new Exception('Invalid order ID');
            }

            // Get browser information
            $browser_info = $this->get_browser_info();
            // Get country codes
            $billing_country_code = $this->get_country_code($order->get_billing_country());
            $shipping_country_code = $this->get_country_code($order->get_shipping_country());

            // Prepare the payment data
            $payment_data = array(
                'data' => array(
                    'class' => 'ECOM',
                    'action' => $this->action_type,
                    'capture_method' => $this->capture_method,
                    'payment_token' => '',
                    'order_details' => array(
                        'm_order_id' => (string) $order->get_id(),
                        'amount' => number_format($order->get_total(), 2, '.', ''),
                        'convenience_fee' => '0',
                        'currency' => $order->get_currency(),
                        'description' => sprintf(__('Order %s', 'payorc-payments'), $order->get_order_number()),
                        'quantity' => '1'
                    ),
                    'customer_details' => array(
                        'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                        'm_customer_id' => (string) ($order->get_customer_id() ? $order->get_customer_id() : ''),
                        'email' => $order->get_billing_email(),
                        'mobile' => $order->get_billing_phone(),
                        'code' => $billing_country_code
                    ),
                    'billing_details' => array(
                        'address_line1' => $order->get_billing_address_1(),
                        'address_line2' => $order->get_billing_address_2(),
                        'city' => $order->get_billing_city(),
                        'province' => $order->get_billing_state(),
                        'pin' => $order->get_billing_postcode(),
                        'country' => $order->get_billing_country()
                    ),
                    'shipping_details' => array(
                        'shipping_name' => $order->get_shipping_method(),
                        'shipping_email' => '',
                        'shipping_code' => $shipping_country_code,
                        'shipping_mobile' => $order->get_billing_phone(),
                        'address_line1' => $order->get_shipping_address_1(),
                        'address_line2' => $order->get_shipping_address_2(),
                        'city' => $order->get_shipping_city(),
                        'province' => $order->get_shipping_state(),
                        'pin' => $order->get_shipping_postcode(),
                        'country' => $order->get_shipping_country(),
                        'location_pin' => '',
                        'shipping_currency' => $order->get_currency(),
                        'shipping_amount' => number_format($order->get_shipping_total(), 2, '.', '')
                    ),
                    'urls' => array(
                        'success' => add_query_arg(array(
                            'wc-api' => 'wc_payorc_payment_gateway',
                            'payorc-action' => 'success',
                            'order_id' => $order_id
                        ), home_url('/')),
                        'cancel' => wc_get_checkout_url(),
                        'failure' => add_query_arg(array(
                            'wc-api' => 'wc_payorc_payment_gateway',
                            'payorc-action' => 'failure',
                            'order_id' => $order_id
                        ), home_url('/'))
                    ),
                    'parameters' => array(
                        array('alpha' => ''),
                        array('beta' => ''),
                        array('gamma' => ''),
                        array('delta' => ''),
                        array('epsilon' => '')
                    ),
                    'custom_data' => array(
                        array('alpha' => ''),
                        array('beta' => ''),
                        array('gamma' => ''),
                        array('delta' => ''),
                        array('epsilon' => '')
                    )
                )
            );

            // Make API call to create order
            $response = wp_remote_post($this->api_base_url . '/sdk/orders/create', array(
                'timeout' => 45,
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'merchant-key' => $this->merchant_key,
                    'merchant-secret' => $this->merchant_secret,
                    'platform' => $this->get_platform(),
                    'browser' => $browser_info['browser'],
                    'browser-version' => $browser_info['version']
                ),
                'body' => json_encode($payment_data)
            ));

            if (is_wp_error($response)) {
                error_log('PayOrc API Error: ' . $response->get_error_message());
                throw new Exception($response->get_error_message());
            }

            $body = json_decode(wp_remote_retrieve_body($response), true);

            if (!isset($body['status']) || !isset($body['status_code'])) {
                error_log('PayOrc Error: Invalid API response format');
                throw new Exception($body['message'] ?? 'Invalid API response format');
            }

            if ($body['status'] !== 'SUCCESS' || $body['status_code'] !== '00') {
                error_log('PayOrc Error: ' . (isset($body['message']) ? $body['message'] : 'Unknown error'));
                throw new Exception(isset($body['message']) ? $body['message'] : 'Unknown error');
            }

            // Store payment details
            update_post_meta($order_id, '_payorc_order_id', $body['p_order_id']);
            update_post_meta($order_id, '_payorc_request_id', $body['p_request_id']);

            // Return based on checkout mode
            if ($this->checkout_mode === 'hosted') {
                error_log('PayOrc: Redirecting to hosted checkout: ' . $body['payment_link']);
                return array(
                    'result' => 'success',
                    'redirect' => $body['payment_link']
                );
            } else {
                error_log('PayOrc: Setting up iframe checkout');
                WC()->session->set('payorc_iframe_url', $body['iframe_link']);
                return array(
                    'result' => 'success',
                    'redirect' => add_query_arg(
                        array(
                            'iframe_url' => urlencode($body['iframe_link'])
                        ),
                        wc_get_checkout_url()
                    )
                );
            }
        } catch (Exception $e) {
            error_log('PayOrc Process Payment Exception: ' . $e->getMessage());
            wc_add_notice(__('Payment error: ', 'payorc-payments') . $e->getMessage(), 'error');
            return array('result' => 'fail');
        }
    }

    /**
     * Handle the webhook and return URLs
     */
    public function webhook_handler() {
        if (isset($_GET['payorc-action'])) {
            error_log('came here');
            $action = sanitize_text_field($_GET['payorc-action']);
            $order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
            $order = wc_get_order($order_id);
            error_log('Order ID - '.$order_id);
            error_log('Order - '.print_r($order, true));
            if (!$order) {
                wp_redirect(wc_get_checkout_url());
                exit;
            }

            error_log('action - '.$action);
            $gateway = new WC_PayOrc_Payment_Gateway();
            switch ($action) {
                case 'success':
                    $order->payment_complete();
                    //$order->add_order_note(__('PayOrc payment completed', 'payorc-payments'));
                    $gateway->store_payment_info($_REQUEST, $order);
                    wp_redirect($gateway->get_return_url($order));
                    exit;

                case 'failure':
                    $order->update_status('failed', __('PayOrc payment failed', 'payorc-payments'));
                    wc_add_notice(__('Payment failed. Please try again.', 'payorc-payments'), 'error');
                    wp_redirect(wc_get_checkout_url());
                    exit;
            }
        }

        // Handle regular webhooks
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);

        if (!$this->verify_webhook_signature($_SERVER['HTTP_X_PAYORC_SIGNATURE'], $payload)) {
            status_header(400);
            exit('Invalid signature');
        }

        $order_id = $data['order_id'];
        $order = wc_get_order($order_id);

        if (!$order) {
            status_header(404);
            exit('Order not found');
        }

        switch ($data['status']) {
            case 'COMPLETED':
                $order->payment_complete();
                $order->add_order_note(__('PayOrc payment completed', 'payorc-payments'));
                break;
            case 'FAILED':
                $order->update_status('failed', __('PayOrc payment failed', 'payorc-payments'));
                break;
            case 'PENDING':
                $order->update_status('on-hold', __('PayOrc payment pending', 'payorc-payments'));
                break;
        }

        status_header(200);
        exit('Webhook processed');
    }

    /**
     * Initialize Gateway Settings Form Fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'       => __('Enable/Disable', 'payorc-payments'),
                'type'        => 'checkbox',
                'label'       => __('Enable PayOrc', 'payorc-payments'),
                'default'     => 'no'
            ),
            'title' => array(
                'title'       => __('Title', 'payorc-payments'),
                'type'        => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'payorc-payments'),
                'default'     => __('PayOrc - Pay with Visa / MasterCard / Amex / Apple Pay', 'payorc-payments'),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __('Description', 'payorc-payments'),
                'type'        => 'textarea',
                'description' => __('This controls the description which the user sees during checkout.', 'payorc-payments'),
                'default'     => __('Pay securely through PayOrc.', 'payorc-payments'),
                'desc_tip'    => true,
            ),
            'testmode' => array(
                'title'       => __('Test mode', 'payorc-payments'),
                'type'        => 'checkbox',
                'label'       => __('Enable Test Mode', 'payorc-payments'),
                'default'     => 'yes',
                'description' => __('Place the payment gateway in test mode.', 'payorc-payments'),
            ),
            'test_merchant_key' => array(
                'title'       => __('Test Merchant Key', 'payorc-payments'),
                'type'        => 'text',
                'description' => __('Enter your Test Merchant Key', 'payorc-payments'),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_merchant_secret' => array(
                'title'       => __('Test Merchant Secret', 'payorc-payments'),
                'type'        => 'password',
                'description' => __('Enter your Test Merchant Secret', 'payorc-payments'),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'live_merchant_key' => array(
                'title'       => __('Live Merchant Key', 'payorc-payments'),
                'type'        => 'text',
                'description' => __('Enter your Live Merchant Key', 'payorc-payments'),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'live_merchant_secret' => array(
                'title'       => __('Live Merchant Secret', 'payorc-payments'),
                'type'        => 'password',
                'description' => __('Enter your Live Merchant Secret', 'payorc-payments'),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'checkout_mode' => array(
                'title'       => __('Checkout Mode', 'payorc-payments'),
                'type'        => 'select',
                'description' => __('Choose how the payment form should be displayed', 'payorc-payments'),
                'default'     => 'iframe',
                'options'     => array(
                    'iframe'  => __('iFrame', 'payorc-payments'),
                    'hosted'  => __('Hosted Checkout', 'payorc-payments')
                ),
                'desc_tip'    => true,
            ),
            'action_type' => array(
                'title'       => __('Action', 'payorc-payments'),
                'type'        => 'select',
                'description' => __('Choose the payment action type', 'payorc-payments'),
                'default'     => 'SALE',
                'options'     => array(
                    'SALE'    => __('SALE', 'payorc-payments'),
                    'AUTH'    => __('AUTH', 'payorc-payments')
                ),
                'desc_tip'    => true,
            ),
            'capture_method' => array(
                'title'       => __('Capture Method', 'payorc-payments'),
                'type'        => 'select',
                'description' => __('Choose the capture method', 'payorc-payments'),
                'default'     => 'AUTOMATIC',
                'options'     => array(
                    'AUTOMATIC' => __('AUTOMATIC', 'payorc-payments'),
                    'MANUAL'    => __('MANUAL', 'payorc-payments')
                ),
                'desc_tip'    => true,
            )
        );
    }

    /**
     * Validate merchant credentials
     */
    private function validate_merchant_credentials() {
        $merchant_key = $this->testmode ? $this->get_option('test_merchant_key') : $this->get_option('live_merchant_key');
        $merchant_secret = $this->testmode ? $this->get_option('test_merchant_secret') : $this->get_option('live_merchant_secret');

        $validation_data = array(
            'merchant_key' => $merchant_key,
            'merchant_secret' => $merchant_secret,
            'env' => $this->testmode ? 'test' : 'live'
        );

        $response = wp_remote_post($this->api_base_url . '/check/keys-secret', array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($validation_data)
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return isset($body['status']) && $body['status'] === 'success' && $body['code'] === '00';
    }

    /**
     * Get browser information
     */
    private function get_browser_info() {
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        
        $browser = 'Unknown';
        $version = '';

        if (preg_match('/MSIE|Trident/i', $user_agent)) {
            $browser = 'Internet Explorer';
            preg_match('/(?:MSIE |rv:)(\d+(\.\d+)?)/i', $user_agent, $matches);
            $version = $matches[1] ?? '';
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            $browser = 'Firefox';
            preg_match('/Firefox\/([0-9\.]+)/i', $user_agent, $matches);
            $version = $matches[1] ?? '';
        } elseif (preg_match('/Edge/i', $user_agent)) {
            $browser = 'Edge';
            preg_match('/Edge\/([0-9\.]+)/i', $user_agent, $matches);
            $version = $matches[1] ?? '';
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            $browser = 'Chrome';
            preg_match('/Chrome\/([0-9\.]+)/i', $user_agent, $matches);
            $version = $matches[1] ?? '';
        } elseif (preg_match('/Safari/i', $user_agent)) {
            $browser = 'Safari';
            preg_match('/Version\/([0-9\.]+)/i', $user_agent, $matches);
            $version = $matches[1] ?? '';
        } elseif (preg_match('/Opera|OPR/i', $user_agent)) {
            $browser = 'Opera';
            preg_match('/(?:Opera|OPR)\/([0-9\.]+)/i', $user_agent, $matches);
            $version = $matches[1] ?? '';
        }

        return array(
            'browser' => $browser,
            'version' => $version
        );
    }

    /**
     * Get platform information
     */
    private function get_platform() {
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        
        if (preg_match('/linux|android/i', $user_agent)) {
            return 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            return 'MacOS';
        } elseif (preg_match('/windows|win32/i', $user_agent)) {
            return 'Windows';
        } elseif (preg_match('/iphone|ipad|ipod/i', $user_agent)) {
            return 'iOS';
        }
        
        return 'Unknown';
    }

    /**
     * Add payment scripts
     */
    public function payment_scripts() {
        if (!is_checkout() || $this->enabled !== 'yes') {
            return;
        }

        wp_enqueue_script(
            'payorc-js',
            plugins_url('assets/js/payorc.js', PAYORC_PLUGIN_FILE),
            array('jquery'),
            PAYORC_VERSION,
            true
        );

        wp_localize_script(
            'payorc-js',
            'payorc_params',
            array(
                'checkout_mode' => $this->checkout_mode,
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('payorc-payment-validation'),
                'plugin_url' => PAYORC_PLUGIN_URL
            )
        );
    }

    /**
     * Handle payment validation AJAX request
     */
    public static function validate_payment() {
        try {
            error_log('PayOrc: Starting payment validation');
            
            $gateway = new WC_PayOrc_Payment_Gateway();
            $payment_data = isset($_POST['payment_data']) ? wp_unslash($_POST['payment_data']) : array();
            
            error_log('PayOrc: Payment data received: ' . print_r($payment_data, true));
            
            // Get order ID from payment data if session is expired
            $order_id = WC()->session->get('order_awaiting_payment');
            if (!$order_id && isset($payment_data['m_order_id'])) {
                $order_id = $payment_data['m_order_id'];
            }

            error_log('PayOrc: Order ID from session: ' . $order_id);

            if (!$order_id) {
                error_log('PayOrc: No order ID found');
                wp_send_json_error(array(
                    'message' => 'Order ID not found',
                    'redirect_url' => wc_get_checkout_url()
                ));
                return;
            }

            $order = wc_get_order($order_id);
            if (!$order) {
                error_log('PayOrc: Order not found for ID: ' . $order_id);
                wp_send_json_error(array(
                    'message' => 'Order not found',
                    'redirect_url' => wc_get_checkout_url()
                ));
                return;
            }

            error_log('PayOrc: Validating order: ' . $order_id);
            
            if ($gateway->is_valid_order($payment_data)) {
                error_log('PayOrc: Order validation successful');
                $gateway->store_payment_info($payment_data, $order);
                $order->payment_complete();
                $order->add_order_note(__('PayOrc payment completed', 'payorc-payments'));
                
                wp_send_json_success(array(
                    'redirect_url' => $gateway->get_return_url($order)
                ));
            } else {
                error_log('PayOrc: Order validation failed');
                $order->update_status('failed', __('PayOrc payment failed', 'payorc-payments'));
                wp_send_json_error(array(
                    'message' => 'Payment validation failed',
                    'redirect_url' => wc_get_checkout_url()
                ));
            }
        } catch (Exception $e) {
            error_log('PayOrc Payment Validation Error: ' . $e->getMessage());
            wp_send_json_error(array(
                'message' => 'An error occurred during payment validation',
                'redirect_url' => wc_get_checkout_url()
            ));
        }
        
        wp_die();
    }

    /**
     * Check if the order is valid
     */
    protected function is_valid_order($payment_data) {
        return isset($payment_data['status_code']) 
            && isset($payment_data['status'])
            && $payment_data['status_code'] == '00' 
            && $payment_data['status'] == 'SUCCESS';
    }

    /**
     * Store payment information in the database
     */
    protected function store_payment_info($payment_data, $order) {
        global $wpdb;

        // Convert payment data to array if it's a string
        if (is_string($payment_data)) {
            $payment_data = json_decode($payment_data, true);
        }

        $data = array(
            'type' => 'payment',
            'source_type' => isset($payment_data['payment_method']) ? strtolower($payment_data['payment_method']) : 'card',
            'p_request_id' => isset($payment_data['p_request_id']) ? $payment_data['p_request_id'] : null,
            'm_payment_token' => isset($payment_data['m_payment_token']) ? $payment_data['m_payment_token'] : null,
            'p_order_id' => isset($payment_data['p_order_id']) ? $payment_data['p_order_id'] : null,
            'id_customer' => isset($payment_data['m_customer_id']) ? $payment_data['m_customer_id'] : $order->get_customer_id(),
            'id_cart' => WC()->session->get('cart_id'),
            'id_order' => $order->get_id(),
            'transaction_id' => isset($payment_data['transaction_id']) ? $payment_data['transaction_id'] : null,
            'amount' => isset($payment_data['amount']) ? $payment_data['amount'] : null,
            'status' => isset($payment_data['status']) ? $payment_data['status'] : 'pending',
            'response' => json_encode($payment_data),
            'currency' => isset($payment_data['currency']) ? $payment_data['currency'] : null,
            'cc_schema' => isset($payment_data['payment_method_data']['scheme']) ? $payment_data['payment_method_data']['scheme'] : null,
            'cc_type' => isset($payment_data['payment_method_data']['card_type']) ? $payment_data['payment_method_data']['card_type'] : null,
            'cc_mask' => isset($payment_data['payment_method_data']['mask_card_number']) ? $payment_data['payment_method_data']['mask_card_number'] : null,
            'mode' => $this->testmode ? 'test' : 'live',
            'date_add' => current_time('mysql')
        );

        // Remove null values
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        try {
            // Check if transaction already exists
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}payorc_transaction WHERE transaction_id = %s",
                $payment_data['transaction_id']
            ));

            if (!$exists) {
                $result = $wpdb->insert($wpdb->prefix . 'payorc_transaction', $data);
                if ($result === false) {
                    error_log('PayOrc DB Error: ' . $wpdb->last_error);
                }
            }
        } catch (Exception $e) {
            error_log('PayOrc DB Exception: ' . $e->getMessage());
        }
    }

    /**
     * Verify webhook signature
     */
    private function verify_webhook_signature($signature, $payload) {
        $expected = hash_hmac('sha256', $payload, $this->merchant_secret);
        return hash_equals($expected, $signature);
    }

    /**
     * Get transaction details by order ID
     */
    public static function get_transaction($order_id) {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}payorc_transaction 
            WHERE id_order = %d 
            ORDER BY id_payorc DESC 
            LIMIT 1",
            $order_id
        );
        
        return $wpdb->get_row($query);
    }

    /**
     * AJAX handler for getting transaction details
     */
    public function ajax_get_transaction() {
        // Check user capabilities
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(array('message' => 'Permission denied'));
            return;
        }

        // Verify nonce
        if (!check_ajax_referer('payorc-admin', 'nonce', false)) {
            wp_send_json_error(array('message' => 'Invalid nonce'));
            return;
        }

        $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
        
        if (!$order_id) {
            wp_send_json_error(array('message' => 'Order ID is required'));
            return;
        }

        $transaction = self::get_transaction($order_id);
        
        if ($transaction) {
            wp_send_json_success(array(
                'transaction' => $transaction
            ));
        } else {
            wp_send_json_error(array('message' => 'No transaction found for this order ID'));
        }
    }

    /**
     * Add admin menu for transaction lookup
     */
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('PayOrc Transactions', 'payorc-payments'),
            __('PayOrc Transactions', 'payorc-payments'),
            'manage_woocommerce',
            'payorc-transactions',
            array($this, 'render_transaction_page')
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_scripts($hook) {
        if ($hook != 'woocommerce_page_payorc-transactions') {
            return;
        }

        wp_enqueue_style('payorc-admin', plugins_url('assets/css/admin.css', PAYORC_PLUGIN_FILE));
        wp_enqueue_script('payorc-admin', plugins_url('assets/js/admin.js', PAYORC_PLUGIN_FILE), array('jquery'), PAYORC_VERSION, true);
        wp_localize_script('payorc-admin', 'payorcAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('payorc-admin')
        ));
        wp_localize_script('payorc-admin', 'payorcL10n', array(
            'enterOrderId' => __('Please enter an Order ID', 'payorc-payments')
        ));
    }

    /**
     * Render transaction lookup page
     */
    public function render_transaction_page() {
        $transactions = $this->get_payorc_transactions();
        ?>
        <div class="wrap">
            <h1><?php _e('PayOrc Transactions', 'payorc-payments'); ?></h1>
            
            <div class="card payorc-card">
                <h2 class="title"><?php _e('Transaction Lookup', 'payorc-payments'); ?></h2>
                <div class="inside">
                    <div class="transaction-lookup">
                        <div class="table-responsive">
                            <table class="wp-list-table widefat striped">
                                <thead>
                                    <tr>
                                        <th>Sno</th>
                                        <th>Payorc Order ID</th>
                                        <th>Customer Email</th>
                                        <th>ID Order</th>
                                        <th>Transaction ID</th>
                                        <th>Paid Amount</th>
                                        <th>Status</th>
                                        <th>Response</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)) : ?>
                                        <?php foreach ($transactions as $transaction) : ?>
                                            <tr>
                                                <td data-label="Sno"><?php echo esc_html($transaction['id_payorc']); ?></td>
                                                <td data-label="Payorc Order ID"><?php echo esc_html($transaction['p_order_id']); ?></td>
                                                <td data-label="Customer Email"><?php echo esc_html($transaction['customer_email']); ?></td>
                                                <td data-label="ID Order"><?php echo esc_html($transaction['id_order']); ?></td>
                                                <td data-label="Transaction ID"><?php echo esc_html($transaction['transaction_id']); ?></td>
                                                <td data-label="Paid Amount"><?php echo $transaction['paid_amount']; ?></td>
                                                <td data-label="Status"><?php echo esc_html($transaction['status']); ?></td>
                                                <td data-label="Response"><?php echo esc_html($transaction['response']); ?></td>
                                                <td data-label="Date"><?php echo esc_html($transaction['date_add']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="9" class="text-center"><?php _e('No transactions found', 'payorc-payments'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function get_payorc_transactions() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'payorc_transaction';
    
        $query = "SELECT * FROM $table_name GROUP BY p_order_id ORDER BY id_payorc DESC";
        $transactions = $wpdb->get_results($query, ARRAY_A);
    
        $all_transactions = [];
    
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $customer = get_userdata($transaction['id_customer']); 
                $all_transactions[$transaction["id_payorc"]] = array(
                    'id_payorc' => $transaction["id_payorc"],
                    'p_order_id' => $transaction["p_order_id"],
                    'customer_email' => isset($customer->user_email) ? $customer->user_email : 'N/A',
                    'id_order' => $transaction['id_order'],
                    'transaction_id' => $transaction['transaction_id'],
                    'paid_amount' => wc_price((float)$transaction['amount']),
                    'status' => $transaction['status'],
                    'response' => $transaction['response'],
                    'date_add' => $transaction['date_add'],
                );
            }
        }
    
        return $all_transactions;
    }

    public function webhook() 
    {
        if (isset($_GET['payorc-action'])) {
            $this->webhook_handler();
        } else {
            wp_redirect(wc_get_checkout_url());
            exit;
        }
    }
}