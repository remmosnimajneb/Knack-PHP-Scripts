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
	* Register the Knack Product ID Field for the Product Pages
	*/

	/* Set the NameSpace properly */
		namespace Knack_PHP\WooCommerce_Sync;

	if( function_exists('acf_add_local_field_group') ):

	/* Add Field Group */
	acf_add_local_field_group(array(
		'key' => 'knack_woocommerce_product_grp',
		'title' => 'WooCommerce Knack Integrations',
		'fields' => array (),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
				),
			),
		),
	));

	/* Add Field to Group */
	acf_add_local_field(array(
		'key' => 'knack_product_id_key',
		'label' => 'Knack Product ID',
		'name' => 'knack_product_id',
		'type' => 'text',
		'parent' => 'knack_woocommerce_product_grp'
	));

	endif;