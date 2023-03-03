<?php
require plugin_dir_path( __FILE__ ). '/Connection.php';

class AfterPaymentProcess{

	function __construct()
	{
	  add_action( 'woocommerce_order_status_completed',array ($this,'complete_payment'));
	}
	function complete_payment( $order_id ) {
		$dp=new Connection;
	  $order = wc_get_order( $order_id );
		  $items = $order->get_items();
		  foreach ( $items as $item ) {
			$product_id = $item['product_id'];
			  $product = wc_get_product($product_id);
			  $name =$product->get_name();
			  $exdate=get_post_meta( $product_id,'expiration',true );
			update_post_meta($product_id, 'expiration',date('d-m-Y',(strtotime($exdate."+1 years"))) );

			  $y=date('d/m/Y',strtotime(get_post_meta( $product_id,'expiration',true )));

				//$que= "UPDATE Activiation SET Expiry = ?  WHERE ID = ?";
				$que= "UPDATE Activiation SET Expiry = ? , Activate = 1  WHERE ID = ?";
				$dp->runQuery($que,$y,$name);	
				}
		}
}