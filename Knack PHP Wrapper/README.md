# Knack PHP Wrapper

- Project: Knack PHP Wrapper
- Code Version: 1.0
- Knack API Version: 1.0
- Author: Benjamin Sommer
- GitHub: https://github.com/remmosnimajneb

## Overview

So our first code to look at is the Knack Wrapper. It allows you to authenticate and then create either Record Requests or Create/Update new records.

The code is MOSTLY just cURL requests but whatever.

The code is pretty documented:

	// Include Wrapper
		include('Knack.Class.php');

	// Init Knack
		$Knack = new Knack();

	// Set Creds
		$Knack->SetCredentials('ApplicationID', 'APIKey');

	// Object Based GET
		$RequestURL = 'https://api.knack.com/v1/objects/object_1/records';
		$Records = $Knack->GetRecords($RequestURL);

	// Object Based GET with Filters
		$Filters = '{"match":"or","rules":[{"field":"field_1","operator":"is","value":"' . $SomeVar . '"}]}';
		$RequestURL = 'https://api.knack.com/v1/objects/object_1/records?filters=' . urlencode($Filters) . '&rows_per_page=1000';
		$Records = $Knack->GetRecords($RequestURL);

	// Object Based Create/Update
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

More samples are inside the Tests.php file so see that for more details.