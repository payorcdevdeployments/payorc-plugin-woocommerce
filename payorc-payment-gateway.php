<?php
/**
 * Plugin Name: PayOrc for WooCommerce
 * Plugin URI: https://payorc.com
 * Description: Accept payments through PayOrc
 * Version: 1.0.0
 * Author: PayOrc
 * Author URI: https://payorc.com
 * Text Domain: payorc-payments
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 4.0
 * WC tested up to: 9.7.1
 *
 * @package PayOrc
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PAYORC_PLUGIN_FILE', __FILE__);
define('PAYORC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PAYORC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PAYORC_VERSION', '1.0.0');

/**
 * Create necessary directories and files
 */
function payorc_create_directories() {
    $directories = array(
        PAYORC_PLUGIN_DIR . 'assets',
        PAYORC_PLUGIN_DIR . 'assets/css',
        PAYORC_PLUGIN_DIR . 'assets/js',
        PAYORC_PLUGIN_DIR . 'assets/images'
    );

    foreach ($directories as $directory) {
        if (!file_exists($directory)) {
            wp_mkdir_p($directory);
        }
    }
}
register_activation_hook(__FILE__, 'payorc_create_directories');

/**
 * Create PayOrc tables on plugin activation
 */
function payorc_create_tables() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'payorc_transaction';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id_payorc int(11) NOT NULL AUTO_INCREMENT,
        type enum('payment','refund') NOT NULL DEFAULT 'payment',
        source_type varchar(16) NOT NULL DEFAULT 'card',
        p_request_id varchar(100) DEFAULT NULL,
        m_payment_token varchar(120) DEFAULT NULL,
        p_order_id varchar(100) DEFAULT NULL,
        id_customer int(10) DEFAULT NULL,
        id_cart int(10) DEFAULT NULL,
        id_order int(10) DEFAULT NULL,
        transaction_id varchar(32) DEFAULT NULL,
        amount float(20,6) DEFAULT NULL,
        status varchar(32) NOT NULL DEFAULT 'pending',
        response text DEFAULT NULL,
        currency varchar(3) DEFAULT NULL,
        cc_schema varchar(16) DEFAULT NULL,
        cc_type varchar(16) DEFAULT NULL,
        cc_mask varchar(30) DEFAULT NULL,
        mode enum('live','test') DEFAULT NULL,
        date_add datetime DEFAULT NULL,
        PRIMARY KEY (id_payorc)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'payorc_create_tables');

/**
 * Initialize the plugin
 */
function payorc_init() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    // Load plugin text domain
    load_plugin_textdomain('payorc-payments', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Include main gateway class
    require_once PAYORC_PLUGIN_DIR . 'includes/class-wc-payorc-payment-gateway.php';
    
    // Add the gateway to WooCommerce
    add_filter('woocommerce_payment_gateways', 'payorc_add_gateway');
}
add_action('plugins_loaded', 'payorc_init');
add_action('wp_ajax_payorc_validate_payment', 'payorc_handle_validate_payment');
add_action('wp_ajax_nopriv_payorc_validate_payment', 'payorc_handle_validate_payment');

function payorc_handle_validate_payment() {
    // Make sure class exists
    if (class_exists('WC_PayOrc_Payment_Gateway')) {
        $gateway = new WC_PayOrc_Payment_Gateway();
        $gateway->validate_payment(); // call your class method
    } else {
        wp_send_json_error(['message' => 'Gateway class not available']);
    }
}

/**
 * Add PayOrc Gateway to WooCommerce
 */
function payorc_add_gateway($methods) {
    $methods[] = 'WC_PayOrc_Payment_Gateway';
    return $methods;
}

/**
 * Add plugin action links
 */
function payorc_plugin_action_links($links) {
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=payorc') . '">' . __('Settings', 'payorc-payments') . '</a>'
    );
    return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'payorc_plugin_action_links');

/**
 * Display admin notice if WooCommerce is not active
 */
function payorc_admin_notice_wc_not_active() {
    if (!class_exists('WC_Payment_Gateway')) {
        ?>
        <div class="notice notice-error">
            <p><?php _e('PayOrc requires WooCommerce to be installed and active.', 'payorc-payments'); ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'payorc_admin_notice_wc_not_active');