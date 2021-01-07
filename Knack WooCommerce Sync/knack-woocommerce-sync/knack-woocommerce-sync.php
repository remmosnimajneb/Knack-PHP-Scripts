<?php
/*
  Plugin Name: Knack WooCommerce Plugin
  Plugin URI: https://github.com/remmosnimajneb/Knack-PHP
  Description: Sync's WooCommerce Products and Orders with Knack
  Version: 1.0
  Author: Benjamin Sommer 
  Author URI: https://github.com/remmosnimajneb/
*/

	/* Make sure we can't call directly */
  		if ( !defined( 'ABSPATH' ) ) {
	  		exit;
		}

	/* Include the Knack Wrapper Class */
		require_once('Includes/KnackPHPWrapper/Knack.Class.php');

	/* Include the Configurations */
		require_once('Config/KnackConfig.php');		// Knack Config
		require_once('Config/WCConfig.php');		// WooCommerce Config
		require_once('Config/RegisterACFFields.php');

	/* Finally, Include Sync */
		require_once('Includes/WCSync.php');
