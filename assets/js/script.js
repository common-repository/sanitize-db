let progress = 0;

jQuery(document).ready(function() {
	if(jQuery('.dataTable').length > 0) {
		jQuery('.dataTable').dataTable({
		"order": [[ 0, 'desc' ]],
		});
	}	
    jQuery('#wpsdb-wrap').css('min-height', window.innerHeight);
});

var bar = new ldBar("#ldBar", {
	"stroke": '#0071a1',
	"stroke-width": 10,
	"stroke-trail-width":10,
	"preset": "circle",
	"data-duration":5,
	"data-transition-in":5,
	"value": 0
}); 


//Scan functiion to remove duplicate entries
function scanNow(thisObj){
	jQuery("#circle_progress").show();
	jQuery(thisObj).prop("disabled",true);
	jQuery(thisObj).text("Scan in progress!");	
	var ajaxTime= new Date().getTime();

	var tid = setInterval(mycode, 1000);
	function mycode() {
		progress++;
		bar.set(
			progress,     /* target value. */
			true   /* enable animation. default is true */
		);  
	}
	
	var data = {
		'action'     : 'wpsdb_scan_now'
	};
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		type: 'POST',
		dataType: 'json',
		beforeSend: function() {
			
			
		},
		success: function (response) {
			console.log(response);
			bar.set(
				100,     /* target value. */
				true   /* enable animation. default is true */
			);
			clearInterval(tid);
			progress = 0;
			var totalTime = new Date().getTime()-ajaxTime;
			//Convert milliseconds to seconds.
			var seconds = totalTime / 1000;
			//Round to 3 decimal places.
			seconds = seconds.toFixed(3);
			alert("Scan has been completed.You can check the statistics on your dashboard!")
			saveScanTime(response.history_id,seconds);
			window.location.reload();
		}	
	});
}

//remove scan history
function clearHistory(thisObj){	

	jQuery(thisObj).prop("disabled",true);
	jQuery(thisObj).text("Please wait...");	

	var data = {
		'action'     : 'wpsdb_remove_scan_history'
	};
	jQuery.ajax({
		
		url: ajaxurl,
		data: data,
		type: 'POST',
		dataType: 'json',
		beforeSend: function() {
			
			
		},
		complete: function (response) {
			window.location.reload();
		}
		
	});
}

function saveScanTime(post_id,time){
	var data = {
		'action'     : 'wpsdb_save_scan_time',
		'post_id'     : post_id,
		'scan_time'     : time
	};
	jQuery.ajax({
		
		url: ajaxurl,
		data: data,
		type: 'POST',
		//dataType: 'json',
		beforeSend: function() {
		},
		complete: function (response) {
			
		}
		
	});
}