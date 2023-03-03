<?php

class PreventDuplicateLicense {
	
	function disallow_posts_with_same_title($messages) {
    global $post;
    global $wpdb ;
    $title = $post->post_title;
    $post_id = $post->ID ;
	$post_title =$post->post_title;
	if(get_post_type($post)=='product'){
    $wtitlequery = "SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'product' AND post_title = '{$title}' AND ID != {$post_id} " ;
 
    $wresults = $wpdb->get_results( $wtitlequery) ;
 
    if ( $wresults ) {
        $error_message = 'This title is already used. Please choose another';
        add_settings_error('post_has_links', '', $error_message, 'error');
        settings_errors( 'post_has_links' );
		$post->post_title= '';
        $post->post_status = 'trash';
        wp_update_post($post);
        return;
    }
    return $messages;
}
}
	  function __construct(){
		add_action('post_updated_messages', array($this,'disallow_posts_with_same_title'));

	  }

// 	function __construct(){
// add_filter('cred_form_validate',array($this,'func_validate_title'),10,2);
// 	  }
	
// function func_validate_title($error_fields, $form_data){
 
//     //field data are field values and errors
//     list($fields,$errors)=$error_fields;
//     //uncomment this if you want to print the field values
//     //print_r($fields);
//     //validate if specific form
 
//     if ($form_data['id']==33531){
 
//         $title = $_POST['post_title'];
 
//         $args = array("post_type" => "product", "name" => $title);
 
//         $query = get_posts( $args );
 
//         if (count($query) > 0){
//         //set error message for my_field
//             $errors['post_title']='Wrong Value';
//         }
//     }
 
//     //return result
//     return array($fields,$errors);
// 			}


	
}