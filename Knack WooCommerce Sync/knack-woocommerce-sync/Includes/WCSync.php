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
  * Main Sync Functions
  */

    /* 0. Set the NameSpace properly */
        namespace Knack_PHP\WooCommerce_Sync;

    /* 
    * 1. Product Sync 
    * 
    * Due to a weird issue with WP and ACF (See README.md) we need to actually CREATE the product outside of WP handlers, therefore, creating a product in Knack is NOT setup here
    */

        /*
        * Handle updating product if the Update button is clicked.
        */
        function knack_wc_update_product_handler( $ProductID ) {

            global $Knack;
            global $UpdateProductPayload;

            /* 
            * Read the README.md first, but in short, since we can't update ACF *FROM* WP, we MUST have the Product ID already set.
            * Assuming that's true, we're going to ASSUME an update, since we can't do much else
            */

            /* Setup the Payloads */
                Setup_Product_Payloads( $ProductID );

            /* Setup the Payload and Update */
                $RequestURL = 'https://api.knack.com/v1/objects/object_' . KNACK_PRODUCTS_OBJECT_ID . '/records/' . get_field( 'knack_product_id', $ProductID );
                $Record = $Knack->CreateUpdateRecord($RequestURL, $UpdateProductPayload, "PUT");      

        }
        add_action( 'woocommerce_update_product', 'knack_wc_update_product_handler', 10, 1 );

    /* 2. Creating WP Orders */

        /*
        * Handle creating WC Order to Knack.
        *
        * UNLIKE Products, since we aren't re-updating an ACF Field back to WP, we DON'T need to run it outside of WP and can be run inside via an add_action 
        */
        function knack_wc_order_update_handler( $OrderID ) {

            global $Knack;
            global $NewOrderItemPayload;
            global $UpdateOrderItemPayload;

            /* Setup WC Variables */
                $Order = wc_get_order( $OrderID );
                $Items = $Order->get_items();
                $OrderData = $Order->get_data();

            /* Loop through each Line Item */
                foreach ( $Items as $Item ) {

                    /* Setup the Payloads */
                        Setup_Order_Item_Payloads( $OrderID, $OrderData, $Item );

                    /* 
                    * In our code, we don't check if the order has been synced yet, as different people's Knack Schema's may be different.
                    * In the event you wanted to add an update, it's relatively simple, by just running a Get to Knack for an ID where the Product = $ProductID and Order ID = $OrderID and then get the Record ID and Update that specifically
                    */

                    /* Setup the Payload and Update */
                        $RequestURL = 'https://api.knack.com/v1/objects/object_' . KNACK_ORDER_ITEMS_OBJECT_ID . '/records/';
                        $Record = $Knack->CreateUpdateRecord($RequestURL, $CreateOrderItem, "POST");   

                }

        }
        add_action( 'woocommerce_order_status_completed', 'knack_wc_order_update_handler', 10, 1 );
        add_action( 'woocommerce_order_status_cancelled', 'knack_wc_order_update_handler', 10, 1 );
        add_action( 'woocommerce_order_status_refunded', 'knack_wc_order_update_handler', 10, 1 );