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

/**
 * Log message to file in logs directory, file named by current date and hour
 */
function payorc_log_to_file($message) {
    $log_dir = __DIR__ . '/logs';
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $filename = $log_dir . '/payorc-' . date('Y-m-d-H') . '.log';
    $datetime = date('Y-m-d H:i:s');
    $log_entry = "[$datetime] $message\n";
    file_put_contents($filename, $log_entry, FILE_APPEND);
}

payorc_log_to_file('PayOrc plugin loaded. Accessed from ' . (is_admin() ? 'admin' : 'frontend'));

// Define plugin constants
define('PAYORC_PLUGIN_FILE', __FILE__);
define('PAYORC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PAYORC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PAYORC_VERSION', '1.0.0');

/**
 * Create necessary directories and files
 */
function payorc_create_directories() {
    payorc_log_to_file('PayOrc plugin activation: Creating directories. Accessed from ' . (is_admin() ? 'admin' : 'frontend'));
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
    payorc_log_to_file('PayOrc plugin activation: Creating tables. Accessed from ' . (is_admin() ? 'admin' : 'frontend'));
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
    try {
        payorc_log_to_file('PayOrc plugin init. Accessed from ' . (is_admin() ? 'admin' : 'frontend'));
        if (!class_exists('WC_Payment_Gateway')) {
            payorc_log_to_file('WooCommerce not active. Exiting init.');
            return;
        }

        // Load plugin text domain
        load_plugin_textdomain('payorc-payments', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Include main gateway class
        require_once PAYORC_PLUGIN_DIR . 'includes/class-wc-payorc-payment-gateway.php';
        
        // Add the gateway to WooCommerce
        add_filter('woocommerce_payment_gateways', 'payorc_add_gateway');
    } catch (Exception $e) {
        payorc_log_to_file('Last PHP error: ' . print_r(error_get_last(), true));
        payorc_log_to_file('PayOrc plugin init error: ' . $e->getMessage());
        return;
    }
    
}
add_action('plugins_loaded', 'payorc_init');

/**
 * Add PayOrc Gateway to WooCommerce
 */
function payorc_add_gateway($methods) {
    payorc_log_to_file('PayOrc gateway added to WooCommerce. Accessed from ' . (is_admin() ? 'admin' : 'frontend'));
    $methods[] = 'WC_PayOrc_Payment_Gateway';
    return $methods;
}

/**
 * Add plugin action links
 */
function payorc_plugin_action_links($links) {
    payorc_log_to_file('PayOrc plugin action links filter. Accessed from ' . (is_admin() ? 'admin' : 'frontend'));
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
        payorc_log_to_file('WooCommerce not active: admin notice shown.');
        ?>
        <div class="notice notice-error">
            <p><?php _e('PayOrc requires WooCommerce to be installed and active.', 'payorc-payments'); ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'payorc_admin_notice_wc_not_active');