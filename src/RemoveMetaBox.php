<?php


class RemoveMetaBox
{
    function __construct()
    {
        //////// Remove fields ////////
        add_action('admin_head', array($this, 'my_custom_admin_styles'));

		//////// Remove products Metaboxes ////////
        add_action('add_meta_boxes_product', array($this, 'bbloomer_remove_metaboxes_edit_product'), 9999);

		//////// remove Editor metabox ////////
        add_action('init', function () {
            remove_post_type_support('product', 'editor');
        }, 99);

		//////// Remove Order metabox ////////
        add_action('add_meta_boxes', array($this, 'remove_downloads_meta_box'), 999);

        //////// Remove Product Details Tabs ////////
        add_filter('woocommerce_product_data_tabs', array($this, 'remove_tab'), 10, 1);

        //////// Unregister taxonomies ////////
        add_action('init', array($this, 'deregister_post_taxonomies'));

        //////// Remove top nav table ////////
        add_filter('woocommerce_products_admin_list_table_filters', array($this, 	'remove_products_admin_list_table_filters'), 10, 1);
    
        //////// Remove Quick edit ////////
        add_filter('post_row_actions', array($this, 'remove_quick_edit'), 10, 2);
		

		
    }

    //////// Remove field CSS ////////
    function my_custom_admin_styles()
    {
        // just add the css selectors below to hide each field as required

        echo '<style type="text/css">
	#woocommerce-product-data .postbox-header			{ display: none !important; }
 	.woocommerce-layout__header							{ display: none !important; }
	.form-field._sale_price_field 						{ display: none; }
	.form-field._tax_status_field 						{ display: none; }
	.form-field._tax_class_field 						{ display: none; }
	.product_data_tabs.wc-tabs 							{ display: none !important; }
	.woocommerce_options_panel label					{ margin:0 !important; }
	.woocommerce_options_panel p.form-field 			{ padding: 10px !important; }
	.woocommerce_options_panel input[type=text].short, 	
	#woocommerce-product-data .woocommerce_options_panel{ width: 100% !important; }
	
	</style>';	 
		 if (isset($_GET['post_type']) && $_GET['post_type'] == 'product') 
		{
	  echo '<style type="text/css">
	 #favorite-actions,
	 .add-new-h2,
	 .page-title-action:nth-child(3),
	 .page-title-action:nth-child(4),
	 #titlediv #title-prompt-text,
	 #post-query-submit 					{ display:none !important; }

	</style>';
		}
    }

    //////// Remove products Metaboxes ////////

    function bbloomer_remove_metaboxes_edit_product()
    {
        remove_meta_box('postexcerpt', 'product', 'normal');                  	//  remove short description
        remove_meta_box('commentsdiv', 'product', 'normal');                  	//	remove reviews
        remove_meta_box('postdivrich', 'product', 'normal');                  	//	remove product description
        remove_meta_box('slugdiv', 'product', 'normal');                        //	Slug Metabox
        remove_meta_box('codevz_page_meta', 'product', 'normal');             	//	Codevz Page Settings Metabox
        remove_meta_box('postimagediv', 'product', 'side');                   	//  remove product image
        remove_meta_box('woocommerce-product-images', 'product', 'side');       //	remove product gallary
        remove_meta_box('tagsdiv-product_tag', 'product', 'side');              //  remove product tags
        remove_meta_box('product_catdiv', 'product', 'side');                	//	remove product categories
        remove_meta_box('slider_revolution_metabox', 'product', 'side');        //	slider_revolution Metabox	
    }

    //////// Remove Orders metabox ////////
    function remove_downloads_meta_box()
    {
        remove_meta_box('woocommerce-order-downloads', 'shop_order', 'normal');
        remove_meta_box('codevz_page_meta', 'shop_order', 'normal');
        remove_meta_box('slider_revolution_metabox', 'shop_order', 'side');
    }

	//////// Remove Product Details Tabs ////////
    function remove_tab($tabs)
    {
        //unset($tabs['general']); 		// it is to remove general tab
        unset($tabs['inventory']);     // it is to remove inventory tab
        unset($tabs['advanced']);         // it is to remove advanced tab
        unset($tabs['linked_product']); // it is to remove linked_product tab
        unset($tabs['attribute']);         // it is to remove attribute tab
        unset($tabs['variations']);     // it is to remove variations tab
        unset($tabs['variations']);     // it is to remove variations tab
        unset($tabs['shipping']); // it is to remove shipping tab
        return ($tabs);
    }

    //////// Unregister taxonomies ////////
    function deregister_post_taxonomies()
    {
        unregister_taxonomy('product_cat');
        unregister_taxonomy('product_tag');
    }

    //////// Remove top nav table ////////
    function remove_products_admin_list_table_filters($filters)
    {
        // Remove "Product type" dropdown filter
        if (isset($filters['product_type']))
            unset($filters['product_type']);

        // Remove "Product stock status" dropdown filter
        if (isset($filters['stock_status']))
            unset($filters['stock_status']);

        // Remove "Product stock status" dropdown filter
        if (isset($filters['product_category']))
            unset($filters['product_category']);

        return $filters;
    }
    //////// Remove Quick edit ////////
    function remove_quick_edit($actions, $product)
    {
        //unset($actions['edit']);
//         unset($actions['trash']);
        unset($actions['view']);
        unset($actions['inline hide-if-no-js']);
        return $actions;
    }

}





