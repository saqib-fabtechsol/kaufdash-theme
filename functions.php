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

add_filter('um_account_page_default_tabs_hook', 'my_custom_tab_in_um', 100 );
function my_custom_tab_in_um( $tabs ) {
//    remove the default tabs
	// unset($tabs[100]);


	// add our custom tab


	$tabs[200]['dashboard']['icon'] = 'um-icon-home';
	$tabs[200]['dashboard']['title'] = 'Dashboard';
	$tabs[200]['dashboard']['custom'] = true;
	$tabs[200]['dashboard']['show_button'] = false;

	$tabs[210]['orderlist']['icon'] = 'um-faicon-list-ul';
    $tabs[210]['orderlist']['title'] = 'Orderlist';
    $tabs[210]['orderlist']['custom'] = true;
	$tabs[210]['orderlist']['show_button'] = false;

    $tabs[220]['package_tracker']['icon'] = 'um-faicon-truck';
    $tabs[220]['package_tracker']['title'] = 'Package Tracker';
    $tabs[220]['package_tracker']['custom'] = true;
	$tabs[220]['package_tracker']['show_button'] = false;

	$tabs[230]['logout']['icon'] = 'um-icon-power';
	$tabs[230]['logout']['title'] = 'Logout';
	$tabs[230]['logout']['custom'] = true;
	$tabs[230]['logout']['show_button'] = false;


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
		<h3>Dashboard</h3>
		<?php echo do_shortcode('[elementor-template id="1083"]'); ?>

		
		
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

add_action('um_account_tab__package_tracker', 'um_account_tab__package_tracker');
function um_account_tab__package_tracker( $info ) {
    global $ultimatemember;
    extract( $info );

    $output = $ultimatemember->account->get_tab_output('package_tracker');
    if ( $output ) { echo $output; }
}

/* Add content to the new tabs */

add_filter('um_account_content_hook_orderlist', 'um_account_content_hook_orderlist');
function um_account_content_hook_orderlist( $output ){
    ob_start();
    ?>
    
    <div class="um-field">
		<h3>Orderlist</h3>

		<?php echo do_shortcode('[elementor-template id="1148"]'); ?>
        
        <!-- Here goes your custom content for Orderlist -->
        <!-- <script>
            console.log('Hello from Orderlist tab');
        </script> -->
        
    </div>
            
    <?php
        
    $output .= ob_get_contents();
    ob_end_clean();
    return $output;
}

add_filter('um_account_content_hook_package_tracker', 'um_account_content_hook_package_tracker');
function um_account_content_hook_package_tracker( $output ){
    ob_start();
    ?>
    
    <div class="um-field">
        
        <!-- Here goes your custom content for Package Tracker -->
		<h3>Package Tracker</h3>
		<?php echo do_shortcode('[elementor-template id="1233"]'); ?>
        <!-- <script>
            console.log('Hello from Package Tracker tab');
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









