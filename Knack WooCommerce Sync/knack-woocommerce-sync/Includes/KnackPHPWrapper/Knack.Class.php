<?php
/********************************
* Project: Knack PHP Wrapper
* Code Version: 1.0
* Knack API Version: 1.0
* Author: Benjamin Sommer - @remmosnimajneb
***************************************************************************************/

Class Knack extends Exception {

	/* Application and API Key's */
	private $ApplicationID;
	private $APIKey;

	/*
	* Function to Set API Key and Application ID.
	* This function is as straightforward as it gets. 
	* If your lost here, I can't help, sorry.
	*
	* If your not sure where to get these keys, see this: https://docs.knack.com/docs/api-key-app-id 
	*
	* @Param (String) $ApplicationID - Knack Application ID
	* @Param (String) $APIKey - Knack API ID
	*/
	function SetCredentials($ApplicationID, $APIKey){

		$this->ApplicationID = $ApplicationID;
		$this->APIKey = $APIKey;

	}

	/*
	* Function to Get Records.
	* This supports both Object and View based requests, as well as single record requests,
	* since Knack format for all of these requests simply change based on RequestURL we can,
	* handle them all with one function.
	*
	* @Param (String) $RequestURL - URL to request on Knack
	* @Return (Array) Array response from Knack.
	*/
	function GetRecords($RequestURL){

		$Request = $this->SendRequest("GetData", $RequestURL, "", "GET");

		return $Request;

	}

	/*
	* Function to Create or Update a Record
	* Similar to Get Records, Knack handles Creating or Updating Records in a similar
	* fashion, we can utilize one function to handle both forms of requests.
	*
	* The only distinction we need to handle is for Create requests utilize POST while 
	* Updating requires a PUT request, so we need to handle that.
	*
	* @Param (String) $RequestURL - URL to request on Knack
	* @Param (Array) $Payload - Array of payload for the record to update or create
	* @Param (String) $RequestMethod - Method of request, POST or PUT
	* @Return (Array) Array response from Knack.
	*/
	function CreateUpdateRecord($RequestURL, $Payload, $RequestMethod){

		$Request = $this->SendRequest("CreateUpdate", $RequestURL, json_encode($Payload), $RequestMethod);

		return $Request;

	}

	/*
	* Actual send request function.
	*
	* Since Knack requests (all requests) are basically all just JSON requests on some level
	* we can utilize a singular function to handle all possible requests.
	*
	* @Param (String) $RequestType - Create/Update or GetRecords
	* @Param (String) $RequestURL - URL to request on Knack
	* @Param (Array) $Payload - Array of payload for the record to update or create
	* @Param (String) $RequestMethod - Method of request, POST or PUT
	* @Return (Array) Array response from Knack.
	*/
	function SendRequest($RequestType, $RequestURL, $Payload, $RequestMethod){

		/* Check API And Application ID set */
		if($this->ApplicationID == "" || $this->APIKey == ""){
			throw new Knack("Application ID or API Key aren't properly set. Please call SetCredentials($ApplicationID, $APIKey) to authenticate first.");
		}

		/* Send Request */
		$CurlRequest = curl_init($RequestURL);

		/* If we need a payload */
			if($RequestType == "CreateUpdate"){
				curl_setopt($CurlRequest, CURLOPT_POSTFIELDS, $Payload);
			}

	    $Headers = [
			    'X-Knack-Application-Id: ' . $this->ApplicationID,
			    'X-Knack-REST-API-Key: ' . $this->APIKey,
			    'Content-Type: application/json',
			    'ignore_errors: true'
			];

		curl_setopt($CurlRequest, CURLOPT_HTTPHEADER, $Headers);					
		curl_setopt($CurlRequest, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($CurlRequest, CURLOPT_CUSTOMREQUEST, $RequestMethod);
			
			$Record = curl_exec($CurlRequest);

		curl_close($CurlRequest);
													
			return json_decode($Record, true);

	}


}