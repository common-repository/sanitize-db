<?php
/**
 * Add admin menu and submenu register.
 */
add_action('admin_menu', 'wpsdb_admin_menu');

if (!function_exists("wpsdb_admin_menu")) {
		
	function wpsdb_admin_menu() { 
		add_menu_page( 
			'Wp Sanitize Database', 
			'WP Sanitize DB', 
			'administrator', 
			'wpsdb-dashboard', 
			'wpsdb_dashboard_callback', 
			'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>') 
		);
		add_submenu_page( 
			'wpsdb-dashboard', 
			'WPSDB Scan History', 
			'Sanitize History', 
			'administrator', 
			'wpsdb-sanitize-history', 
			'wpsdb_sanitize_history_callback'
		
		);
	}
}
//common wpsdb screen header
function  wpsdb_get_header(){
	$html = '<div id="wpsdb-header">
			<img style="width:200px;" class="wpsdb-header-logo" src="'.WPSDB_PLUGIN_URL.'/assets/img/logo.png" alt="WP Sanitize Database">
		</div>';
	echo $html;
}

//main wpsdb dashboard screen
function wpsdb_dashboard_callback(){
	
	$args = array(
		'post_type' => 'wpsdb_history',
		'posts_per_page' => 1,
		'order'=>'DESC',
		'post_status' => 'private'
	); 
	$last_sanitize_history = get_posts($args);

	if(!empty($last_sanitize_history [0])){
		$history = $last_sanitize_history [0];  //pick latest record
	}
	

	
	?>
	<div class="wrap" id="wpsdb-wrap">
		<?php wpsdb_get_header(); ?>
		<hr>
		<div id="wpsdb-content">
				<h2>Last Scan Statistics</h2>
				<p>Hey! Checkout your last scan statistics...It helps you to measure your website performance.</p>
				<table>
				<?php if(!empty($last_sanitize_history[0])){ ?>
					<tr>
						<td><p>Your Last Scan</p></td>
						<td><p><?php echo date("F jS, Y h:i:s",strtotime($history->post_title)); ?></td>
					</tr>

					<tr>
						<td><p>Entries Removed</p></td>
						<td><p><?php echo get_post_meta($history->ID,'entries_removed',true); ?></p></td>
					</tr>

					<tr>
						<td><p>Total Scan Time</p></td>
						<td><p><?php echo (get_post_meta($history->ID,'scan_time',true)); ?> Seconds</p></td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td colspan="3"><p>Seems that you haven't tried yet...Give it a try and your database will be sanitized.</p></td>
					</tr>
				<?php } ?>	
				</table>	

				<div id="sacn_now">
					<button onClick="scanNow(this)"  class="btn">
						<span>Run Scan Now</span>
					</button>
				</div>
		</div>

		<div id="circle_progress" style="display:none">
			<div id="ldBar" class="ldBar label-center" style="width: 100%;height: 210px;"></div>
		</div>	
	</div>
<?php }

//wpsdb sanitize history screen - table records
function wpsdb_sanitize_history_callback(){
	$args = array(
		'post_type' => 'wpsdb_history',
		'posts_per_page' => -1,
		'post_status' => 'private'
	); 
	$sanitize_history = get_posts($args);
	?>
	
	<div class="wrap" id="wpsdb-wrap">
		<?php wpsdb_get_header(); ?>
		<hr>
		<div id="wpsdb-content-history">
			<h2>Scan History</h2>
			<table class="datatable_style dataTable" data-ordering="false" data-rows="10">	
					<thead>
						<tr>
							<td>#</td>
							<td><strong>Scan Date</strong></td>
							<td><strong>Entries Removed</strong></td>
							<td><strong>Scan Time</strong></td>
						</tr>
					</thead>
					<tbody>
					<?php						
					if(!empty($sanitize_history)) {
						$i=0;
						foreach($sanitize_history as $history){ 
						$i++;	
						?>
								<tr>
									<td><?php echo $i ?></td>
									<td><?php echo date("F jS, Y h:i:s",strtotime($history->post_title)); ?></td>
									<td><p><?php echo get_post_meta($history->ID,'entries_removed',true); ?></p></td>
									<td><?php echo (get_post_meta($history->ID,'scan_time',true)); ?> Seconds</td>
								</tr>
					<?php }
					}	
					?>		
					</tbody>
			</table>
			<div id="sacn_now">	
				<?php if(count($sanitize_history)>1){ ?>	
					<button onClick="clearHistory(this)"  class="btn">		
							<span>Clear Sanitize History</span>
					</button>
				<?php } ?>
			</div>
		</div>
	</div>
<?php
}
