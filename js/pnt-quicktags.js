
jQuery(document).ready(function(){

	Date.firstDayOfWeek = 0;
Date.format = 'yyyy-mm-dd';
	jQuery('.datepicker').datepicker({ dateFormat: 'dd.mm.yy' }).val();

	
});

		
		jQuery(document).ready(function($) {
 
 jQuery('#report_month').change(function(){
 
 
    // We'll pass this variable to the PHP function example_ajax_request
    var fruit = 'Banana';
     
    // This does the ajax request
    $.ajax({
		type: "POST",
        url: ajaxurl,
        data: {
            'action':'my_actiona',
			'year' : $("#year").val(),
			'payment_member' : $("#member").val(),
        },
        success:function(data) {
            // This outputs the result of the ajax request
            console.log(data);
			jQuery("#state-list").html(data);	
		
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });  
 });          
});

	