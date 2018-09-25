<?php
/*
 * Plugin Name: WooCommerce Put it on my tab
 * Plugin URI: https://github.com/LeanSibal/woocommerce-put-it-in-my-tab
 * Description: Allows Customers to accumulate orders and pay once a month.
 * Author: Lean Sibal
 * Author URI: https://github.com/LeanSibal/
 * Version: 0.0.1
 * Text Domain: woocommerce-put-it-on-my-tab
 * Domain Path: /languages
 */

if( ! defined( 'ABSPATH' ) ) exit;

add_action('plugins_loaded', 'woocommerce_put_it_on_my_tab_init');

function woocommerce_put_it_on_my_tab_init() {
	if( ! class_exists( 'WooCommerce' ) ) add_action( 'admin_notices', 'woocommerce_put_it_on_my_tab_missing_wc_notice' );
	if( ! class_exists( 'WC_Put_It_On_My_Tab' ) ) return;
	WC_Put_It_On_My_Tab::get_instance();
}

function woocommerce_put_it_on_my_tab_missing_wc_notice() {
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Put it on my tab add-on requires WooCommerce to be installed and active. You can download %s here.', 'woocommerce-put-it-on-my-tab' ), '<a href="https://www.woocommerce.com/" target="_blank">WooCommerce</a>') . '</strong></p></div>';
}


class WC_Put_It_On_My_Tab {

	private static $instance;

	public static function get_instance() {
		if( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->setup_filters();
		$this->setup_actions();
		require_once( dirname( __FILE__ ) . '/includes/class-wc-put-it-on-my-tab.php' );
		add_filter( 'woocommerce_payment_gateways', [ $this, 'add_gateways' ] );
	}

	protected function setup_filters() {
		add_filter( 'woocommerce_account_menu_items', [ $this, 'add_statements_to_my_account_menu_item' ] );
	}

	protected function setup_actions() {
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'woocommerce_account_statements_endpoint', [ $this, 'customer_statements' ] );
	}

	public function init() {
		add_rewrite_endpoint( 'statements', EP_PAGES );
	}

	public function add_gateways( $methods ) {
		$methods[] = 'WC_Gateway_Put_It_On_My_Tab';
		return $methods;
	}

	public function add_statements_to_my_account_menu_item( $items ) {
		$index = 1;
		return array_slice( $items, 0, $index, true ) +
			[ 'statements' => 'Statements' ] +
			array_slice( $items, $index, null, true );
	}

	public function customer_statements() {
		echo "hello";
	}
}

