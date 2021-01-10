<?php
/*
  Plugin Name: Knack GiveWP Plugin
  Plugin URI: https://github.com/remmosnimajneb/Knack-PHP
  Description: Sync's GiveWP Donations with Knack
  Version: 1.0
  Author: Benjamin Sommer 
  Author URI: https://github.com/remmosnimajneb/
*/

  	/* Set the NameSpace so we can have multiple Plugins use the Knack Class safely */
  		namespace Knack_PHP\GiveWP_Sync;
  		use \stdClass;
  		use \Give_Payments_Query;

	/* Make sure we can't call directly */
  		if ( !defined( 'ABSPATH' ) ) {
	  		exit;
		}

	/* Include the Knack Wrapper Class */
		require_once('Includes/KnackPHPWrapper/Knack.Class.php');

		/* Setup Knack */
		DEFINE('KNACK_APPLICATION_ID_GWPKN', '');
		DEFINE('KNACK_API_KEY_GWPKN', '');

		DEFINE( 'KNACK_DONATIONS_OBJECT_ID_GWPKN', 1 );

		$Knack = new Knack();
		$Knack->SetCredentials(KNACK_APPLICATION_ID_GWPKN, KNACK_API_KEY_GWPKN);

	/* Configurations and Handlers */

		/*
	    * Hook to add a "Proccess Donation" button to the GiveWP Donation Dashboard
	    * Fired By action give_view_donation_details_update_after
	    * @param $PaymentID int ID to add to URL for Running Script
	    */
	    function add_sync_donation_knack_button($PaymentID){
	        ?>
	            <div id="major-publishing-actions">
	                <div id="publishing-action" style="text-align: center;">
	                    <p><strong>Sync Donation to Knack</strong></p>
	                    <a href="#" class="button-secondary" id="SyncToKnackButton" data-donation-id="<?php echo $PaymentID; ?>" style="background-color: green;color: white;">Sync to Knack</a>
	                </div>
	                <div class="clear"></div>
	            </div>
	        <?php
	    }
	    add_filter( 'give_view_donation_details_update_after', __NAMESPACE__ . '\\add_sync_donation_knack_button' );

	    /*
	    * Enqueue the Script for the AJAX call
	    */
	    function knack_givewp_enqueue_scripts() {
	        wp_enqueue_script( 'script-name', get_site_url() . '/wp-content/plugins/knack-givewp-sync/Includes/SyncGiveWPKnack.js', array(), '1.2.0', true );
	    }
	    add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\knack_givewp_enqueue_scripts' );


	    /* 
	    * Add Action for the AJAX Call
	    * @Param $_REQUEST['DonationID'] = GiveWP Donation ID, also WP Post ID
	    * @Return (int) 0 for error, 1 for OK
	    */ 
		function sync_knack_givewp_ajax() {
			 
			global $Knack;
		 
			/* Setup GiveWP Donation */
			$DonationObject = new Give_Payments_Query( array( 'id' => $_REQUEST['DonationID'] ) );
			$DonationObject = $DonationObject->get_payments()[0];
			$DonationInfo = give_get_meta( $_REQUEST['DonationID'] );

				/* Set Vars (Sample variables, you can add more if avail or only use some, etc.) */
				$DonationID = $_REQUEST['DonationID'];
				$DonationTotal = $DonationInfo['_give_payment_total'][0];
				$Currency = $DonationInfo['_give_payment_currency'][0];
				$BillingFirstName = $DonationInfo['_give_donor_billing_first_name'][0];
				$BillingLastName = $DonationInfo['_give_donor_billing_last_name'][0];
				$Address1 = $DonationInfo['_give_donor_billing_address1'][0];
				$Address2 = $DonationInfo['_give_donor_billing_address2'][0];
				$City = $DonationInfo['_give_donor_billing_city'][0];
				$State = $DonationInfo['_give_donor_billing_state'][0];
				$ZIP = $DonationInfo['_give_donor_billing_zip'][0];
				$Country = $DonationInfo['_give_donor_billing_country'][0];
				$Gateway = $DonationInfo['_give_payment_gateway'][0];
				$DonorEmail = $DonationInfo['_give_payment_donor_email'][0];
				$GiveWPPurchaseKey = $DonationInfo['_give_payment_purchase_key'][0];
				$WPDonorID = $DonationInfo['_give_payment_donor_id'][0];
				$IsRecurring = $DonationInfo['_give_is_donation_recurring'][0];
				$IsAnon = $DonationInfo['_give_anonymous_donation'][0];
				$Date = $DonationInfo['_give_completed_date'][0];
				$GatewayTransactionID = $DonationInfo['_give_payment_transaction_id'][0];

			/* Name */
				$Name = new stdClass();
					$Name->title = "";
					$Name->first = $BillingFirstName;
					$Name->middle = "";
					$Name->last = $BillingLastName;


			/* Address */
				$Address = new stdClass();
					$Address->street = $Address1;
					$Address->street2 = $Address2;
					$Address->city = $City;
					$Address->state = $State;
					$Address->zip = $ZIP;
					$Address->country = $Country;

			/* Actual Payload - You'd setup your Field Key's and Variables here! */
			$Payload = new stdClass();

				$Payload->field_1 = $Name;
				$Payload->field_2 = $Address;
				$Payload->field_3 = $Date;
				$Payload->field_3 = $DonationTotal;
				$Payload->field_5 = ucfirst($DonationObject->status);
				$Payload->field_6 = $GatewayTransactionID;

			/* Send! */
                $RequestURL = 'https://api.knack.com/v1/objects/object_' . KNACK_DONATIONS_OBJECT_ID_GWPKN . '/records/';
                $Record = $Knack->CreateUpdateRecord($RequestURL, $Payload, "POST");      

            /* Check and return */
            	if($Record['id'] != ""){
            		echo "1";
            	} else {
            		echo "0";
            	}
		    
		    /* Close WP */
		    wp_die();
		}
		add_action( 'wp_ajax_sync_knack_givewp_ajax', __NAMESPACE__ . '\\sync_knack_givewp_ajax' );