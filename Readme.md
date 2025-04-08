# PayOrc Payment Gateway for WooCommerce

## Description

PayOrc Payment Gateway for WooCommerce allows you to accept payments through PayOrc's secure payment processing system. It integrates directly with your WooCommerce checkout process, providing a seamless payment experience for your customers.

## Features

- Easy integration with WooCommerce checkout
- Support for both test and live modes
- Flexible checkout options (iframe or hosted checkout)
- Automatic order status updates
- Detailed transaction logging

## Requirements

- WordPress 5.0 or higher
- WooCommerce 3.0 or higher
- PHP 7.2 or higher
- PayOrc merchant account

## Installation

1. Download the plugin zip file
2. Go to WordPress admin panel > Plugins > Add New
3. Click "Upload Plugin" and select the downloaded zip file
4. Click "Install Now" and then "Activate"
5. Go to WooCommerce > Settings > Payments
6. Find "PayOrc Payment Gateway" and click "Manage"
7. Configure your PayOrc merchant credentials and settings

## Configuration

1. Navigate to WooCommerce > Settings > Payments > PayOrc Payment Gateway
2. Enable the payment gateway
3. Configure the following settings:

### Basic Settings
- **Enable/Disable**: Turn the payment gateway on/off
- **Title**: Payment method title displayed to customers
- **Description**: Payment method description displayed to customers

### Credentials
- **Test Mode**: Enable/disable test mode
- **Test Merchant Key**: Your PayOrc test merchant key
- **Test Merchant Secret**: Your PayOrc test merchant secret
- **Live Merchant Key**: Your PayOrc live merchant key
- **Live Merchant Secret**: Your PayOrc live merchant secret

### Payment Settings
- **Checkout Mode**: Choose between iframe or hosted checkout
- **Action Type**: Select SALE or AUTH
- **Capture Method**: Choose AUTOMATIC or MANUAL

## Payment Flow

1. Add products to cart
2. During checkout, select PayOrc as payment method
3. Based on checkout mode:
   - **Hosted**: Redirected to PayOrc's payment page
   - **iFrame**: Payment form appears in a modal on your site
4. Completes payment
5. Order status is automatically updated
6. Redirected back to your site

## Post Transaction

1.  Navigate to  Wordpress Admin > WooCommerce and find the  `PayOrc Transactions`  module.
    
2. Click the tab and the latest transaction will be available in the tabular form


## Support

For issues or questions, please contact PayOrc support or visit the official documentation.

  

---

  

### Security Notice

- Keep your **Merchant Secret** confidential.
