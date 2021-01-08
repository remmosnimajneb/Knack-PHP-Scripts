# Knack WooCommerce Plugin

- Project: Knack WooCommerce Plugin
- Code Version: 1.1
- Knack API Version: 1.0
- Author: Benjamin Sommer
- GitHub: https://github.com/remmosnimajneb

## Overview

Continuing with the rest of this repository, this is a WordPress Plugin (intended to be installed as a standard WordPress Plugin) that integrates with WooCommerce and ACF (Advanced Custom Fields) to allow users to sync their Products and Orders with Knack.

Note: This plugin *requires* WooCommerce and Advanced Custom Fields (Free or Pro) to be installed and activated to work.

The plugin adds a options box on the WooCommerce Product Edit page and WooCommerce Order Details page and gives buttons for users to sync their Products and Orders with Knack.

Note: This is not built as a "plug and play" but requires configuration (and knowledge of basic coding) to setup properly, if your are looking for a "plug and play" plugin - this ain't it.

## Let's get into some details

### Knack Assumptions
There are a few assumptions being made here:
1. Knack is setup with a "Products" Object and an "Orders" object and a connection from the Orders to the Products.
2. Each Order consists of multiple Line items (I.E. each line item in WC = a singular record in Knack).
3. The purchaser is not assumed to be an object but rather simply a name field for name inside the Orders object.

In short, we're assuming your Knack setup is like WooCommerce, Products, Orders, each item is a line item in the Orders Object and that's how we'll be syncing.

### Technical note, (it's important!)
WordPress and ACF seem to have a weird thing that when you try and set an ACF field for the first time on a post, it will set it, but THEN something afterwards clears it. It's really weird and I spent tons of time on it to no avail.
Hence, in order to set the Knack ID for the Post, we need to call an EXTERAL PHP script and via that script open Wordpress and set the field, otherwise it simply won't work.

Hence, Products have TWO places where they sync:
1. /Includes/SyncProductWC.php which is the external script that CREATES (and Updates in case the user messed up) a product in Knack and sets the ACF field.
2. /Includes/WCSync.php which has a handler for the update button on the post and UPDATES ONLY, if you update without setting the field via the external script first, IT WILL NOT WORK!

P.S. Anyone who knows WP or ACF better than I do and wants to look into this bug or tell me where I might look I'd love to hear!


### Technical details for Knack
So let's go through a bit of detail.

1. Products:
Products are setup (as stated above) via the script and the handler for updates.

2. Line Items are handled via the script in Includes/WCSync.php when the order is set to completed, refunded or canceled.
There is NO Updating of orders built into this Plugin, although, it can be easily implemented by sending the WC Order ID to Knack and then before each Update, check for a record using the Order ID and Product ID and if you have a match update that record specifically.

## Setup Part 1 - Basic Config

The only file you should need for basic setup is Config/KnackConfig.php.
1. Add your Application ID and API Key's (Line 19 and 20) - [Not sure what these are? Might not be the plugin for you. Not sure where to get these? See: https://docs.knack.com/docs/api-key-app-id]
2. Add the Object ID's for the Products and Order Items into lines 22 and 23. (If you wanted to change these to View based requests you'll need to run through the plugin to look for any $RequestURL variables and change them yourself)

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


Where:
$PayloadName is given to you in the script.
field_## is the field number from Knack (See: https://docs.knack.com/docs/working-with-fields)
$Variable is anything, it can be string "Via WooCommerce" or a number or a Object Variable ($Product->get_name();)

For Products and Orders, all the WC Objects are setup and brought in for you, it's simply up to you to map the right data your looking for into the right fields.


1. Products
Products Payload (function Setup_Product_Payloads($ProductID) line 45) has 2 payloads, NewProductPayload and UpdateProductPayload, one for creating a new product and one for updating.
The variable setup for Product info is $Product which is a WC Product Object.
The Knack ID Variable (from ACF) is $KnackProductID (IF it's been set).

2. Orders
Orders has a singular payload for New (as there is no update).
It brings in:
$Order - the WC Order Object
$OrderData - The WC OrderData Object
$Item - The specific WC Item Object
and The Knack ID Variable (from ACF) is $KnackProductID (IF it's been set).

## Ok, that's it.
Remmember, it's not going to be plug and play, but the sky is yours how far you want to take this.

### Changelog
Version 1.0 - Initial Commit
Version 1.1 - Added Knack-PHP Wrapper V1.1, Included proper NameSpace