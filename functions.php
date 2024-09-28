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

$baseUri = "https://sellerapi.kaufland.com/v2/orders/";


$user_id = get_current_user_id();
$clientKey = get_user_meta($user_id, 'client_key', true);
$secretKey = get_user_meta($user_id, 'secret_key', true);






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
		return [];
	}

	return json_decode($result, true);
}

function displayKauflandOrders() {
	global $baseUri, $secretKey, $clientKey;
	
	ob_start(); // Start output buffering

	try {
		global $orderRevenue; // Declare the variable as global
		global $orderLength; // Declare the variable as global

		if (!isset($orderRevenue)) {
			$orderRevenue = 0; // Initialize only if it hasn't been already
		}

		// Initial request to get the list of orders
		$orderList = makeRequest("GET", $baseUri, "", $secretKey, $clientKey);

		if (empty($orderList) || !isset($orderList['data']) || empty($orderList['data'])) {
			$orderLength = 0;
			echo '<a style="text-decoration:underline" href="/account/general/" target="">No orders found. Click here to check your API Keys</a>';
			return ob_get_clean();
		} else {
			$orderLength = count($orderList['data']);
		}

        echo '<input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for orders..">';

		echo '<div class="order-lists">';
		echo '<table id="orderTable">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>ID</th>';
		echo '<th>NAME</th>';
		echo '<th>ADDRESS</th>';
		echo '<th>DATE</th>';
		echo '<th>TYPE</th>';
		echo '<th>STATUS</th>';
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

					$orderFirstName = $orderItem['shipping_address']['first_name'];
					$orderLastName = $orderItem['shipping_address']['last_name'];
					$orderFullName = $orderFirstName . ' ' . $orderLastName;
					$orderPrice = $orderItem['order_units'][0]['price'];

					$orderRevenue += $orderPrice; // Accumulate the total price

					// Concatenate the address
					$orderData = array(
						'id' => $orderItem['id_order'],
						'product_title' => $orderItem['order_units'][0]['product']['title'],
						'name' => $orderFullName,
						'order_address' => $orderAddressStreet . ', ' . $orderAddressCity . ', ' . $orderAddressCountry,
                        'order_date' => (new DateTime($orderItem['ts_created_iso']))->format('Y-m-d '),
						'Type' => $orderItem['fulfillment_type'],
						'status' => $orderItem['order_units'][0]['status']
					);
					
					echo '<tr>';
					echo '<td>' . htmlspecialchars($orderData['id']) . '</td>';
					echo '<td>' . htmlspecialchars($orderData['name']) . '</td>';
					echo '<td>' . htmlspecialchars($orderData['order_address']) . '</td>';
					echo '<td>' . htmlspecialchars($orderData['order_date']) . '</td>';
					echo '<td>' . htmlspecialchars($orderData['Type']) . '</td>';
					echo '<td><span style="padding:6px;background:#CCF0EB;color:#00B69B">' . htmlspecialchars($orderData['status']) . '</span></td>';
					echo '</tr>';               
				}
			}
		}

		echo '</tbody>';
		echo '</table>';
		echo '</div>';

        
    echo '<script>
    function filterTable() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("orderTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    }
                }
            }
        }
    }
    </script>';

	} catch (Exception $e) {
		echo "Error: " . htmlspecialchars($e->getMessage());
	}

	return ob_get_clean(); // Return the buffered content
}

function registerKauflandOrdersShortcode() {
	add_shortcode('kaufland_orders', 'displayKauflandOrders');
}

add_action('init', 'registerKauflandOrdersShortcode');


	

	

add_filter('um_account_content_hook_dashboard', 'um_account_content_hook_dashboard');
function um_account_content_hook_dashboard($output) {
    global $baseUri, $secretKey, $clientKey;

    global $orderRevenue;
    global $orderLength;
    $monthlyRevenue = [];
    $monthlyOrders = [];
    $yearlyRevenue = [];
    $yearlyOrders = [];
    $currentYear = date('Y');

    if (!isset($orderRevenue)) {
        $orderRevenue = 0;
    }

    $orderLists = makeRequest("GET", $baseUri, "", $secretKey, $clientKey);

  

	if(empty($orderLists)){
		
		$orderLength = 0;
	}else{
		$orderLength = count($orderLists['data']);
	}

   
    $orderRevenue = 0;

    if ($orderLists && isset($orderLists['data'])) {
        foreach ($orderLists['data'] as $order) {
            $orderId = $order['id_order'];
            $orderDetailsUri = $baseUri . $orderId;

            $orderDetails = makeRequest("GET", $orderDetailsUri, "", $secretKey, $clientKey);
           



            if ($orderDetails && isset($orderDetails)) {
                foreach($orderDetails as $orderItem) {
                    $orderPrice = $orderItem['order_units'][0]['price'] / 100;
                    $orderRevenue += $orderPrice;

                    $orderDate = new DateTime($order['ts_created_iso']);
                    $monthYearKey = $orderDate->format('m-Y');
                    $yearKey = $orderDate->format('Y');
                    $monthKey = $orderDate->format('m');

                    if (!isset($monthlyRevenue[$monthYearKey])) {
                        $monthlyRevenue[$monthYearKey] = 0;
                        $monthlyOrders[$monthYearKey] = 0;
                    }

                    if (!isset($yearlyRevenue[$yearKey])) {
                        $yearlyRevenue[$yearKey] = 0;
                        $yearlyOrders[$yearKey] = 0;
                    }

                    if (!isset($monthlyRevenue[$monthKey])) {
                        $monthlyRevenue[$monthKey] = 0;
                        $monthlyOrders[$monthKey] = 0;
                    }

                    $monthlyRevenue[$monthYearKey] += $orderPrice;
                    $monthlyOrders[$monthYearKey]++;
                    $yearlyRevenue[$yearKey] += $orderPrice;
                    $yearlyOrders[$yearKey]++;
                }
            }
        }
    }

    // Filter arrays to include only the current year's data
    $filteredYearlyRevenue = array_filter($monthlyRevenue, function($key) use ($currentYear) {
        return strpos($key, $currentYear) !== false;
    }, ARRAY_FILTER_USE_KEY);

    $filteredYearlyOrders = array_filter($monthlyOrders, function($key) use ($currentYear) {
        return strpos($key, $currentYear) !== false;
    }, ARRAY_FILTER_USE_KEY);

    // Fill in missing months with zero values
    $months = [
        '01', '02', '03', '04', '05', '06',
        '07', '08', '09', '10', '11', '12'
    ];

    $filteredYearlyRevenue = array_reduce($months, function($carry, $month) use ($filteredYearlyRevenue, $currentYear) {
        $key = "$month-$currentYear";
        $carry[$key] = isset($filteredYearlyRevenue[$key]) ? $filteredYearlyRevenue[$key] : 0;
        return $carry;
    }, []);

    $filteredYearlyOrders = array_reduce($months, function($carry, $month) use ($filteredYearlyOrders, $currentYear) {
        $key = "$month-$currentYear";
        $carry[$key] = isset($filteredYearlyOrders[$key]) ? $filteredYearlyOrders[$key] : 0;
        return $carry;
    }, []);

    // foreach ($monthlyRevenue as $monthYear => $revenue) {
    //     echo "Revenue for $monthYear: €$revenue, Orders: " . $monthlyOrders[$monthYear] . "<br>";
    // }

    ob_start();
    ?>

    <div class="um-field">
        <?php
        $current_user = wp_get_current_user();
        $user_name = $current_user->user_login;
        echo "<h3 style=\"color:#202224;font-size: 36px;font-family: 'Nunito Sans';margin-bottom:30px;font-weight:700; \">" . $user_name . "'s Dashboard</h3>";

        

        $currentMonthYear = (new DateTime())->format('m-Y');
        $monthlyRevenueCurrent = isset($monthlyRevenue[$currentMonthYear]) ? $monthlyRevenue[$currentMonthYear] : 0;
        $monthlyOrdersCurrent = isset($monthlyOrders[$currentMonthYear]) ? $monthlyOrders[$currentMonthYear] : 0;

        ?>

        <div class="dashboard-cards">
            <div class="card">
                <div class="top-content">
                    <div>
                        <h5 class="card-revenue">Revenue (Total)</h5>
                    </div>
                    <div class="card-icon">
                        <svg width="42" height="30" viewBox="0 0 42 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.76418 30H35.7064C39.5083 30 41.4706 28.0439 41.4706 24.3393V5.64339C41.4706 1.93883 39.5083 0 35.7064 0H5.76418C1.9798 0 0 1.93883 0 5.64339V24.3393C0 28.0612 1.9798 30 5.76418 30ZM3.48654 5.95499C3.48654 4.29313 4.38008 3.4622 5.99195 3.4622H35.4786C37.0905 3.4622 37.984 4.29313 37.984 5.95499V7.20138H3.48654V5.95499ZM5.99195 26.5378C4.38008 26.5378 3.48654 25.7069 3.48654 24.045V11.2695H37.984V24.045C37.984 25.7069 37.0905 26.5378 35.4786 26.5378H5.99195ZM8.37471 23.4045H12.6672C13.7009 23.4045 14.4017 22.7294 14.4017 21.76V18.5401C14.4017 17.5707 13.7009 16.8956 12.6672 16.8956H8.37471C7.35853 16.8956 6.65772 17.5707 6.65772 18.5401V21.76C6.65772 22.7294 7.35853 23.4045 8.37471 23.4045Z" fill="#1C1C1E"/>
                        </svg>
                    </div>
                </div>
                <h3 class="card-price">€<?php echo $orderRevenue  ; ?></h3>
                <div class="bottom-content">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none">
                        <path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"/>
                    </svg>
                    <p>8.5% Up from yesterday</p> -->
                </div>
            </div>
            <div class="card">
                <div class="top-content">
                    <div>
                        <h5 class="card-revenue">Total Revenue (this month)</h5>
                    </div>
                    <div class="card-icon">
                        <svg width="42" height="30" viewBox="0 0 42 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.76418 30H35.7064C39.5083 30 41.4706 28.0439 41.4706 24.3393V5.64339C41.4706 1.93883 39.5083 0 35.7064 0H5.76418C1.9798 0 0 1.93883 0 5.64339V24.3393C0 28.0612 1.9798 30 5.76418 30ZM3.48654 5.95499C3.48654 4.29313 4.38008 3.4622 5.99195 3.4622H35.4786C37.0905 3.4622 37.984 4.29313 37.984 5.95499V7.20138H3.48654V5.95499ZM5.99195 26.5378C4.38008 26.5378 3.48654 25.7069 3.48654 24.045V11.2695H37.984V24.045C37.984 25.7069 37.0905 26.5378 35.4786 26.5378H5.99195ZM8.37471 23.4045H12.6672C13.7009 23.4045 14.4017 22.7294 14.4017 21.76V18.5401C14.4017 17.5707 13.7009 16.8956 12.6672 16.8956H8.37471C7.35853 16.8956 6.65772 17.5707 6.65772 18.5401V21.76C6.65772 22.7294 7.35853 23.4045 8.37471 23.4045Z" fill="#1C1C1E"/>
                        </svg>
                    </div>
                </div>
                <h3 class="card-price">€<?php echo $monthlyRevenueCurrent; ?></h3>
                <div class="bottom-content">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none">
                        <path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"/>
                    </svg>
                    <p>8.5% Up from yesterday</p> -->
                </div>
            </div>
			<div class="card">
				<div class="top-content">
					<div>
						<h5 class="card-revenue">Total Orders</h5>
					</div>
					<div class="card-icon">
                    <svg width="41" height="32" viewBox="0 0 41 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M32.7622 15.3024C36.9262 15.3024 40.4211 11.8293 40.4211 7.64378C40.4211 3.45826 36.9708 0 32.7622 0C28.5684 0 25.1033 3.45826 25.1033 7.64378C25.1033 11.8442 28.5684 15.3024 32.7622 15.3024ZM12.1501 24.5492H29.4607C30.1596 24.5492 30.7991 24 30.7991 23.2134C30.7991 22.4416 30.1596 21.8924 29.4607 21.8924H12.4922C11.7783 21.8924 11.3322 21.4026 11.2281 20.6456L11.005 19.102H29.5796C31.0371 19.102 32.0037 18.5232 32.5986 17.3952C31.0519 17.3358 29.6243 16.8609 28.3899 16.0742C28.1966 16.3117 27.9438 16.4304 27.572 16.4304L10.6183 16.4453L9.47322 8.60853H23.0659C22.9469 7.77737 22.9766 6.78293 23.1254 5.95176H9.08656L8.86348 4.34879C8.67015 3.05751 8.14965 2.40445 6.4989 2.40445H1.39793C0.654351 2.40445 0 3.05751 0 3.81447C0 4.58627 0.654351 5.23933 1.39793 5.23933H6.05275L8.35785 20.9425C8.68502 23.1837 9.87475 24.5492 12.1501 24.5492ZM27.8992 7.64378C27.8992 7.03525 28.3156 6.63451 28.9253 6.63451H31.736V3.82931C31.736 3.22078 32.1376 2.80519 32.7622 2.80519C33.3868 2.80519 33.7883 3.22078 33.7883 3.82931V6.63451H36.599C37.2088 6.63451 37.6252 7.03525 37.6252 7.64378C37.6252 8.26716 37.2088 8.6679 36.599 8.6679H33.7883V11.4879C33.7883 12.0965 33.3868 12.4972 32.7622 12.4972C32.1376 12.4972 31.736 12.0965 31.736 11.4879V8.6679H28.9253C28.3156 8.6679 27.8992 8.26716 27.8992 7.64378ZM13.3696 32C14.8121 32 15.9721 30.8423 15.9721 29.4026C15.9721 27.9629 14.8121 26.8052 13.3696 26.8052C11.927 26.8052 10.767 27.9629 10.767 29.4026C10.767 30.8423 11.927 32 13.3696 32ZM27.111 32C28.5535 32 29.6986 30.8423 29.6986 29.4026C29.6986 27.9629 28.5535 26.8052 27.111 26.8052C25.6684 26.8052 24.4936 27.9629 24.4936 29.4026C24.4936 30.8423 25.6684 32 27.111 32Z" fill="#1C1C1E"/>
                        </svg>

					</div>
				</div>
				<h3 class="card-price"><?php echo $orderLength; ?></h3>
				<div class="bottom-content">
					<!-- <svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none">
						<path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"/>
					</svg>
					<p>8.5% Up from yesterday</p> -->

				</div>
			</div>

			<div class="card">
				<div class="top-content">
					<div>
						<h5 class="card-revenue">Total Orders (this month)</h5>
					</div>
						<div class="card-icon">
                        <svg width="41" height="32" viewBox="0 0 41 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M32.7622 15.3024C36.9262 15.3024 40.4211 11.8293 40.4211 7.64378C40.4211 3.45826 36.9708 0 32.7622 0C28.5684 0 25.1033 3.45826 25.1033 7.64378C25.1033 11.8442 28.5684 15.3024 32.7622 15.3024ZM12.1501 24.5492H29.4607C30.1596 24.5492 30.7991 24 30.7991 23.2134C30.7991 22.4416 30.1596 21.8924 29.4607 21.8924H12.4922C11.7783 21.8924 11.3322 21.4026 11.2281 20.6456L11.005 19.102H29.5796C31.0371 19.102 32.0037 18.5232 32.5986 17.3952C31.0519 17.3358 29.6243 16.8609 28.3899 16.0742C28.1966 16.3117 27.9438 16.4304 27.572 16.4304L10.6183 16.4453L9.47322 8.60853H23.0659C22.9469 7.77737 22.9766 6.78293 23.1254 5.95176H9.08656L8.86348 4.34879C8.67015 3.05751 8.14965 2.40445 6.4989 2.40445H1.39793C0.654351 2.40445 0 3.05751 0 3.81447C0 4.58627 0.654351 5.23933 1.39793 5.23933H6.05275L8.35785 20.9425C8.68502 23.1837 9.87475 24.5492 12.1501 24.5492ZM27.8992 7.64378C27.8992 7.03525 28.3156 6.63451 28.9253 6.63451H31.736V3.82931C31.736 3.22078 32.1376 2.80519 32.7622 2.80519C33.3868 2.80519 33.7883 3.22078 33.7883 3.82931V6.63451H36.599C37.2088 6.63451 37.6252 7.03525 37.6252 7.64378C37.6252 8.26716 37.2088 8.6679 36.599 8.6679H33.7883V11.4879C33.7883 12.0965 33.3868 12.4972 32.7622 12.4972C32.1376 12.4972 31.736 12.0965 31.736 11.4879V8.6679H28.9253C28.3156 8.6679 27.8992 8.26716 27.8992 7.64378ZM13.3696 32C14.8121 32 15.9721 30.8423 15.9721 29.4026C15.9721 27.9629 14.8121 26.8052 13.3696 26.8052C11.927 26.8052 10.767 27.9629 10.767 29.4026C10.767 30.8423 11.927 32 13.3696 32ZM27.111 32C28.5535 32 29.6986 30.8423 29.6986 29.4026C29.6986 27.9629 28.5535 26.8052 27.111 26.8052C25.6684 26.8052 24.4936 27.9629 24.4936 29.4026C24.4936 30.8423 25.6684 32 27.111 32Z" fill="#1C1C1E"/>
                        </svg>

						</div>
				</div>
					<h3 class="card-price"><?php echo $monthlyOrdersCurrent; ?></h3>
					<div class="bottom-content">
						<!-- <svg xmlns="http://www.w3.org/2000/svg" width="27" height="17" viewBox="0 0 27 17" fill="none">
							<path d="M18.6667 0.333328L21.72 3.38666L15.2133 9.89333L9.88 4.56L0 14.4533L1.88 16.3333L9.88 8.33333L15.2133 13.6667L23.6133 5.27999L26.6667 8.33333V0.333328H18.6667Z" fill="#00B69B"/>
						</svg>
						<p>8.5% Up from yesterday</p> -->
					</div>
				</div>
        </div>

		<div class="dashboard-charts">
			<canvas id="yearlyRevenueChart"></canvas>
			<canvas id="yearlyOrdersChart"></canvas>
		</div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let ctx1 = document.getElementById('yearlyRevenueChart').getContext('2d');
            let ctx2 = document.getElementById('yearlyOrdersChart').getContext('2d');

            let yearlyRevenueData = <?php echo json_encode(array_values($filteredYearlyRevenue)); ?>;
            let yearlyRevenueLabels = <?php echo json_encode(array_keys($filteredYearlyRevenue)); ?>;
            let yearlyOrdersData = <?php echo json_encode(array_values($filteredYearlyOrders)); ?>;
            let yearlyOrdersLabels = <?php echo json_encode(array_keys($filteredYearlyOrders)); ?>;

            console.log(yearlyRevenueData , "yearlyRevenueData");
            console.log(yearlyRevenueLabels , "yearlyRevenueLabels");
            console.log(yearlyOrdersData , "yearlyOrdersData");
            console.log(yearlyOrdersLabels , "yearlyOrdersLabels");

            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: yearlyRevenueLabels,
                    datasets: [{
                        label: 'Store Performance',
                        data: yearlyRevenueData,
                        borderColor: '#A52008',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
						tension : 0.5,
                        
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 50,
                                    callback: function(value, index, values) {
                                    return '$' + value;
                                },
                                font: {
                                     size: 14 // Adjust the font size for y-axis labels
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 16 // Adjust the font size for dataset labels
                                }
                            }
                        }
                    }
                }
            });

            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: yearlyOrdersLabels,
                    datasets: [{
                        label: 'Total Orders',
                        data: yearlyOrdersData,
                        borderColor: '#08A569',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
						tension : 0.5
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5,
                                font: {
                                size: 14 // Adjust the font size for y-axis labels
                            }
                                
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 16 // Adjust the font size for dataset labels
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <?php
    $output .= ob_get_clean();
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
    
    
        <h3 style="color:#202224;font-size: 36px;font-family: 'Nunito Sans';margin-bottom:30px;font-weight:700;">
            Product Tracker
        </h3>
        
        <!-- Custom content for Product Tracker -->
        <?php
echo do_shortcode('[user_product_tracker]');


    
            

    
    $output .= ob_get_clean(); // Get the output and clean the buffer
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

    <h3 style="color:#202224;font-size: 36px;font-family: 'Nunito Sans';margin-bottom:5px;font-weight:700; ">Product Database</h3>

    <?php

	
 
//    show the shortcode here
	echo do_shortcode('[search_form]');

	
	
	
			
	
		
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



function kaufdash_custom_js() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Function to set the active tab based on the current URL
        function setActiveTabFromURL() {
            var pathArray = window.location.pathname.split('/');
            var path = pathArray.pop() || pathArray.pop();  // Handle trailing '/'

            console.log(path, "path check");

            // Default tab is 'dashboard'
            var activeTab = 'dashboard';

            // Map URL paths to tab data-tab values
            var tabMap = {
                'account': 'dashboard', // Default to dashboard if path is empty
                'general': 'general',
                'dashboard': 'dashboard',
                'orderlist': 'orderlist',
                'product_database': 'product_database',
                'product_tracker': 'product_tracker',
                'profit_calculator': 'profit_calculator'
                // Add more mappings as needed
            };

            // Check if the current path is in the tabMap
            if (tabMap.hasOwnProperty(path)) {
                activeTab = tabMap[path];
                console.log(activeTab, "active tab");
            }

            // Remove active class from all tabs and hide their content
            $('.um-account-link').removeClass('current');
            $('.um-account-tab').hide();

            // Set the current tab as active and show its content
            $('a[data-tab="' + activeTab + '"]').addClass('current');
            $('.um-account-tab[data-tab="' + activeTab + '"]').show();
        }

        // Call the function on page load
        setActiveTabFromURL();
    });
    </script>
    <?php
}
add_action('wp_footer', 'kaufdash_custom_js');

// Ensure jQuery is enqueued
function kaufdash_enqueue_scripts() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'kaufdash_enqueue_scripts');



	


	 add_action('um_after_account_general' , 'show_extra_fields');
	 function show_extra_fields(){
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$user_id = get_current_user_id();

			// Check which form was submitted
			if (isset($_POST['form_type']) && $_POST['form_type'] == 'api_keys') {
				// Process API Keys form
				if (isset($_POST['client_key'])) {
					update_user_meta($user_id, 'client_key', sanitize_text_field($_POST['client_key']));
				}
				if (isset($_POST['secret_key'])) {
					update_user_meta($user_id, 'secret_key', sanitize_text_field($_POST['secret_key']));
				}
			} elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'billing_info') {
				// Process Billing Info form
				$display_name = sanitize_text_field($_POST['display_name']);
				$email = sanitize_email($_POST['email']);
				// Ensure other fields are only processed if this form was submitted
				$billing_address = sanitize_text_field($_POST['billing_address']);
				$city = sanitize_text_field($_POST['city']);
				$country = sanitize_text_field($_POST['country']);
				$phone = sanitize_text_field($_POST['phone']);
                $password = sanitize_text_field($_POST['new_password']);
                $confirm_password = sanitize_text_field($_POST['confirm_password']);

				// Update user info in wp_users table
				$user_id = wp_update_user(array(
					'ID' => $user_id,
					'display_name' => $display_name,
					'user_email' => $email
				));

				if (!is_wp_error($user_id)) {
					// User update was successful
					global $wpdb;
					// Update custom fields in wp_pmpro_membership_orders table
					$wpdb->update(
						'wp_pmpro_membership_orders',
						array(
							'billing_city' => $city,
							'billing_state' => $billing_address,
							'billing_country' => $country,
							'billing_phone' => $phone
						),
						array('user_id' => $user_id) // Where clause
					);

                                // Change password if provided and matches confirmation
                    if (!empty($password) && $password === $confirm_password) {
                        wp_set_password($password, $user_id);
                       
                        ?>
                        <script>
                            alert('Password changed successfully. Please log in again.');
                        </script>
                        <?php
                        echo '<p>Password changed successfully.</p>';
                    } elseif (!empty($password) && $password !== $confirm_password) {
                        echo '<p>Passwords do not match. Please try again.</p>';
                    }



				} else {
					// Handle errors
					$error = $user_id->get_error_message();
					// Display error message
				}
			}

			// Reload the page to prevent form resubmission
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit;
		}
	
		$user_id = get_current_user_id();
		$client_key = get_user_meta($user_id, 'client_key', true);
		$secret_key = get_user_meta($user_id, 'secret_key', true);
	
		global $wpdb;
		$query = $wpdb->prepare("
			SELECT o.user_id, o.billing_city, o.billing_state, o.billing_country, o.billing_phone, u.display_name, u.user_email, u.user_pass 
			FROM wp_pmpro_membership_orders AS o 
			INNER JOIN wp_users AS u ON o.user_id = u.ID 
			WHERE o.user_id = %d", 
			$user_id
		);
		$billing_info = $wpdb->get_row($query);


        // Retrieve the plain text password
$plain_text_password = get_user_meta($user_id, 'plain_text_password', true);

echo $plain_text_password;

	
		?>
	
		<h3 style="color:#000">Settings - Profile</h3>
	
		

<div class="tabs-main">
    <div class="tabs-section">
        <ul class="tabs-menu">
            <li class="tab-link " data-tab="tab-1">Edit Profile</li>
            <li class="tab-link current " data-tab="tab-2">Edit Profile</li>
            <li class="tab-link" data-tab="tab-3">API Settings</li>
        </ul>

		<div id="tab-1" class="tab-content " >
			<form>
	</form>
	</div>
				
        <div id="tab-2" class="tab-content current">
            <div class="profile-edit">
                <!-- <div class="profile-image">
                    <div>
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/profile-img.png" alt="">
                        <button>
                            <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                </div> -->
                <div class="profile-form">
                    <form action="" method="post">
                        <input type="hidden" name="form_type" value="billing_info">
                        <div class="form-group">
                            <label for="display_name">Your Name</label>
                            <input type="text" id="display_name" name="display_name" value="<?php echo esc_attr($billing_info->display_name); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" class="input pmpro_required" size="30" value="<?php echo esc_attr($billing_info->user_email); ?>">
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>
                        <div class="form-group">
                            <label for="billing-address">Billing Address</label>
                            <input type="text" id="billing-address" name="billing_address" value="<?php echo esc_attr($billing_info->billing_state); ?>">
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="<?php echo esc_attr($billing_info->billing_city); ?>">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" value="<?php echo esc_attr($billing_info->billing_country); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($billing_info->billing_phone); ?>">
                        </div>
                        <div class="submit-wrp">
                        <input type="submit" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="tab-3" class="tab-content">
            <div class="api-settings">
            <form action="" method="post">
                <input type="hidden" name="form_type" value="api_keys">
                <div class="form-group">
                    <label for="client-key">Client Key</label>
                    <div style="position: relative;">
                        <input type="password" id="client-key" name="client_key" value="<?php echo esc_attr($client_key); ?>">
                        <span onclick="toggleVisibility('client-key')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            👁️
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="secret-key">Secret Key</label>
                    <div style="position: relative;">
                        <input type="password" id="secret-key" name="secret_key" value="<?php echo esc_attr($secret_key); ?>">
                        <span onclick="toggleVisibility('secret-key')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            👁️
                        </span>
                    </div>
                </div>
                <input type="submit" value="Save">
            </form>

            <script>
            function toggleVisibility(id) {
                var input = document.getElementById(id);
                if (input.type === "password") {
                    input.type = "text";
                } else {
                    input.type = "password";
                }
            }
            </script>
            </div>
        </div>
    </div>
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/account-page.png" alt="" class="account-img">
</div>

	
		<?php
	}

	


	 
	 add_action('um_user_account_update_errors_hook', 'save_extra_fields', 10, 3);
	 function save_extra_fields($args, $user_id, $errors) {
		 if (isset($args['client_key'])) {
			 update_user_meta($user_id, 'client_key', $args['client_key']);
		 }
		 if (isset($args['secret_key'])) {
			 update_user_meta($user_id, 'secret_key', $args['secret_key']);
		 }
	 }




    
     function redirect_account_to_login() {
        // Check if the current URL contains "account" and the user is not logged in
        if (strpos($_SERVER['REQUEST_URI'], 'account') !== false && !is_user_logged_in()) {
            // Redirect to the login page
            wp_redirect(home_url('/login/'));
            exit;
        }
    }
     // Hook into the template_redirect action with high priority
     add_action('template_redirect', 'redirect_account_to_login', 1);



     
add_action('template_redirect', 'redirect_account_to_login', 1);



	

	 











