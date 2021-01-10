/********************************
* Project: Knack GiveWP Sync Plugin
* Description: Sync's GiveWP Donations with Knack
* Code Version: 1.0
* Knack API Version: 1.0
* Author: Benjamin Sommer - @remmosnimajneb
* See More: https://github.com/remmosnimajneb/Knack-PHP
***************************************************************************************/

 	var $ = jQuery.noConflict();
	
	$(document).ready(function() {

		$("#SyncToKnackButton").click(function(){
	        
	        /* Get Donation ID */
	        var DonationID = $(this).attr("data-donation-id");

	        /* Send AJAX Call */
	        $.post('admin-ajax.php', {action: 'sync_knack_givewp_ajax', DonationID: DonationID}, function(response){
	        	
				if(response == 1){

					alert("Donation has been synced successfully!");

				} else {

					alert("Something went wrong with that! Try again or check your configuration.");					

				}

	    	}); 

		});
		
	});