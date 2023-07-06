<?php
/*
 * Plugin Name: WooCommerce Quick Buy Now
 * Plugin URI: https://github.com/truongchauhien/woocommerce-quick-buy-now
 * Text Domain: woocommerce-quick-buy-now
 * Domain Path: /languages
 */

register_activation_hook(__FILE__, 'wqbn_activate');
register_deactivation_hook(__FILE__, 'wqbn_deactivate');

function wqbn_activate() {

}

function wqbn_deactivate() {

}

add_action( 'init', 'wqbn_load_textdomain' );
function wqbn_load_textdomain() {
    load_plugin_textdomain('woocommerce-quick-buy-now', false, dirname( plugin_basename( __FILE__ ) ) . '/languages'); 
}

add_action('woocommerce_after_add_to_cart_button', 'wqbn_add_buy_now_button');
function wqbn_add_buy_now_button() {
    global $product;
    printf('<button class="wqbn-buy-now" type="submit" name="wqbn_buy_now" value="%s">%s</button>', $product->get_ID(), esc_html(__('Buy now', 'woocommerce-quick-buy-now')));
}

add_action('wp_enqueue_scripts', 'wqbn_add_scripts');
function wqbn_add_scripts() {
    wp_enqueue_style('wqbn_quick_buy_now_css', plugin_dir_url(__FILE__) . '/public/css/quick-buy-now.css');
    wp_enqueue_script('wqbn_quick_buy_now_js', plugin_dir_url(__FILE__) . '/public/js/quick-buy-now.js', array('jquery'), false, true);
}

add_action('template_redirect', 'wqbn_handle_buy_now');
function wqbn_handle_buy_now() {
    if (!isset($_REQUEST['wqbn_buy_now'])) {
        return false;
    }

    global $product;

    $product_id = isset($_REQUEST['wqbn_buy_now']) ? $_REQUEST['wqbn_buy_now'] : 0;
    $quantity = floatval(isset($_REQUEST['quantity']) ? $_REQUEST['quantity'] : 1 );
    $variation_id = isset($_REQUEST['variation_id']) ? $_REQUEST['variation_id'] : 0;
    if ($product_id) {
        // A product with variants has 'variation_id' hidden input, is used to indicate which variant is chosen.
        // Add to cart is automatically done on the product, because the add to cart button doesn't have name and value attributes.
        if (!$variation_id) {
            WC()->cart->add_to_cart($product_id, $quantity);
        }
    }

    wp_safe_redirect(wc_get_checkout_url());
    exit;
}
