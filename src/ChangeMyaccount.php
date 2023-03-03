<?php

class ChangeMyaccount
{


    function __construct()
    {
        //////// Disable Quantities ////////

        add_filter('woocommerce_is_sold_individually', array($this, 'default_no_quantities'), 10, 2);
        //////// Register New Endpoint ////////
        add_action('init', array($this, 'bbloomer_add_licenses_keys_endpoint'));
        //////// Add new query var ////////
        add_filter('query_vars',  array($this, 'bbloomer_licenses_keys_query_vars'), 0);
        //////// Add New tab in my account page ////////

        add_filter('woocommerce_account_menu_items', array($this, 'bbloomer_add_licenses_keys_link_my_account'));
        //////// Add content to the new tab ////////

        add_action('woocommerce_account_licenses_keys_endpoint',  array($this, 'bbloomer_licenses_keys_content'));
        ////reorder my account menu/////
        add_filter('woocommerce_account_menu_items', array($this, 'bbloomer_add_link_my_account'));
		
		add_action('template_redirect', array($this,'product_post_redirect'));
		
		//link of name at cart return null
		add_filter( 'woocommerce_cart_item_permalink', '__return_null' );
		
// 		//change label of cart table
		add_filter('gettext',array($this,'translate_reply'),9999 );
		add_filter('ngettext', array($this,'translate_reply'));
		//modify remove_button of cart table
		add_filter('woocommerce_cart_item_remove_link', array($this,'remove_icon_and_add_text'), 10, 2);



    }
	
	
	//change label of cart table
	function translate_reply($translated) {
	$translated = str_ireplace('المنتج', 'الترخيص', $translated);
	$translated = str_ireplace('Product', 'License', $translated);
	$translated = str_ireplace('الضرائب', 'ضريبة القيمة المضافة', $translated);
	$translated = str_ireplace('Taxes', 'Value-added tax', $translated);
	$translated = str_ireplace('التقدم لإتمام الطلب', 'اتمام الدفع', $translated);
	$translated = str_ireplace('إجمالي سلة المشتريات', 'إجمالي مبلغ التجديد', $translated);
	$translated = str_ireplace('عرض السلة', 'عرض التراخيص المضافة', $translated);
		
	return $translated;
		
		}
//modify remove_button of cart table
function remove_icon_and_add_text($string, $cart_item_key) {
    $string = str_replace('class="remove"', 'class="remove" style="width: 29px;font-size: 12px;padding-top: 5px;"', $string);
    return str_replace('&times;', 'حذف', $string);
}
	
	
	
    //////// Display Fields using WooCommerce Action Hook ////////

    function woocommerce_general_product_data_custom_field()
    {
        global $woocommerce, $post;
        $users = get_users();
        $id_name_users = [];
        foreach ($users as $user) {
            $id_name_users[$user->ID] = $user->display_name;
        }
        echo '<div class="options_group">';
        woocommerce_form_field('contract', array(
            'type'              => 'date',
            'label'             => __('contract', 'woocommerce'),
            'required'          => false,
            'description'       => __('Add contract date', 'woocommerce'),


        ), get_post_meta(get_the_ID(), 'contract', TRUE));
        echo '</div>';


        // Date field
//         echo '<div class="options_group">';
//         woocommerce_form_field('expiration', array(
//             'type'              => 'date',
//             'label'             => __('expiration', 'woocommerce'),
//             'required'          => false,
//             'description'       => __('Add expiration date', 'woocommerce')

//         ), get_post_meta(get_the_ID(), 'expiration', TRUE));
//         echo '</div>';

        echo '<div class="options_group"> ';
        woocommerce_form_field('users', array(
            'type'          => 'select',
            'label'             => __('users', 'woocommerce'),
            'description'       => __('Select customer', 'woocommerce'),
            'options'       =>  $id_name_users,
        ), get_post_meta(get_the_ID(), 'users', TRUE));

        echo '</div>';
    }

    // Save Fields using WooCommerce Action Hook

    function woocommerce_process_product_meta_fields_save($post_id)
    {
        if (isset($_POST['expiration'])) {
			
            update_post_meta($post_id, 'expiration', date('d/m/Y',strtotime($_POST['expiration'])));
			
        }
      
        if (isset($_POST['users'])) {
            update_post_meta($post_id, 'users', $_POST['users']);
        }
    }


    //////// Disable Quantities ////////
    function default_no_quantities($individually, $product)
    {
        $individually = true;
        return $individually;
    }

    //////// Register New Endpoint ////////
    function bbloomer_add_licenses_keys_endpoint()
    {
        add_rewrite_endpoint('licenses_keys', EP_ROOT | EP_PAGES);
        flush_rewrite_rules();
    }

    //////// Add new query var ////////
    function bbloomer_licenses_keys_query_vars($vars)
    {
        $vars["licenses_keys"] = 'licenses_keys';
        return $vars;
    }

    //////// Add New tab in my account page ////////

    function bbloomer_add_licenses_keys_link_my_account($items)
    {
		
        $items['licenses_keys'] = 'تجديد التراخيص'  ;
		unset ($items['dashboard']);
		unset ($items['edit-address']);
        return $items;
    }
    //////// Add content to the new tab ////////

    function bbloomer_licenses_keys_content()
    {

        echo '';
?><table>
            <thead>
                <tr>
                    <th><?php _e( 'License', 'Bnn-license-Manage' ); ?></th>
                    <th><?php _e( 'Price', 'Bnn-license-Manage' ); ?></th>
                    <th><?php _e( 'Renewal date', 'Bnn-license-Manage' ); ?></th>
                    <th><?php _e( 'renewal status', 'Bnn-license-Manage' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
					$args = [
    							'status'  => 'publish',
    							'orderby' => 'name',
   								'order'   => 'ASC',
   								'limit'   => -1,
							];
					$products = wc_get_products($args);
//                 $products = wc_get_products(array('status' => array('private', 'publish')));
                foreach ($products as $product) {
                if (get_post_meta($product->get_id(), 'customer', TRUE) == get_current_user_id()) {
                   
					
				?>
                       
				<tr>
                            <td><?= $product->get_title() ?></td>
                            <td><?= $product->get_price(); ?></td>
                            <td><?=date('d/m/Y',strtotime(get_post_meta($product->get_id(), 'expiration', true)) ) ?></td>
                            <td>
	<?php if( strtotime("now") > strtotime(get_post_meta($product->get_id(), 'expiration', TRUE)."-1 months")){  ?>
					<div style="margin-right:40%">
								<a href="?add-to-cart=<?= $product->get_id() ?>">
					<img src="https://ezpos-sa.com/soft/wp-content/plugins/Bnn-license-Manage/src/shopping.png" ></a> 		 					</div>
					<?php } else{  ?> 
					<span style="color:green;"><?php _e( 'renovated', 'Bnn-license-Manage' ); ?></span> 
					<?php } ?>
                            </td>
                        </tr>

        <?php
	}
                }


                echo '</tbody></table>';
            }
            ////reorder my account menu/////
            function bbloomer_add_link_my_account($items)
            {
                $save_for_later = array('licenses_keys' => __('licenses_keys', 'woocommerce')); // SAVE TAB
                unset($items['orders']); // REMOVE TAB
                $items = array_merge($save_for_later, array_slice($items, 0, 2),  array_slice($items, 2)); // PLACE TAB AFTER POSITION 2
                return $items;
            }
        
	
	// function student_post_redirect
	function product_post_redirect() {
    global $post;
    if ('product' === $post->post_type
        && ( ! is_user_logged_in() ||   (get_current_user_id() != get_post_meta($post->ID, 'customer', true)))) {
       			 
				wp_redirect(home_url('/error/'));
       			 exit();
    }
} 


	}