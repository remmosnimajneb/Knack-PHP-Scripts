<?php
/********************************
* Project: Knack WooCommerce Plugin
* Description: Sync's WooCommerce Products and Orders with Knack
* Code Version: 1.0
* Knack API Version: 1.0
* Author: Benjamin Sommer - @remmosnimajneb
* See More: https://github.com/remmosnimajneb/Knack-PHP
***************************************************************************************/

  /*
  * Knack Configuration
  */

	/* 1. Set Application and API Keys */

		/* !! You need to edit these! */

  		DEFINE('KNACK_APPLICATION_ID', '');
		DEFINE('KNACK_API_KEY', '');

		DEFINE( 'KNACK_PRODUCTS_OBJECT_ID', 1 );
		DEFINE( 'KNACK_ORDER_ITEMS_OBJECT_ID', 2 );

	/* 2. Setup Knack Class (No Touching!) */
		$Knack = new Knack();
		$Knack->SetCredentials(KNACK_APPLICATION_ID, KNACK_API_KEY);

	/*
	* 3. Setup Knack Payloads 
	* (Hint): These are what you'll want to customize
	*/
		$NewProductPayload = new stdClass();
		$UpdateProductPayload = new stdClass();

		$CreateOrderItem = new stdClass();

		/* 
		* Setup Product Payloads
		* @Param $ProductID (int) WooCommerce Product ID
		* @Return none
		*
		* Note, in this example, New and Update are the same fields, in reality, you can customize so only some fields change on update vs new
		*/
		function Setup_Product_Payloads($ProductID){

			global $NewProductPayload;
			global $UpdateProductPayload;

			/* Load Product */
			$Product = wc_get_product( $ProductID );

			$KnackProductID = get_field( 'knack_product_id', $ProductID );	// Remember, if the product is a New product, this will be empty!

	    	$NewProductPayload->field_1 = $Product->get_name();
			$NewProductPayload->field_2 = $Product->get_description();
			$NewProductPayload->field_3 = $Product->get_price();
			$NewProductPayload->field_4 = wp_get_attachment_url( $Product->get_image_id() );

			$UpdateProductPayload->field_1 = $Product->get_name();
			$UpdateProductPayload->field_2 = $Product->get_description();
			$UpdateProductPayload->field_3 = $Product->get_price();
			$UpdateProductPayload->field_4 = wp_get_attachment_url( $Product->get_image_id() );

		}

		/* 
		* Setup Order Payloads
		* @Param $Order (WC Order Object) WooCommerce Order Object
		* @Param $OrderData (WC OrderData Object) WC OrderData Object
		* @Param $Item (WC Item Object) Item Object from WC Order
		* @Return none
		*
		* Note, in this example, New and Update are the same fields, in reality, you can customize so only some fields change on update vs new
		*/
		function Setup_Order_Item_Payloads($Order, $OrderData, $Item){

			global $CreateOrderItem;

			/* Setup a few Generic Variables */
			$FirstName = ucfirst(strtolower($OrderData['billing']['first_name']));
			$LastName = ucfirst(strtolower($OrderData['billing']['last_name']));
			
			$Address1 = $OrderData['billing']['address_1'];
			$Address2 = $OrderData['billing']['address_2'];
			$City = ucfirst(strtolower($OrderData['billing']['city']));
			$State = $OrderData['billing']['state'];
			$ZIP = $OrderData['billing']['postcode'];
			$Country = $OrderData['billing']['country'];

			$Email = strtolower($OrderData['billing']['email']);
			$Phone = $OrderData['billing']['phone'];

			$CompanyName = $Order->get_billing_company();


			/* Item Variables */
				$ProductName = $Item->get_name();
			    $ProductID = $Item->get_product_id();
			    $KnackProductID = get_field( 'knack_product_id', $ProductID );

			/* Sample Name and Address Objects you can use for Knack */
				$Name = new stdClass();

					$Name->title = "";
					$Name->first = $FirstName;
					$Name->middle = ""; 
					$Name->last = $LastName;

				$Address = new stdClass();

					$Address->street = $Address1;
					$Address->street2 = $Address2;
					$Address->city = $City;
					$Address->state = $State;
					$Address->zip = $ZIP;
					$Address->country = $Country;

			$CreateOrderItem->field_1 = $Name;
			$CreateOrderItem->field_2 = $Address;
			$CreateOrderItem->field_3 = $ProductKnackID;
			$CreateOrderItem->field_4 = $Order->order_date;
			$CreateOrderItem->field_5 = htmlspecialchars($Order->get_customer_note());
			$CreateOrderItem->field_6 = $Item->get_quantity();
			$CreateOrderItem->field_7 = $Item->get_total();
			$CreateOrderItem->field_8 = $OrderData['total'];
			$CreateOrderItem->field_9 = $OrderID;
			$CreateOrderItem->field_10 = ucfirst($Order->get_status());
 
		}