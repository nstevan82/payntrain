<?php


/* add action for ajax page when dropdown list is clicked */
add_action("wp_ajax_myplguin_admin_page", "myplguin_admin_page");
function myplguin_admin_page(){
	?>
	<div class="wrap">
	
		<h2>MEMBERSHIP PAYMENT REPORT</h2>
		
		<form action="" method="post">
		<table>
		<tr>
		
		<td><label for="year">Year</label></td>
		<td><input type="text" name="year" id="year" value='<?php if (!empty($_POST['year'])) echo $_POST['year'];?>'></td></tr>
		<td><label for="member">Member</label></td>
		<td><input type="text" name="payment_member" id="member" value='<?php if (!empty($_POST['payment_member'])) echo $_POST['payment_member'];?>'></td></tr>		
		<td><label for="report_month">Month</label></td>
		
		<td>
		<select name="month" id="report_month">
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="") echo "selected";?> value=""></option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="1") echo "selected";?> value="1">January</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="2") echo "selected";?> value="2">February</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="3") echo "selected";?> value="3">March</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="4") echo "selected";?> value="4">April</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="5") echo "selected";?> value="5">May</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="6") echo "selected";?> value="6">Jun</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="7") echo "selected";?> value="7">July</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="8") echo "selected";?> value="8">August</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="9") echo "selected";?> value="9">September</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="10") echo "selected";?> value="10">October</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="11") echo "selected";?> value="11">November</option>
			<option <?php if (!empty($_POST['month']) && $_POST['month'][0]=="12") echo "selected";?> value="12">December</option>
		</select>

		<input type="submit" name='submit' value='View report'>
		</td></tr>
		</table>
		
		</form>
	</div>
	<div id='state-list'>
	</div>
	<?php
	if (isset($_POST['poruka'])) echo $_POST['poruka'];	
	
	
	if (isset($_POST['submit'])){
/* if the page is submited show the report */
	$posts = get_posts(array(
    'post_type'   => 'club_payment',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'fields' => 'ids',
	   'meta_query' => array(
        array(
            'key' => 'month',
            'value' => $_POST['month'],
            'compare' => 'LIKE'
        )
		),
	  'meta_query' => array(
        array(
            'key' => 'year',
            'value' => $_POST['year'],
            'compare' => 'LIKE'
        )
		),
         array(
			'key' => 'payment_member',
			'value' => $_POST['payment_member'],
			'compare' => 'LIKE'
        )
	)
);
//loop over each post
echo '<table class="report" border=1>';	
echo "<tr><th>MEMBER</th><th>PAYMENT DATE</th><th>AMOUNT</th><th>PAID FOR THE MONTH</th><th>YEAR</th></tr>";
foreach($posts as $p){
    //get the meta you need form each post
    $amount = get_post_meta($p,"amount",true);
	$date = get_post_meta($p,"date",true);
	$month = get_post_meta($p,"month",true);
	$year = get_post_meta($p,"year",true);
	$payment_member = get_post_meta($p,"payment_member",true);	
	echo '<tr><td>'.$payment_member.'</td><td>'.$date.'</td><td>'.$amount.'</td><td>'.$month.'</td><td>'.$year.'</td></tr>';

    //do whatever you want with it
}
echo "</table>";	

	}
}

?>