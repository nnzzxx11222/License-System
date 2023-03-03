<?php

class ChangeMyadmin
{
    function __construct()
    {
         //////// Display Fields using WooCommerce Action Hook ////////
//          add_action('woocommerce_product_options_general_product_data', array($this, 'woocommerce_general_product_data_custom_field'));
         // Save Fields using WooCommerce Action Hook
         add_action('woocommerce_process_product_meta', array($this, 'woocommerce_process_product_meta_fields_save'));
 
        ////change_product_object_label///
        add_action('init', array($this, 'change_product_object_label'), 999);
        /////edit product table columns/////
        add_filter('manage_edit-product_columns', array($this, 'change_columns_filter'), 10, 1);
        ////content of new product columns //// 
        add_action( 'manage_product_posts_custom_column', array($this,'wpso23858236_product_column_offercode'), 10, 2 );
		/////////permalink hidden//////////////////
		add_filter('get_sample_permalink_html', array($this,'my_hide_permalinks'));

    }
    ////change_product_object_label///
    function  change_product_object_label()
    {
        global $wp_post_types;
        $labels = &$wp_post_types['product']->labels;
        $labels->name = 'License Key';
        $labels->singular_name = 'License Key';
        $labels->add_new = 'Add License Key';
        $labels->add_new_item = 'Add License Key';
        $labels->edit_item = 'Edit License Key';
        $labels->new_item = 'License Key';
        $labels->all_items = 'All License Keys';
        $labels->view_item = 'View License Key';
        $labels->search_items = 'Search License Keys';
        $labels->not_found = 'No License Key found';
        $labels->not_found_in_trash = 'No License Key found in Trash';
        $labels->menu_name = 'License Keys';
        $labels->all_items = 'All License Keys';
        $labels->view_items = 'View License Keys';
        $labels->archives = 'License Key archives';
        $labels->insert_into_item = 'Insert into License Key';
        $labels->name_admin_bar = 'License Key';
        $labels->item_published = 'License Key published';
        $labels->item_updated = 'License Key updated';
    }
            /////edit product table columns/////

    function change_columns_filter($columns)
    {
        unset($columns['product_tag']);
        unset($columns['sku']);
        unset($columns['featured']);
        unset($columns['product_type']);
        unset($columns['is_in_stock']);
        unset($columns['product_cat']);
        unset($columns['thumb']);
        unset($columns['date']);
        $columns['customer'] = __('customer');
        $columns['expiration'] = __('expiration');

        return $columns;
    }

////content of new product columns //// 
    function wpso23858236_product_column_offercode( $column, $postid ) {
        if ( $column == 'expiration' ) {
          
            echo date('d-m-Y',strtotime(get_post_meta($postid, 'expiration', true )));
        }
    
      if ( $column == 'customer' ) {
    
           $id=get_post_meta($postid, 'customer', true );
      	  echo get_userdata($id)->display_name ;
      }
     
   
    
    
    }
// 	function woocommerce_general_product_data_custom_field()
// {
// 	$users = get_users();
// 	$id_name_users = [];
// 	foreach ($users as $user) {
// 	  $id_name_users[$user->ID] = $user->display_name;
// 	}
//   global $woocommerce, $post;
  // Date field
//   echo '<div class="options_group">';
//   woocommerce_form_field('expiration', array(
//     'type'              => 'date',
//     'label'             => __('Expiration Date', 'woocommerce'),
//     'required'          => false,

//   ), get_post_meta(get_the_ID(), 'expiration', TRUE));
//   echo '</div>';
//   echo '<div class="options_group"> ';
//   woocommerce_form_field('users', array(
//     'type'          => 'select',
//     'label'             => __('Customer', 'woocommerce'),
//     'options'       =>  $id_name_users,
//   ), get_post_meta(get_the_ID(), 'users', TRUE));
//   echo '</div>';
// }
function woocommerce_process_product_meta_fields_save($post_id)
{
  if (isset($_POST['expiration'])) {
    update_post_meta($post_id, 'expiration', $_POST['expiration']);
  }
 
  if (isset($_POST['customer'])) {
    update_post_meta($post_id, 'customer', $_POST['customer']);
  }
}
		/////////permalink hidden//////////////////

    function my_hide_permalinks($in){
        global $post;
        if($post->post_type == 'product'){
            $out = preg_replace('~<div id="edit-slug-box".*</div>~Ui', '', $in);
        return $out;}
    }
}
