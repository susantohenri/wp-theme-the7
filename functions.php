<?php
/**
 * The7 theme.
 *
 * @since   1.0.0
 *
 * @package The7
 */


defined( 'ABSPATH' ) || exit;

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since 1.0.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200; /* pixels */
}

/**
 * Initialize theme.
 *
 * @since 1.0.0
 */
 
 
add_action('after_setup_theme','primer_ahoy', 15);
function primer_ahoy() {
        add_action('wp_enqueue_scripts', 'primer_scripts_and_styles', 999);
}

function primer_scripts_and_styles() {
 wp_register_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ), '', true );
 	wp_enqueue_script( 'custom-js' );
}
//wp_enqueue_script( 'script', get_template_directory_uri() . '/js/custom.js', array ( 'jquery' ), 1.1, true);

function remove_item_from_cart() {
	$cart = WC()->instance()->cart;
	$product_id = $_REQUEST['product_id'];
	$cart_id = $cart->generate_cart_id($product_id);
	$cart_item_id = $cart->find_product_in_cart($cart_id);
	if($cart_item_id){
		$cart->set_quantity($cart_item_id,0);
	}
	die();
}

add_action('wp_ajax_remove_item_from_cart', 'remove_item_from_cart');
add_action('wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart');

function load_item_from_cart() {
	$items = WC()->cart->get_cart();
    $ids = array();
    foreach($items as $item => $values) {
        $_product = $values['data']->post;
		$ids[] = $_product->ID;
	}
	$response['ID'] = $ids;
    echo json_encode($response);
	die();
}

add_action('wp_ajax_load_item_from_cart', 'load_item_from_cart');
add_action('wp_ajax_nopriv_load_item_from_cart', 'load_item_from_cart');

if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

add_action('woocommerce_payment_complete', 'checkout_done', 10, 1);

function checkout_done($order_id) {
   
    if ( ! $order_id )
        return;

	// Get an instance of the WC_Order object
	$order = wc_get_order( $order_id );

	// Get the order key
	$order_key = $order->get_order_key();

	// Get the order number
	$order_key = $order->get_order_number();

	if($order->is_paid())
		$paid = __('yes');
	else
		$paid = __('no');

	$service = '1';
	// Loop through order items
	foreach ( $order->get_items() as $item_id => $item ) {

		// Get the product object
		$product = $item->get_product();

		// Get the product Id
		$product_id = $product->get_id();

		// Get the product name
		if ($item->get_name() == 'Basic') {
			$service = '2';
		} else if ($item->get_name() == 'Standard') {
			$service = '3';
		} else if ($item->get_name() == 'Superfast') {
			$service = '4';
		}
	}

	$mobile = get_post_meta($order_id, 'billing_mobile', true);
	
	$billing_addr = $order->get_address();
	
	$fulladdress = trim($billing_addr['address_2'] . ' ' . $billing_addr['address_1'] . ' ' . $billing_addr['city'] . ' ' . $billing_addr['state'] . ' ' . $billing_addr['postcode']);
	
	write_log("Address line built from WC order: $fulladdress");
	
    $url = "https://airtel.net.au/aus_addr.php/d";

	$data = array("address" => $fulladdress);
	
    $curl = curl_init();
    if ($data) {
        $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    
    // OPTIONS:
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    // EXECUTE:
    // 
    $json = curl_exec($curl);
	curl_close($curl);
	
	
	
    $result = json_decode($json);
    
    $addr = $result->features[0]->properties;
    

	if ($addr->formattedAddress != null) {
		$params = array(
			"first_name" => $billing_addr['first_name'],
			"last_name" => $billing_addr['last_name'],
			"unit_number" => $result->parsedQuery->complexUnitIdentifier,
			"street_number"=> $result->parsedQuery->streetNumber1,
			"street_addr" => $addr->streetName,
			"street_type" => $addr->streetType,
			"suburb" => $addr->localityName,
			"state" => $addr->stateTerritory,
			"postcode" => $addr->postcode,
			"landline" => $billing_addr['phone'],
			"mobile" => $mobile,
			"email" => $billing_addr['email'],
			"service" => $service
		);
		
		write_log($params);
		
		$url = "https://service.airtel.net.au/api/create_service";
		$ch = curl_init( $url );
		# Setup request to send json via POST.
		$payload = json_encode( $params );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		# Return response instead of printing.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result = curl_exec($ch);
		write_log($payload);
		write_log('createService result: ' . $result);

		curl_close($ch);
	}
}

require trailingslashit( get_template_directory() ) . 'inc/init.php';
