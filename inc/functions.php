<?php
//enqueue scripts and styles
function wpsdb_enqueue_scripts() {
	if (is_admin()) {
	
    wp_register_script( 
      'wpsd_datatables',
	  WPSDB_PLUGIN_URL . '/assets/dataTables/jquery.dataTables.min.js',
      array('jquery'),
      '1.0',
      TRUE
    );
    wp_enqueue_script('wpsd_datatables');

    wp_register_style( 
      'wpsd_datatables_styles', 
      WPSDB_PLUGIN_URL . '/assets/dataTables/jquery.dataTables.min.css',
      array(), 
      '1.0',
      'all' 
    );
    wp_enqueue_style( 'wpsd_datatables_styles');

    wp_register_script( 'wpsdb_loadingbar_script', WPSDB_PLUGIN_URL . '/assets/loading-bar/loading-bar.min.js' , '', true, '1.0' );
    wp_enqueue_script('wpsdb_loadingbar_script');

    wp_register_script( 'wpsdb_script', WPSDB_PLUGIN_URL . '/assets/js/script.js' , '', true, '1.0' );
    wp_enqueue_script('wpsdb_script');

    wp_enqueue_style( 'wpsdb_loadingbar_styles', WPSDB_PLUGIN_URL . '/assets/loading-bar/loading-bar.css',true,'1.0','all');  
    wp_enqueue_style( 'wpsdb_styles', WPSDB_PLUGIN_URL . '/assets/css/style.css',true,'1.0','all');
  }
}
add_action('init', 'wpsdb_enqueue_scripts');

//db scan and sanitize function
add_action( 'wp_ajax_nopriv_wpsdb_scan_now', 'wpsdb_scan_now' );
add_action( 'wp_ajax_wpsdb_scan_now', 'wpsdb_scan_now' );

function wpsdb_scan_now() {
	
	global $wpdb;
    $post_meta = $wpdb->postmeta;
    $result = $wpdb->get_results("SELECT meta_id,post_id FROM  $post_meta WHERE 1");

	$entries_removed = 0;
	if(!empty($result)){
		foreach($result as $meta){
		
			$post_id = $meta->post_id;
			//check if post exists
			$post = get_post($post_id);
			if(empty($post)){
				//remove metadata if post is not exists
				$wpdb->delete( $wpdb->postmeta, [ 'meta_id' => $meta->meta_id ], [ '%d' ] );
				$entries_removed++;
			}
		}
	}

	$args = array(
		'post_type' => 'wpsdb_history',
		'post_status' => 'private',
		'post_content' => '',
		'post_title' => date('Y-m-d h:i:s')
	);
	$history_id = wp_insert_post( $args );
	update_post_meta($history_id,'entries_removed',$entries_removed);

	$data["entries_removed"] = 	$entries_removed;
	$data["date"] = 	date('Y-m-d');
	$data["history_id"] = 	$history_id;

	wp_send_json($data,200);
	
	exit();	
}


//remove scan history function
add_action( 'wp_ajax_nopriv_wpsdb_remove_scan_history', 'wpsdb_remove_scan_history' );
add_action( 'wp_ajax_wpsdb_remove_scan_history', 'wpsdb_remove_scan_history' );

function wpsdb_remove_scan_history() {
	
	global $wpdb;
	$post_meta = $wpdb->postmeta;
	
	$args = array(
		'post_type' => 'wpsdb_history',
		'posts_per_page' => -1,
		'order'=>'DESC',
		'post_status' => 'private'
	); 
	$history = get_posts($args);
    

	$counter = 0;
	foreach( $history as $key=>$h ) {
				if ($key == 0) continue;  //skip recent last  history record
				wp_delete_post( $h->ID, true);
				$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $h->ID ], [ '%d' ] );
	}
  

	wp_send_json(["success"=>true],200);
	exit();	
}
//save scan time function
add_action( 'wp_ajax_nopriv_wpsdb_save_scan_time', 'wpsdb_save_scan_time' );
add_action( 'wp_ajax_wpsdb_save_scan_time', 'wpsdb_save_scan_time' );

function wpsdb_save_scan_time() {
	$history_id = sanitize_text_field($_POST['post_id']);

	if(!is_numeric($history_id)){
		return;
		exit();
	}

	update_post_meta($history_id,'scan_time',sanitize_text_field($_POST['scan_time']));
	wp_send_json([],200);
	exit();	
}
