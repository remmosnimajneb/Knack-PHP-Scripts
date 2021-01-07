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
  * WooCommerce Scripts and Handlers
  */

    /*
    * Function to add a Button to "Sync with Knack" for Product Pages
    */
    function knack_wc_button_sync_prod_knack_id(){
        global $post;

        /* If it's a Product only! */
        if(get_post_type() == "product"){
            $HTML  = '<div id="major-publishing-actions" style="overflow:hidden">';
                $HTML .= '<div>';
                    $HTML .= '<i>After Publishing, Click below to sync this product properly with Knack</i><br><br>';
                    $HTML .= '<a href="" id="sync_knack_check" class="button button-primary button-large">Sync Product to Knack</a>';
                $HTML .= '</div>';
            $HTML .= '</div>';
            $HTML .= '
                <script>
                var url = new URL(window.location.href);
                var c = url.searchParams.get("post");
                document.getElementById(\'sync_knack_check\').href="' . get_site_url() . '/wp-content/plugins/knack-woocommerce-sync/Includes/SyncProductWC.php?ID=" + c;
                </script>';
            echo $HTML;
        }
    }
    add_action( 'post_submitbox_start', 'knack_wc_button_sync_prod_knack_id' );