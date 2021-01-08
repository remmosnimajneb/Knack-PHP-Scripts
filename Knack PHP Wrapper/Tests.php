<?php
/********************************
* Project: Knack PHP Wrapper
* Code Version: 1.0
* Knack API Version: 1.0
* Author: Benjamin Sommer - @remmosnimajneb
***************************************************************************************/

	/* Tests */

		/* Include the Class */
			include('Knack.Class.php');

		/* Initiate the Object */
			$Knack = new Knack();

		/* Set ApplicationID and API Key */
			$Knack->SetCredentials('ApplicationID', 'APIKey');
	
		/* Retrieve Request's */

			/* Object Based Retrieve Request */
				$RequestURL = 'https://api.knack.com/v1/objects/object_1/records';
				$Records = $Knack->GetRecords($RequestURL);
					
			/* View Based Retrieve Request */
				$RequestURL = 'https://api.knack.com/v1/pages/scene_1/views/view_1/records';
				$Records = $Knack->GetRecords($RequestURL);

			/* Object based with Filters */
				$Filters = '{"match":"or","rules":[{"field":"field_1","operator":"is","value":"' . $SomeVar . '"}]}';
				$RequestURL = 'https://api.knack.com/v1/objects/object_1/records?filters=' . urlencode($Filters) . '&rows_per_page=1000';
				$Records = $Knack->GetRecords($RequestURL);

			/* Object Based - Retrieve 1 Record */
				$RequestURL = 'https://api.knack.com/v1/objects/object_1/records/XXXXXXXXXXXXXXXXXXXX';
				$Records = $Knack->GetRecords($RequestURL);

			/* View Based - Retrieve 1 Record */
				$RequestURL = 'https://api.knack.com/v1/pages/scene_1/views/view_1/records/XXXXXXXXXXXXXXXXXXXX';
				$Records = $Knack->GetRecords($RequestURL);

		/* Create Requests */

			/* Create new - Object Based */
				$RequestURL = 'https://api.knack.com/v1/objects/object_1/records';

					/* Setup Payload */
						$Payload = new stdClass();

							$Name = new stdClass();
								$Name->title = "Mr";
								$Name->first = "First";
								$Name->middle = "Something";
								$Name->last = "Last";

						$Payload->field_1 = $Name;							// Name
						$Payload->field_2 = "Something@Something.com";		// Email

						$Records = $Knack->CreateUpdateRecord($RequestURL, $Payload, "POST");

			/* Create new - View Based */
				$RequestURL = 'https://api.knack.com/v1/pages/scene_1/views/view_1/records';

					/* Setup Payload */
						$Payload = new stdClass();

							$Name = new stdClass();
								$Name->title = "Mr";
								$Name->first = "First";
								$Name->middle = "Something";
								$Name->last = "Last";

						$Payload->field_1 = $Name;							// Name
						$Payload->field_2 = "Something@Something.com";		// Email

						$Records = $Knack->CreateUpdateRecord($RequestURL, $Payload, "POST");

		/* Update Records */

			/* Update - Object Based */
				$RequestURL = 'https://api.knack.com/v1/objects/object_1/records/XXXXXXXXXXXXXXXXXXXX';

					/* Setup Payload */
						$Payload = new stdClass();

							$Name = new stdClass();
								$Name->title = "Mr";
								$Name->first = "First";
								$Name->middle = "Something";
								$Name->last = "Last";

						$Payload->field_1 = $Name;							// Name
						$Payload->field_2 = "Something@Something.com";		// Email

						$Records = $Knack->CreateUpdateRecord($RequestURL, $Payload, "PUT");

			/* Update - View Based */
				$RequestURL = 'https://api.knack.com/v1/pages/scene_1/views/view_1/records/XXXXXXXXXXXXXXXXXXXX';

					/* Setup Payload */
						$Payload = new stdClass();

							$Name = new stdClass();
								$Name->title = "Mr";
								$Name->first = "First";
								$Name->middle = "Something";
								$Name->last = "Last";

						$Payload->field_1 = $Name;							// Name
						$Payload->field_2 = "Something@Something.com";		// Email

						$Records = $Knack->CreateUpdateRecord($RequestURL, $Payload, "PUT");

		/* Delete Record - Object Based, Can be View based */
			$RequestURL = 'https://api.knack.com/v1/objects/object_1/records/XXXXXXXXXXXXXXXXXXXX';
			$Records = $Knack->DeleteRecord($RequestURL);