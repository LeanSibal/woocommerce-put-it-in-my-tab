<?php

if( ! defined( 'ABSPATH' ) ) exit;

class WC_Gateway_Put_It_On_My_Tab extends WC_Payment_Gateway {

	public function __construct() {
		$this->setup_filters();
		$this->setup_properties();
	}

	protected function setup_properties() {
		$this->id = 'putitonmytab';
		$this->title = "Put it on my tab";
		$this->description = apply_filters('put_it_on_my_tab_customer_description', '');
		$this->method_title = "Put it on my tab";
		$this->method_description = "Take orders and charge monthly.";
	}

	protected function setup_filters() {
		add_filter('put_it_on_my_tab_customer_description', [ $this, 'customer_description' ] );
	}

	public function customer_description() {
		return "hello";
	}

	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		if( $order->get_total() > 0 ) {
			$order->update_status( 'processing', 'Payment to be made on the next billing cycle.' );
		} else {
			$order->payment_complete();
		}
		wc_reduce_stock_levels( $order_id );
		WC()->cart->empty_cart();
		return [
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		];
	}

}
