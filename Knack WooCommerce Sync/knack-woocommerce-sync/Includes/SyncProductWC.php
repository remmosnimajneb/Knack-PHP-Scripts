<?php
/********************************
* Project: Knack WooCommerce Plugin
* Description: Sync's WooCommerce Products and Orders with Knack
* Code Version: 1.1
* Knack API Version: 1.0
* Author: Benjamin Sommer - @remmosnimajneb
* See More: https://github.com/remmosnimajneb/Knack-PHP
***************************************************************************************/

  	/*
  	* Sync a new Product to Knack
  	*
	* Read the README.md first, but in short, since we can't update ACF *FROM* WP, we MUST have the Product ID already set.
	* Assuming that's true, we're going to ASSUME an update, since we can't do much else
	*/

	/* 0. Set the NameSpace properly */
		namespace Knack_PHP\WooCommerce_Sync;

	/* 1. Since we are outside WP, we need to load WP */

	define('WP_USE_THEMES', false);
	require( '../../../../wp-load.php' );	// I agree it's bad, but it works.

	/* 2. Now check the User is logged in and has Editing Abilities, and ID is set */
	if( !is_user_logged_in() || !current_user_can('edit_posts') || (empty($_GET['ID']) || $_GET['ID'] == "")){
		echo "Bad Request!";
		die();
	}

	/* 3. Setup the Payloads */
        Setup_Product_Payloads( $_GET['ID'] );

	/* 4. Check if Product has an ID */
	if(!empty(get_field("knack_product_id", $_GET['ID']))){

		/* Update the Product */

	        /* Setup the Payload and Update */
	            $RequestURL = 'https://api.knack.com/v1/objects/object_' . KNACK_PRODUCTS_OBJECT_ID . '/records/' . get_field( 'knack_product_id', $_GET['ID'] );
	            $Knack->CreateUpdateRecord($RequestURL, $UpdateProductPayload, "PUT");     

	} else {

		/* Add it new */

			/* Setup the Payload and Add */
	            $RequestURL = 'https://api.knack.com/v1/objects/object_' . KNACK_PRODUCTS_OBJECT_ID . '/records/';
	            $Record = $Knack->CreateUpdateRecord($RequestURL, $NewProductPayload, "POST");   

	        /*Now we need to set the field for the Knack ID */
				update_field("knack_product_id", $Record["id"], $_GET['ID']);   
	}

	/* Now redirect back to WC */
		header('Location: ' . get_site_url() . '/wp-admin/post.php?post=' . $_GET['ID'] . '&action=edit');