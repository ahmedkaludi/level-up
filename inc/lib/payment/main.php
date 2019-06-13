<?php 
require_once LEVELUP_INC_DIR . '/lib/payment/paypal.php';
// require_once LEVELUP_INC_DIR . '/lib/payment/woocommerce.php';

/*
 * check if any payment is enabled
 */
if ( ! function_exists( 'trav_is_payment_enabled' ) ) {
	function trav_is_payment_enabled() {
		return apply_filters( 'trav_is_payment_enabled', false );
	}
}

/*
 * process payment
 */
if ( ! function_exists( 'trav_process_payment' ) ) {
	function trav_process_payment( $payment_data ) { // $payment_data = array('item_name', 'item_number', 'item_desc', 'item_qty', 'item_price', 'item_total_price', 'grand_total', 'status', 'return_url', 'cancel_url', 'deposit_rate')
		global $trav_options, $logo_url;
		$success = 0;
		if ( trav_is_paypal_enabled() ) {
			// validation
			if ( empty( $trav_options['acc_pay_paypal_api_username'] ) || empty( $trav_options['acc_pay_paypal_api_password'] ) || empty( $trav_options['acc_pay_paypal_api_signature'] ) ) {
				echo '<div class="alert alert-error">Please check site paypal setting. <a href="' . admin_url( 'admin.php?page=theme_options' ) . '">' . admin_url( 'admin.php?page=theme_options' ) . '</a><span class="close"></span></div>';
				exit;
			}

			$PayPalApiUsername = $trav_options['acc_pay_paypal_api_username'];
			$PayPalApiPassword = $trav_options['acc_pay_paypal_api_password'];
			$PayPalApiSignature = $trav_options['acc_pay_paypal_api_signature'];
			$PayPalMode = ( empty( $trav_options['acc_pay_paypal_sandbox'] ) ? 'live' : 'sandbox' );

			// SetExpressCheckOut
			if ( $payment_data['status'] == 'before' ) {

				$padata = 	'&METHOD=SetExpressCheckout'.
							'&RETURNURL='.urlencode($payment_data['return_url'] ).
							'&CANCELURL='.urlencode($payment_data['cancel_url']).
							'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
							'&L_PAYMENTREQUEST_0_NAME0='.urlencode($payment_data['item_name']).
							'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($payment_data['item_number']).
							'&L_PAYMENTREQUEST_0_DESC0='.urlencode($payment_data['item_desc']).
							'&L_PAYMENTREQUEST_0_AMT0='.urlencode($payment_data['item_price']).
							'&L_PAYMENTREQUEST_0_QTY0='. urlencode($payment_data['item_qty']).
							'&NOSHIPPING=1'.
							'&SOLUTIONTYPE=Sole'.
							'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($payment_data['item_total_price']).
							'&PAYMENTREQUEST_0_AMT='.urlencode($payment_data['grand_total']).
							'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode( $payment_data['currency'] ) .
							'&LOCALECODE=US'.
							'&LOGOIMG=' . $logo_url .
							'&CARTBORDERCOLOR=FFFFFF'.
							'&ALLOWNOTE=1';

				//We need to execute the "SetExpressCheckOut" method to obtain paypal token
				$paypal= new Trav_PayPal();
				$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
				
				//Respond according to message we receive from Paypal
				if ( "SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
					//Redirect user to PayPal store with Token received.
					$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';
				 	$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
					header('Location: '.$paypalurl);
				 	exit;
				} else {
					//Show error message
					echo '<div class="alert alert-error"><b>Error : </b>' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '<span class="close"></span></div>';
					echo '<pre>';
					print_r($httpParsedResponseAr);
					echo '</pre>';
					exit;
				}
			}

			// DoExpressCheckOut
			if ( isset( $_GET["token"] ) && isset( $_GET["PayerID"] ) ) {

				$token = $_GET["token"];
				$payer_id = $_GET["PayerID"];

				$padata = 	'&TOKEN='.urlencode($token).
							'&PAYERID='.urlencode($payer_id).
							'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
							'&L_PAYMENTREQUEST_0_NAME0='.urlencode($payment_data['item_name']).
							'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($payment_data['item_number']).
							'&L_PAYMENTREQUEST_0_DESC0='.urlencode($payment_data['item_desc']).
							'&L_PAYMENTREQUEST_0_AMT0='.urlencode($payment_data['item_price']).
							'&L_PAYMENTREQUEST_0_QTY0='. urlencode($payment_data['item_qty']).
							'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($payment_data['item_total_price']).
							'&PAYMENTREQUEST_0_AMT='.urlencode($payment_data['grand_total']).
							'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($payment_data['currency']);

				//execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
				$paypal = new Trav_PayPal();
				$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

				//Check if everything went ok..
				if ( "SUCCESS" == strtoupper( $httpParsedResponseAr["ACK"] ) || "SUCCESSWITHWARNING" == strtoupper( $httpParsedResponseAr["ACK"] ) ) {
					if ( $payment_data['deposit_rate'] < 100 ) {
						echo '<div class="alert alert-success">' . __( 'Security Deposit Payment Received Successfully! Your Transaction ID : ', 'trav' ) . urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]) . '<span class="close"></span></div>';
					} else {
						echo '<div class="alert alert-success">' . __( 'Payment Received Successfully! Your Transaction ID : ', 'trav' ) . urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]) . '<span class="close"></span></div>';
					}

					$transation_id = urldecode( $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"] );

					// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
					$padata = '&TOKEN='.urlencode($token);
					$paypal= new Trav_PayPal();
					$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

					if ( "SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
						$success = 1;
						return array( 'success'=>1, 'method'=>'paypal', 'transaction_id' => $transation_id );
					} else  {
						echo '<div class="alert alert-error"><b>GetTransactionDetails failed:</b>' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '<span class="close"></span></div>';
						echo '<pre>';
						print_r($httpParsedResponseAr);
						echo '</pre>';
						exit;
					}
				} else {
					echo '<div class="alert alert-error"><b>Error : </b>' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '<span class="close"></span></div>';
					echo '<pre>';
					print_r($httpParsedResponseAr);
					echo '</pre>';
					exit;
				}
			}
		}
		return false;
	}
}

/*
 * check if woocommerce payment is enabled
 */
if ( ! function_exists( 'trav_is_woo_enabled' ) ) {
	function trav_is_woo_enabled() {
		global $trav_options;
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( ! empty( $trav_options['acc_pay_woocommerce'] ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return true;
		} else {
			return false;
		}
	}
}