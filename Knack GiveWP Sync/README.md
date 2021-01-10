# Knack GiveWP Donation Sync Plugin

- Project: Knack GiveWP Donation Sync Plugin
- Code Version: 1.0
- Knack API Version: 1.0
- Author: Benjamin Sommer
- GitHub: https://github.com/remmosnimajneb

## Overview

This plugin, similar to the WooCommerce and Knack Plugin, allows GiveWP Donations to be synced to Knack.
The plugin adds a button on the GiveWP Donation details page that when clicked will sync the record (new record) to Knack.

Note: This plugin *requires* GiveWP (Free or Pro) to be installed and activated to work (Obviously).

Note: This is not built as a "plug and play" but requires configuration (and knowledge of basic coding) to setup properly, if your are looking for a "plug and play" plugin - this ain't it.

## Setup Part 1 - Basic Config

The only file you should need for basic setup is the actual plugin file knack-givewp-sync.php
1. Add your Application ID and API Key's (Lines 25 and 26) - [Not sure what these are? Might not be the plugin for you. Not sure where to get these? See: https://docs.knack.com/docs/api-key-app-id]
2. Add the Object ID for the record to sync to on line 27. (If you wanted to change these to View based requests you'll need to change that on line 125 below)

## Setup Part 2 - Payload Config

So this is the slightly more complex part, setting up the payloads, since everyone's Knack setup and information they require is different, you'll need to do some of this on your own.

In short, the Payload is the fields you want to send to Knack.
The format across the board is:
$PayloadName->field_## = $Variable;

Most of the fields are straight forward.
If you want to setup a "nested" field in Knack (Like a Name or Address) you should make a new stdClass() and add the inner fields and then add the overall field to the payload:

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

$Payload->field_1 = $Name;
$Payload->field_2 = $Address;

### Payload information
By default, the following objects are already setup for you:
$DonationObject which is the GiveWP Object of the donation info
$DonationInfo which has MOST of the information - including custom meta information.

GiveWP happens to have a very documented API, so we'd recommend reading up on that FIRST and once you've established getting the variables, then it should be straightforward.
(https://givewp.com/documentation/developers/how-to-query-donor-information/)

Updating donations is not setup in this plugin, but with minimal effort can be, simply by storing the DonationID in Knack and then before running the update, Searching Knack 1st for a record and if nothing, then add, otherwise update.

## Ok, that's it.
Remmember, it's not going to be plug and play, but the sky is yours how far you want to take this.