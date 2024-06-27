<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

	wp_enqueue_style(
		'theme-style',
		get_stylesheet_directory_uri() . '/assets/main.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

	wp_enqueue_script(
		'hello-elementor-child-js',
		get_stylesheet_directory_uri() . '/assets/main.js',
		[
			'jquery',
		],
		HELLO_ELEMENTOR_CHILD_VERSION,
		true
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );







/* add new tab called "mytab" */

add_filter('um_account_page_default_tabs_hook', 'my_custom_tab_in_um', 100);
function my_custom_tab_in_um($tabs) {
	
    // Define your custom tabs here
    $tabs[200] = [
        'dashboard' => [
            'icon' => 'um-icon-home',
            'title' => 'Dashboard',
            'custom' => true,
            'show_button' => false,
        ],
        'orderlist' => [
            'icon' => 'um-icon-ios-photos-outline',
            'title' => 'Orderlist',
            'custom' => true,
            'show_button' => false,
        ],
        'product_database' => [
            'icon' => 'um-faicon-database',
            'title' => 'Product Database',
            'custom' => true,
            'show_button' => false,
        ],
        'product_tracker' => [
            'icon' => 'um-icon-arrow-graph-up-right',
            'title' => 'Product Tracker',
            'custom' => true,
            'show_button' => false,
        ],
        'profit_calculator' => [
            'icon' => 'um-faicon-calculator',
            'title' => 'Profit Calculator',
            'custom' => true,
            'show_button' => false,
        ],
        'settings' => [
            'icon' => 'um-icon-gear',
            'title' => 'Settings',
            'custom' => true,
            'show_button' => false,
        ],
        'logout' => [
            'icon' => 'um-icon-power',
            'title' => 'Logout',
            'custom' => true,
            'show_button' => false,
        ],
    ];



    return $tabs;
}





	
/* make our new tab hookable */



add_action('um_account_tab__dashboard', 'um_account_tab__dashboard');
function um_account_tab__mytab( $info ) {
	global $ultimatemember;
	extract( $info );

	$output = $ultimatemember->account->get_tab_output('mytab');
	if ( $output ) { echo $output; }
}

/* Finally we add some content in the tab */

add_filter('um_account_content_hook_dashboard', 'um_account_content_hook_dashboard');
function um_account_content_hook_dashboard( $output ){
	ob_start();
	?>
		
	<div class="um-field">
		<?php
			$current_user = wp_get_current_user();
			$user_name = $current_user->user_login;
			echo '<h3 style="color:#000">'.$user_name.  '\'s Dashboard</h3>';

		?>

		<div class="dashboard-cards">
			<div class="card">
				<div class="top-content">
					<div>
						<h5 class="card-revenue">Revenue (today)</h5>
						
					</div>
					
					<div class="card-icon">
						<svg width="42" height="30" viewBox="0 0 42 30" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.76418 30H35.7064C39.5083 30 41.4706 28.0439 41.4706 24.3393V5.64339C41.4706 1.93883 39.5083 0 35.7064 0H5.76418C1.9798 0 0 1.93883 0 5.64339V24.3393C0 28.0612 1.9798 30 5.76418 30ZM3.48654 5.95499C3.48654 4.29313 4.38008 3.4622 5.99195 3.4622H35.4786C37.0905 3.4622 37.984 4.29313 37.984 5.95499V7.20138H3.48654V5.95499ZM5.99195 26.5378C4.38008 26.5378 3.48654 25.7069 3.48654 24.045V11.2695H37.984V24.045C37.984 25.7069 37.0905 26.5378 35.4786 26.5378H5.99195ZM8.37471 23.4045H12.6672C13.7009 23.4045 14.4017 22.7294 14.4017 21.76V18.5401C14.4017 17.5707 13.7009 16.8956 12.6672 16.8956H8.37471C7.35853 16.8956 6.65772 17.5707 6.65772 18.5401V21.76C6.65772 22.7294 7.35853 23.4045 8.37471 23.4045Z" fill="#1C1C1E"/>
						</svg>

					</div>

				</div>
				<h3 class="card-price">€450,69</h3>
				<div class="bottom-content">
				<svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none"><path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"></path></svg>
					<p>8.5% Up from yesterday</p>

				</div>
			</div>
			<div class="card">
				<div class="top-content">
					<div>
						<h5 class="card-revenue">Revenue (this month)</h5>
						
					</div>
					
					<div class="card-icon">
						<svg width="42" height="30" viewBox="0 0 42 30" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.76418 30H35.7064C39.5083 30 41.4706 28.0439 41.4706 24.3393V5.64339C41.4706 1.93883 39.5083 0 35.7064 0H5.76418C1.9798 0 0 1.93883 0 5.64339V24.3393C0 28.0612 1.9798 30 5.76418 30ZM3.48654 5.95499C3.48654 4.29313 4.38008 3.4622 5.99195 3.4622H35.4786C37.0905 3.4622 37.984 4.29313 37.984 5.95499V7.20138H3.48654V5.95499ZM5.99195 26.5378C4.38008 26.5378 3.48654 25.7069 3.48654 24.045V11.2695H37.984V24.045C37.984 25.7069 37.0905 26.5378 35.4786 26.5378H5.99195ZM8.37471 23.4045H12.6672C13.7009 23.4045 14.4017 22.7294 14.4017 21.76V18.5401C14.4017 17.5707 13.7009 16.8956 12.6672 16.8956H8.37471C7.35853 16.8956 6.65772 17.5707 6.65772 18.5401V21.76C6.65772 22.7294 7.35853 23.4045 8.37471 23.4045Z" fill="#1C1C1E"/>
						</svg>

					</div>

				</div>
				<h3 class="card-price">€10.060,45</h3>
				<div class="bottom-content">
				<svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none"><path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"></path></svg>
					<p>8.5% Up from yesterday</p>

				</div>
			</div>

			<div class="card">
				<div class="top-content">
					<div>
						<h5 class="card-revenue">Orders (today)</h5>
						
					</div>
					

					<div class="card-icon green">
					<svg width="41" height="32" viewBox="0 0 41 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M32.7622 15.3024C36.9262 15.3024 40.4211 11.8293 40.4211 7.64378C40.4211 3.45826 36.9708 0 32.7622 0C28.5684 0 25.1033 3.45826 25.1033 7.64378C25.1033 11.8442 28.5684 15.3024 32.7622 15.3024ZM12.1501 24.5492H29.4607C30.1596 24.5492 30.7991 24 30.7991 23.2134C30.7991 22.4416 30.1596 21.8924 29.4607 21.8924H12.4922C11.7783 21.8924 11.3322 21.4026 11.2281 20.6456L11.005 19.102H29.5796C31.0371 19.102 32.0037 18.5232 32.5986 17.3952C31.0519 17.3358 29.6243 16.8609 28.3899 16.0742C28.1966 16.3117 27.9438 16.4304 27.572 16.4304L10.6183 16.4453L9.47322 8.60853H23.0659C22.9469 7.77737 22.9766 6.78293 23.1254 5.95176H9.08656L8.86348 4.34879C8.67015 3.05751 8.14965 2.40445 6.4989 2.40445H1.39793C0.654351 2.40445 0 3.05751 0 3.81447C0 4.58627 0.654351 5.23933 1.39793 5.23933H6.05275L8.35785 20.9425C8.68502 23.1837 9.87475 24.5492 12.1501 24.5492ZM27.8992 7.64378C27.8992 7.03525 28.3156 6.63451 28.9253 6.63451H31.736V3.82931C31.736 3.22078 32.1376 2.80519 32.7622 2.80519C33.3868 2.80519 33.7883 3.22078 33.7883 3.82931V6.63451H36.599C37.2088 6.63451 37.6252 7.03525 37.6252 7.64378C37.6252 8.26716 37.2088 8.6679 36.599 8.6679H33.7883V11.4879C33.7883 12.0965 33.3868 12.4972 32.7622 12.4972C32.1376 12.4972 31.736 12.0965 31.736 11.4879V8.6679H28.9253C28.3156 8.6679 27.8992 8.26716 27.8992 7.64378ZM13.3696 32C14.8121 32 15.9721 30.8423 15.9721 29.4026C15.9721 27.9629 14.8121 26.8052 13.3696 26.8052C11.927 26.8052 10.767 27.9629 10.767 29.4026C10.767 30.8423 11.927 32 13.3696 32ZM27.111 32C28.5535 32 29.6986 30.8423 29.6986 29.4026C29.6986 27.9629 28.5535 26.8052 27.111 26.8052C25.6684 26.8052 24.4936 27.9629 24.4936 29.4026C24.4936 30.8423 25.6684 32 27.111 32Z" fill="#1C1C1E"/>
						</svg>

					</div>

				</div>
				<h3 class="card-price">€10.060</h3>
				<div class="bottom-content">
				<svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none"><path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"></path></svg>
					<p>8.5% Up from yesterday</p>

				</div>
			</div>
			<div class="card">
				<div class="top-content">
					<div>
						<h5 class="card-revenue">Orders (this month)</h5>
						
					</div>
					

					<div class="card-icon green">
					<svg width="41" height="32" viewBox="0 0 41 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M32.7622 15.3024C36.9262 15.3024 40.4211 11.8293 40.4211 7.64378C40.4211 3.45826 36.9708 0 32.7622 0C28.5684 0 25.1033 3.45826 25.1033 7.64378C25.1033 11.8442 28.5684 15.3024 32.7622 15.3024ZM12.1501 24.5492H29.4607C30.1596 24.5492 30.7991 24 30.7991 23.2134C30.7991 22.4416 30.1596 21.8924 29.4607 21.8924H12.4922C11.7783 21.8924 11.3322 21.4026 11.2281 20.6456L11.005 19.102H29.5796C31.0371 19.102 32.0037 18.5232 32.5986 17.3952C31.0519 17.3358 29.6243 16.8609 28.3899 16.0742C28.1966 16.3117 27.9438 16.4304 27.572 16.4304L10.6183 16.4453L9.47322 8.60853H23.0659C22.9469 7.77737 22.9766 6.78293 23.1254 5.95176H9.08656L8.86348 4.34879C8.67015 3.05751 8.14965 2.40445 6.4989 2.40445H1.39793C0.654351 2.40445 0 3.05751 0 3.81447C0 4.58627 0.654351 5.23933 1.39793 5.23933H6.05275L8.35785 20.9425C8.68502 23.1837 9.87475 24.5492 12.1501 24.5492ZM27.8992 7.64378C27.8992 7.03525 28.3156 6.63451 28.9253 6.63451H31.736V3.82931C31.736 3.22078 32.1376 2.80519 32.7622 2.80519C33.3868 2.80519 33.7883 3.22078 33.7883 3.82931V6.63451H36.599C37.2088 6.63451 37.6252 7.03525 37.6252 7.64378C37.6252 8.26716 37.2088 8.6679 36.599 8.6679H33.7883V11.4879C33.7883 12.0965 33.3868 12.4972 32.7622 12.4972C32.1376 12.4972 31.736 12.0965 31.736 11.4879V8.6679H28.9253C28.3156 8.6679 27.8992 8.26716 27.8992 7.64378ZM13.3696 32C14.8121 32 15.9721 30.8423 15.9721 29.4026C15.9721 27.9629 14.8121 26.8052 13.3696 26.8052C11.927 26.8052 10.767 27.9629 10.767 29.4026C10.767 30.8423 11.927 32 13.3696 32ZM27.111 32C28.5535 32 29.6986 30.8423 29.6986 29.4026C29.6986 27.9629 28.5535 26.8052 27.111 26.8052C25.6684 26.8052 24.4936 27.9629 24.4936 29.4026C24.4936 30.8423 25.6684 32 27.111 32Z" fill="#1C1C1E"/>
						</svg>

					</div>

				</div>
				<h3 class="card-price">€100</h3>
				<div class="bottom-content">
				<svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none"><path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"></path></svg>
					<p>8.5% Up from yesterday</p>

				</div>
			</div>
		</div>


		<div class="dashboard-charts">
			
				<canvas id="linechart" width="400" height="400"></canvas>
				<canvas id="linechart-2" width="400" height="400"></canvas>
			
			
		</div>


		<?php



	


	
		//  echo do_shortcode('[elementor-template id="1083"]'); ?>

		
		
		<!-- Here goes your custom content -->
		<!-- <script>
			console.log('Hello from my custom tab');
		</script> -->
		
	</div>		
		
	<?php
		
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}



/* Make our new tabs hookable */

add_action('um_account_tab__orderlist', 'um_account_tab__orderlist');
function um_account_tab__orderlist( $info ) {
    global $ultimatemember;
    extract( $info );

    $output = $ultimatemember->account->get_tab_output('orderlist');
    if ( $output ) { echo $output; }
}

add_action('um_account_tab__product_tracker', 'um_account_tab__product_tracker');
function um_account_tab__product_tracker( $info ) {
    global $ultimatemember;
    extract( $info );

    $output = $ultimatemember->account->get_tab_output('product_tracker');
    if ( $output ) { echo $output; }
}






/* Add content to the new tabs */

add_filter('um_account_content_hook_orderlist', 'um_account_content_hook_orderlist');
function um_account_content_hook_orderlist( $output ){
    ob_start();
    ?>
    
    <div class="um-field">
		<h3 style="color:#000">Order Lists</h3>

		<?php  echo do_shortcode('[elementor-template id="1148"]'); ?>
		


		


        
       
        
    </div>
            
    <?php
        
    $output .= ob_get_contents();
    ob_end_clean();
    return $output;
}

add_filter('um_account_content_hook_product_tracker', 'um_account_content_hook_product_tracker');
function um_account_content_hook_product_tracker( $output ){
    ob_start();
    ?>
    
    <div class="um-field">
        
        <!-- Here goes your custom content for Package Tracker -->
		<h3 style="color:#000;font-style:normal">Product Tracker</h3>
		<?php
		 echo do_shortcode('[elementor-template id="1233"]');
		
		 
		 ?>


        <!-- <script>
            console.log('Hello from Package Tracker tab');
        </script> -->
        
    </div>
            
    <?php
        
    $output .= ob_get_contents();
    ob_end_clean();
    return $output;
}


add_action('um_account_tab__product_database', 'um_account_tab__product_database');
function um_account_tab__product_database( $info ) {
	global $ultimatemember;
	extract( $info );

	$output = $ultimatemember->account->get_tab_output('product_database');
	if ( $output ) { echo $output; }
}




add_filter('um_account_content_hook_product_database', 'um_account_content_hook_product_database');
function um_account_content_hook_product_database( $output ){
	ob_start();

	



	?>
	
	<div class="um-field product-database-main">
		<h3 style="color:
				#202224">Product Database</h3>

		<div class="pd-main">
			<div class="search-and-filters">
				<!-- Search form -->
				<div class="search-form">
					<form action="" method="get">
						<input type="text" name="search" placeholder="Search by name / EAN">
						<button type="submit"><svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M20.7546 21.1456L15.536 15.5345C16.9581 13.6643 17.6573 11.278 17.489 8.86915C17.3207 6.46026 16.2977 4.21309 14.6316 2.59245C12.9656 0.971804 10.7839 0.101683 8.53793 0.162063C6.29194 0.222444 4.15346 1.20871 2.56481 2.91685C0.976163 4.62499 0.0588955 6.92433 0.00273877 9.33925C-0.0534179 11.7542 0.755833 14.0999 2.2631 15.8913C3.77037 17.6827 5.86034 18.7826 8.10072 18.9636C10.3411 19.1446 12.5605 18.3928 14.2998 16.8637L19.5183 22.4748C19.6832 22.646 19.9041 22.7408 20.1333 22.7387C20.3626 22.7365 20.5818 22.6376 20.7439 22.4634C20.906 22.2891 20.998 22.0533 21 21.8068C21.002 21.5603 20.9138 21.3229 20.7546 21.1456ZM8.7708 17.11C7.38747 17.11 6.0352 16.6689 4.885 15.8426C3.7348 15.0162 2.83833 13.8417 2.30895 12.4675C1.77957 11.0934 1.64107 9.5813 1.91094 8.12249C2.18081 6.66369 2.84695 5.32369 3.82512 4.27195C4.80328 3.22021 6.04954 2.50397 7.40629 2.2138C8.76304 1.92362 10.1694 2.07255 11.4474 2.64175C12.7254 3.21094 13.8178 4.17484 14.5863 5.41156C15.3548 6.64827 15.7651 8.10225 15.7651 9.58964C15.763 11.5835 15.0254 13.495 13.7142 14.9049C12.403 16.3147 10.6252 17.1077 8.7708 17.11Z" fill="#626262"/>
							</svg>
						</button>
					</form>
				</div>

				<!-- Filters -->
				<div class="filters">
					<!-- Period filter -->
					<div class="filter">
						<label for="period">Period:</label>
						<select id="period" name="period">
							<option value="">Select period</option>
							<option value="daily">1 week</option>
							<option value="weekly">1 month</option>
							<option value="monthly">Yeraly</option>
						</select>
					</div>

					<!-- Sort by filter -->
					<div class="filter">
						<label for="sort">Sort by:</label>
						<select id="sort" name="sort">
							
							<option value="price">Sales : High to Low</option>
							<option value="sales">Sales : Low to High</option>
						
						</select>
					</div>
				</div>
			</div>
		

		<?php

			$results = array(
				(object) array(
					'id' => 1,
					'product_image' => 'https://media.cdn.kaufland.de/product-images/1024x1024/0ed088047b894b54f710b5fe24704421.jpg',
					'product_title' => 'Product',
					'product_sales' => 10,
					'product_rate' => 3.6,
					'product_price' => 20,
					'product_revenue' => 200,
					'product_stock' => 10,

				),
				(object) array(
					'id' => 2,
					'product_image' => 'https://media.cdn.kaufland.de/product-images/1024x1024/0ed088047b894b54f710b5fe24704421.jpg',
					'product_title' => 'Product',
					'product_sales' => 10,
					'product_rate' => 3.6,
					'product_price' => 20,
					'product_revenue' => 200,
					'product_stock' => 10,

				),
				
			);


			if (!empty($results)) {
				echo '<div class="pr-products-wrapper product-database-table">';
				echo '<table>';
				echo '<tr>';
				echo '<th>Product</th>';
				echo '<th>Title</th>';
				
				echo '<th>Sales</th>';
				echo '<th>Average Price</th>';
				echo '<th>Revenue</th>';
				
				echo '<th>Review score</th>';
				echo '<th>Add to tracker</th>';
				
				echo '</tr>';

				foreach ($results as $row) {
					echo '<tr class="product-row" data-product-id="' . $row->id . '">';
					echo '<td><img src="' . esc_url($row->product_image) . '" alt="' . esc_attr($row->product_title) . '" style="max-width: 100px;"></td>';
					echo '<td><h6><b>' . esc_html($row->product_title) . '</b>
					</h6><div><span>EAN : 1234563444</span></div><div style="color: #03BD59">Track for 200 days</div></td>';
					echo '<td><b>' . esc_html($row->product_sales) . '</b></td>';
					echo '<td><b>€' . esc_html($row->product_sales * $row->product_stock) . '</b></td>';
					echo '<td><b>€' . esc_html($row->product_price) . '</b></td>';
					echo '<td><b>' . esc_html($row->product_rate) . '</b></td>';
					
					echo '<td><a href="#"><img src="' . get_stylesheet_directory_uri() . '/assets/images/plus-icon.png" alt="Description"></a></td>';
					
					echo '</tr>';
				}

				echo '</table>';
				echo '</div>';
			} else {
				echo '<p>No products found.</p>';
			}

		?>


		</div>

		
		
		<!-- Here goes your custom content for Product Database -->
		<!-- <script>
			console.log('Hello from Product Database tab');
		</script> -->
		
	</div>
			
	<?php
		
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}

add_action('um_account_tab__profit_calculator', 'um_account_tab__profit_calculator');
function um_account_tab__profit_calculator( $info ) {
	global $ultimatemember;
	extract( $info );

	$output = $ultimatemember->account->get_tab_output('profit_calculator');
	if ( $output ) { echo $output; }
}


add_filter('um_account_content_hook_profit_calculator', 'um_account_content_hook_profit_calculator');

function um_account_content_hook_profit_calculator( $output ){
	ob_start();
	?>
	
	<div class="um-field">
		<h3 style="color:#000">Profit Calculator</h3>

		<div class="profit-wrapper">
			<div class="um-field  profit-calculator">
				
				
				
				<form id="profit-calculator-form">
				<h5 style="color:#000" >Purchase Value (ex. Vat)</h5>
					<div class="field-group">
						<label for="quantity">Quantity:</label>
						<input type="number" id="quantity" name="quantity">
					</div>
					
					<div class="field-group">
						<label for="purchase-price">Purchase Price:</label>
						<input type="number" id="purchase-price" name="purchase-price">

					</div>
					
					<div class="field-group">
						<label for="transaction-cost">Transaction Cost:</label>
						<input type="number" id="transaction-cost" name="transaction-cost">
					</div>
					<div class="field-group">
						<label for="transport-cost">Transport Cost:</label>
						<input type="number" id="transport-cost" name="transport-cost">
					</div>

					<div class="field-group">
						<label for="packaging-fee">Packaging Fee:</label>
						<input type="number" id="packaging-fee" name="packaging-fee">
					</div>

					<h4 style="text-align:center;margin:20px 0px;color:#000">Coming Soon!</h4>
				</form>
				
			</div>
			<div class="profit-img">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/calculator.png" alt="">

		</div>


		
		
		<!-- Here goes your custom content for Profit Calculator -->
		<!-- <script>
			console.log('Hello from Profit Calculator tab');
		</script> -->
		
	</div>
			
	<?php
		
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}

// make logout tab hookable
add_action('um_account_tab__logout', 'um_account_tab__logout');
function um_account_tab__logout( $info ) {
	global $ultimatemember;
	extract( $info );

	$output = $ultimatemember->account->get_tab_output('logout');
	if ( $output ) { echo $output; }
}

// add content to logout tab
add_filter('um_account_content_hook_logout', 'um_account_content_hook_logout');
function um_account_content_hook_logout( $output ){
	ob_start();
	?>
		
	<div class="um-field">
		<!-- <h3>Logout</h3> -->
		<!-- <?php echo do_shortcode('[elementor-template id="1083"]'); ?> -->
		<!-- Here goes your custom content for Logout -->
		<!-- <script>
			console.log('Hello from Logout tab');
		</script> -->
		
	</div>		
		
	<?php
		
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}


add_action( 'um_after_account_page_load', 'override_a_tab', 10 );
     function override_a_tab() {
      ?>
      <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('[data-tab="logout"]').prop('disabled', true).click(function(e){
        e.preventDefault();
        window.location.href="https://kaufdash.com/logout/?redirect_to=https://kaufdash.com/login/";}); // CHANGE THIS TO YOUR LOGOUT URL
      })
      </script>
     <?php
}



	 add_filter("um_change_default_tab","um_092821_change_default_account_tab"); 
	 function um_092821_change_default_account_tab( $tab ){
		 $tab = 'dashboard'; // change this with your custom tab key
		 return $tab;
	 } 


	 add_action('um_after_account_general' , 'show_extra_fields');
	 function show_extra_fields(){
		$user_id = get_current_user_id();
		$api_key = get_user_meta($user_id, 'api_key', true);
		$client_id = get_user_meta($user_id, 'client_id', true);
	
		// echo '<div class="um-field um-field-text um-field-api_key um-field-type_text" data-key="api_key">
		// 		<div class="um-field-label">
		// 			<label for="api_key">API Key</label>
		// 			<div class="um-clear"></div>
		// 		</div>
		// 		<div class="um-field-area">
		// 			<input autocomplete="off" class="um-form-field valid " type="text" name="api_key" id="api_key" value="'.$api_key.'" placeholder="" data-key="api_key">
		// 		</div>
		// 	  </div>';
	
		// echo '<div class="um-field um-field-text um-field-secret_key um-field-type_text" data-key="client_id"">
		// 		<div class="um-field-label">
		// 			<label for="client_id"">Client ID</label>
		// 			<div class="um-clear"></div>
		// 		</div>
		// 		<div class="um-field-area">
		// 			<input autocomplete="off" class="um-form-field valid " type="text" name="client_id" id="client_id"" value="'.$client_id.'" placeholder="" data-key="client_id"">
		// 		</div>
		// 	  </div>';

		?>

		<h3 style="color:#000">Settings - Profile</h3>

		<div class="tabs-main">
			<div class="tabs-section">
				<ul class="tabs-menu">
					<li class="tab-link current" data-tab="tab-1">Edit Profile</li>
					<li class="tab-link" data-tab="tab-2">API Settings</li>
				</ul>
				<div id="tab-1" class="tab-content current">
					<div class="profile-edit">
						<div class="profile-image">
							<div>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/profile-img.png" alt="">
							<button>	<svg width="31" height="31" viewBox="0 0 31 31" fill="none" 	xmlns="http://www.w3.org/2000/svg">
								<circle cx="15.5298" cy="15.7967" r="15.0541" fill="#A52008"/>
								<g clip-path="url(#clip0_783_2807)">
								<path d="M23.144 11.9461L21.7886 13.3016C21.6504 13.4398 21.4269 13.4398 21.2887 13.3016L18.0251 10.0379C17.8869 9.89974 17.8869 9.67628 18.0251 9.53809L19.3805 8.18263C19.9303 7.63281 20.8242 7.63281 21.3769 8.18263L23.144 9.94972C23.6968 10.4995 23.6968 11.3934 23.144 11.9461ZM16.8607 10.7024L9.13965 18.4235L8.51631 21.9959C8.43105 22.4781 8.8515 22.8956 9.3337 22.8133L12.9061 22.187L20.6272 14.4659C20.7654 14.3277 20.7654 14.1043 20.6272 13.9661L17.3635 10.7024C17.2224 10.5642 16.9989 10.5642 16.8607 10.7024ZM12.1534 17.762C11.9917 17.6002 11.9917 17.3415 12.1534 17.1798L16.6814 12.6518C16.8431 12.4901 17.1018 12.4901 17.2635 12.6518C17.4253 12.8135 17.4253 13.0723 17.2635 13.234L12.7356 17.762C12.5739 17.9237 12.3151 17.9237 12.1534 17.762ZM11.092 20.2347H12.5033V21.302L10.6068 21.6343L9.69241 20.7198L10.0247 18.8234H11.092V20.2347Z" fill="white"/>
								</g>
								<defs>
								<clipPath id="clip0_783_2807">
								<rect width="15.0541" height="15.0541" fill="white" transform="translate(8.50452 7.76807)"/>
								</clipPath>
								</defs>
								</svg>
							</button>
						 </div>
							<p>Edit profile picture</p>
						</div>
						<div class="profile-form">
							<form action="your-action-url" method="post">
								<div class="form-group">
									<label for="name">Your Name</label>
									<input type="text" id="name" name="name">
								</div>
								
								<div class="form-group">
									<label for="username">Username</label>
									<input type="text" id="username" name="username">
	 							</div>

								 <div class="form-group">
									<label for="email">Email</label>
									<input type="email" id="email" name="email">
								</div>

								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" id="password" name="password">
								</div>

								<div class="form-group">
									<label for="postal-code">Postal Code</label>
									<input type="text" id="postal-code" name="postal_code">
	 							</div>

								 <div class="form-group">
									<label for="billing-address">Billing Address</label>
									<input type="text" id="billing-address" name="billing_address">
	 							</div>
								
								<div class="form-group">
									<label for="city">City</label>
									<input type="text" id="city" name="city">
								</div>

								<div class="form-group">
									<label for="country">Country</label>
									<input type="text" id="country" name="country">
								</div>

								<div class="form-group">
									<label for="vat-number">VAT Number</label>
									<input type="text" id="vat-number" name="vat_number">
								</div>
								<div class="form-group">
									<label for="phone">Phone</label>
									<input type="tel" id="phone" name="phone">
								</div>

								<input type="submit" value="Save">
							</form>
						</div>
					</div>
				</div>
				<div id="tab-2" class="tab-content">
					<div class="api-settings">
						<form action="your-action-url" method="post">
							<div class="form-group">
								<label for="client-key">Client Key</label>
								<input type="text" id="client-key" name="client_key">
							</div>
							
							<div class="form-group">
							<label for="secret-key">Secret Key</label>
							<input type="text" id="secret-key" name="secret_key">
							</div>

							<input type="submit" value="Save">
						</form>
					</div>
				</div>
			</div>
		
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/account-page.png" alt="" class="account-img">

		</div>

	
		<?php

	 }


	 
	add_action('um_user_account_update_errors_hook', 'save_extra_fields', 10, 3);
	function save_extra_fields($args, $user_id, $errors){
		print_r($args);
		if(isset($args['api_key'])){
			update_user_meta($user_id, 'api_key', $args['api_key']);
			print_r($args);

		}
		if(isset($args['client_id"'])){
			update_user_meta($user_id, 'secret_key', $args['secret_key']);
		}
	}



	// create a shortcode for product tracker
	add_shortcode('product_tracker', 'product_tracker');

	function product_tracker(){
		ob_start();


		$user_id = 2; // Replace with the actual user ID
	$meta_key = ''; // Replace with the actual meta key

	$user_meta = get_user_meta($user_id, $meta_key, true);



// if (!empty($user_meta)) {
//     echo '<div style="font-family: Arial, sans-serif; margin: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">';
//     echo '<h2>User Meta</h2>';
//     echo '<ul style="list-style-type: none;">';
//     foreach ($user_meta as $key => $value) {
//         echo '<li><strong>' . esc_html($key) . ':</strong> ' . esc_html($value) . '</li>';
//     }
//     echo '</ul>';
//     echo '</div>';
// } else {
//     echo '<p style="font-family: Arial, sans-serif; margin: 10px;">No user meta found for this key.</p>';
// }
		


		?>
		<div class="um-field product-database-main product-tracker-main">
		

		<div class="pd-main">
			<div class="icons-add">
				<a href="#">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/plus-icon.png" alt="">
				</a>
				<a href="#">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/edit-icon.png" alt="">
				</a>
			</div>
			<div class="search-and-filters">
				<!-- Search form -->
				<div class="search-form">
					<form action="" method="get">
						<input type="text" name="search" placeholder="Insert product link">
						<button >Track product
						</button>
					</form>
				</div>

				<!-- Filters -->
				<div class="filters">
					<!-- Period filter -->
					<div class="filter">
						<label for="period">Period:</label>
						<select id="period" name="period">
							<option value="">Select period</option>
							<option value="daily">1 week</option>
							<option value="weekly">1 month</option>
							<option value="monthly">Yeraly</option>
						</select>
					</div>

					<!-- Sort by filter -->
					<div class="filter">
						<label for="sort">Sort by:</label>
						<select id="sort" name="sort">
							
							<option value="price">Sales : High to Low</option>
							<option value="sales">Sales : Low to High</option>
						
						</select>
					</div>
				</div>
			</div>
		

		<?php

			$results = array(
				(object) array(
					'id' => 1,
					'product_image' => 'https://media.cdn.kaufland.de/product-images/1024x1024/0ed088047b894b54f710b5fe24704421.jpg',
					'product_title' => 'Product',
					'product_sales' => 10,
					'product_rate' => 3.6,
					'product_price' => 20,
					'product_revenue' => 200,

				),
				(object) array(
					'id' => 2,
					'product_image' => 'https://media.cdn.kaufland.de/product-images/1024x1024/0ed088047b894b54f710b5fe24704421.jpg',
					'product_title' => 'Product',
					'product_sales' => 10,
					'product_rate' => 3.6,
					'product_price' => 20,
					'product_revenue' => 200,

				),
				
			);


			if (!empty($results)) {
				echo '<div class="pr-products-wrapper product-database-table">';
				echo '<table>';
				echo '<tr>';
				echo '<th>Product</th>';
				echo '<th>Title</th>';
				
				echo '<th>Sales</th>';
				echo '<th>Average Price</th>';
				echo '<th>Revenue</th>';
				
				echo '<th>Review score</th>';
				echo '<th>Add to tracker</th>';
				
				echo '</tr>';

				foreach ($results as $row) {
					echo '<tr class="product-row" data-product-id="' . $row->id . '">';
					echo '<td><img src="' . esc_url($row->product_image) . '" alt="' . esc_attr($row->product_title) . '" style="max-width: 100px;"></td>';
					echo '<td><h6><b>' . esc_html($row->product_title) . '</b>
					</h6><div><span>EAN : 1234563444</span></div><div style="color: #03BD59">Track for 200 days</div></td>';
					echo '<td><b>' . esc_html($row->product_sales) . '</b></td>';
					echo '<td><b>€' . esc_html($row->product_sales * $row->product_stock) . '</b></td>';
					echo '<td><b>€' . esc_html($row->product_price) . '</b></td>';
					echo '<td><b>' . esc_html($row->product_rate) . '</b></td>';
					
					echo '<td><a href="#"><img src="' . get_stylesheet_directory_uri() . '/assets/images/edit-dot.png" alt="Description"></a></td>';
					
					echo '</tr>';
				}

				echo '</table>';
				echo '</div>';
			} else {
				echo '<p>No products found.</p>';
			}

		?>


		</div>

		<div class="chart-main">
    
  
    <div id="chartModal" >
       


        <div class="product-info">
            
        </div>

        <button id="closeChart" class="cross-button"></button>
        <div class="loader-wrapper">
            <div class="yellow"></div>
            <div class="red"></div>
            <div class="blue"></div>
        </div>
        <div class="filter-buttons">
            <button id="weekly">Weekly</button>
            <button id="monthly">Monthly</button>
            <button id="quarterly">3 months</button>
            <button id="yearly">Yearly</button>
        </div>
        <canvas id="salesChart"></canvas>
    </div>
</div>

		
		
		<!-- Here goes your custom content for Product Database -->
		<!-- <script>
			console.log('Hello from Product Database tab');
		</script> -->
		
	</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}



	$baseUri = "https://sellerapi.kaufland.com/v2/orders/";
	$secretKey = "85691a6e05313da29a01245087a58ad6f10f850c2fd5fb0051d85160aad35327";
	$clientKey = "ae85879a27d68685925f55408d1b2bf7";

	function signRequest($method, $uri, $body, $timestamp, $secretKey) {
		$string = implode("\n", [$method, $uri, $body, $timestamp]);
		return hash_hmac('sha256', $string, $secretKey);
	}

	function makeRequest($method, $uri, $body, $secretKey, $clientKey) {
		$timestamp = time();
		$hmac = signRequest($method, $uri, $body, $timestamp, $secretKey);

		$ch = curl_init($uri);
		if ($ch === false) {
			die('Failed to initialize cURL session.');
		}

		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json",
				"Shop-Timestamp: $timestamp",
				"Shop-Signature: $hmac",
				"Shop-Client-Key: $clientKey",
				"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3"
			]
		]);

		$result = curl_exec($ch);
		if ($result === false) {
			throw new Exception('cURL error: ' . curl_error($ch));
		}

		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($httpCode != 200) {
			throw new Exception("Request failed with status code $httpCode. Response: $result");
		}

		return json_decode($result, true);
	}

	function displayKauflandOrders() {
		global $baseUri, $secretKey, $clientKey;

		ob_start(); // Start output buffering

		try {
			// Initial request to get the list of orders
			$orderList = makeRequest("GET", $baseUri, "", $secretKey, $clientKey);

			if ($orderList && isset($orderList['data'])) {
				echo '<div class="order-lists">';
				echo '<table>';
				echo '<thead>';
				echo '<tr>';
				echo '<th>Order ID</th>';
				echo '<th>Product Title</th>';
				echo '<th>Order Address</th>';
				echo '<th>Order Date</th>';
				echo '<th>Fulfillment Type</th>';
				echo '<th>Status</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';

				foreach ($orderList['data'] as $order) {           
					$orderId = $order['id_order'];
					$orderDetailsUri = $baseUri . $orderId;

					// Request to get details for each order
					$orderDetails = makeRequest("GET", $orderDetailsUri, "", $secretKey, $clientKey);
				
					if ($orderDetails && isset($orderDetails)) {
						foreach($orderDetails as $orderItem) {
							$orderAddressStreet = $orderItem['shipping_address']['street'];
							$orderAddressCity = $orderItem['shipping_address']['city'];
							$orderAddressCountry = $orderItem['shipping_address']['country'];

							// concatenate the address
							$orderData = array(
								'id' => $orderItem['id_order'],
								'product_title' => $orderItem['order_units'][0]['product']['title'],
								'order_address' => $orderAddressStreet . ', ' . $orderAddressCity . ', ' . $orderAddressCountry,
								'order_date' => $orderItem['ts_created_iso'],
								'Type' => $orderItem['fulfillment_type'],
								'status' => $orderItem['order_units'][0]['status']
							);
							
							echo '<tr>';
							echo '<td>' . $orderData['id'] . '</td>';
							echo '<td>' . $orderData['product_title'] . '</td>';
							echo '<td>' . $orderData['order_address'] . '</td>';
							echo '<td>' . $orderData['order_date'] . '</td>';
							echo '<td>' . $orderData['Type'] . '</td>';
							echo '<td>' . $orderData['status'] . '</td>';
							echo '</tr>';               
						}
					}
				}

				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			} else {
				echo "No orders found.\n";
			}

		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		return ob_get_clean(); // Return the buffered content
	}

	function registerKauflandOrdersShortcode() {
		add_shortcode('kaufland_orders', 'displayKauflandOrders');
	}

	add_action('init', 'registerKauflandOrdersShortcode');


	

	 











