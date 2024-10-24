### 1.5.9: October 22th, 2024
* Fix the pdf for woocommerce style.

### 1.5.8: January 24th, 2023
* Prevent Mailjet from upgrading

### 1.5.7: November 30th, 2022
* WooCommerce: customize "To" recipient with billing firstname and lastname along to the email for better email delivrability

### 1.5.6: August 29th, 2022
* Ocean: removed customizer ad
* Ocean: moved menu item from top to above appearance
* Ocean: removed menu in admin top bar

### 1.5.5: June 9th, 2022
* Ocean: removed outdated theme notice
* Ocean: removed ugly menu icon
* WPvivid: removed plugin update notice

### 1.5.4: May 5th, 2022
* Gravity Forms: "Legal Notice" field to form settings
* Functions refactoring

### 1.5.3: April 27th, 2022
* Checkout Field Editor for WooCommerce: remove notices
* Disable updates from "BackUpWordPress" plugin
* Mailjet: fix settings menu

### 1.5.2: January 7th, 2022
* *Revert* Disable `wp_maybe_auto_update` action to prevent `wp_update_plugins` from being triggered after `wp_version_check`

### 1.5.1: January 7th, 2022
* *Revert* Core Updates: disable `wp_version_check` single event creation (forced ttl to 3600 prevented the weekly schedule of the updates)
* Instead disable `wp_maybe_auto_update` action to prevent `wp_update_plugins` from being triggered after `wp_version_check`

### 1.5.0: January 6th, 2022
* Core Updates: disable `wp_version_check` single event creation (forced ttl to 3600 prevented the weekly schedule of the updates)

### 1.4.9: December 10th, 2021
* WooCommerce: remove flickering screen because of spinner
* Post Expirator: move admin menu to submenu of Settings
* Post Expirator: update functions and column names

### 1.4.8: October 25th, 2021
* Disable updates from "Woo In Stock Notifier" plugin
* WooCommerce 5.8: Hide add-ons menus (Marketplace & My Subscriptions)

### 1.4.7: September 9th, 2021
* Rank Math remove pro notice nag

### 1.4.6: July 22nd, 2021
* Prevent Github Updater from updating

### 1.4.5: June 14th, 2021
* Fix redirect to orders only if WooCommerce is enabled

### 1.4.4: June 14th, 2021
* Redirect to orders only if WooCommerce is enabled

### 1.4.3: June 3rd, 2021
* WooCommerce: Going back to original subject/heading for local pickup email

### 1.4.2: June 3rd, 2021
* WooCommerce: Roll Back email subject/heading for local pickup 

### 1.4.1: May 17th, 2021
* WooCommerce Invoice Slips: Keep invoice exist icon difference

### 1.4.0: May 17th, 2021
* Small CSS fix for complete action button
* Gravity Forms sub labels are now thin text not bold
* WooCommerce: Emails titles and headings were not replaced by placeholders

### 1.3.9: May 3rd, 2021
* Fix global $woocommerce must be defined
* WooCommerce: Remove snackbar notices
* WooCommerce: Duplicate product changes the year if last year is present in the name or SKU 

### 1.3.8: May 3rd, 2021
* WooCommerce: Fix Customize order completed and processing customer emails to protect local pickup and virtual informations

### 1.3.7: May 3rd, 2021
* WooCommerce: Customize order completed and processing customer emails to protect local pickup and virtual informations 

### 1.3.6: April 24rd, 2021
* WooCommerce PDF Invoices & Packing Slips: Add "In Preparation" WooCommerce status to allowed Invoice statuses
* WooCommerce PDF Invoices & Packing Slips: New PDF icon
* Shop Order admin screen: Remove unwanted boxes
* Fix styling of processing icon

### 1.3.5: April 23rd, 2021
* WooCommerce: Display again main menu for shop_order_managers
* Complianz: Remove review notice
* BackUpWordPress: Remove review notice
* BackUpWordPress: Fix disk_free_space banned function

### 1.3.4: April 6th, 2021
* WooCommerce: menu icon compatibility for 5.1 version 
* Mailjet: activated again menu order
* Members: hide review notice (fix)
* WooCommerce PDF Invoices & Packing Slips: remove invoice number and invoice date (order info is enough)
* WooCommerce PDF Invoices & Packing Slips: display used coupons in invoice PDF

### 1.3.3: March 11th, 2021
* Changed licence from GPL 2 to GPL 3
* Remove Members upgrade branding and custom icon
* Hide Rank Math Review Notice
* Complianz: reorder menu and remove custom menu icon 
* Admin stylesheet as a LESS file instead of CSS

### 1.3.2: January 12th, 2021
* Remove WP Rocket CDN ad
* Remove WP Rocket preloading notice
* Remove WP Rocket sidebar
* Reorder Rank Math, Jetpack, Hubspot, Gravity Forms admin menus
* Change Members, Mailjet, Product Feed, MailChimp Discount icons in admin menu

### 1.3.1: December 29th, 2020
* Fix Expiration Date had wrong timezone offset

### 1.3.0: December 29th, 2020
* Expire date shown for product in list table 
* Hide post expirator column

### 1.2.0: December 28th, 2020
* Easy Updates Manager expiry notice
* Remove Woo Store Vacation upsell notice

### 1.1.9: October 22nd, 2020
* Remove Members review notice

### 1.1.8: August 5th, 2020
* Remove MailChimp menu override
* Remove Mailjet menu override until access are fixed

### 1.1.7: July 17th, 2020
* Remove La Poste Tracking CSS override
* Force hiding ads

### 1.1.6: November 28st, 2019
* Fix Rank Math menu icon

### 1.1.5: November 21st, 2019
* WooCommerce PDF Invoices & Packing Slips: hide ads 
* Setup interval for redirecting the user to the admin email confirmation screen: disable the redirection

### 1.1.4: November 13th, 2019
* Rank Math menu icon
* Preview Emails for WooCommerce: remove notices

### 1.1.3: April 11th, 2019
* OptinMonster: Move admin menu to submenu of Settings
* Remove CRMPerks menu

### 1.1.2: April 9th, 2019
* Move Kinsta menu to settings
* Hide Kinsta clear cache button in admin bar

### 1.1.1: March 27th, 2019
* WooCommerce: on product admin page, create a button leading to product report
* Fix text size in PDF invoice

### 1.1.0: January 14th, 2018
* Check if Polylang is installed
* Check if query vars are set before testing them
* Elementor ads
* Hustle ads
* Add order count in menu (for shop_order_manager role)
* Remove ACF ads
* Disable Connect your store to WooCommerce.com to receive extensions updates and support admin notice
* Disable WooCommerce reviews dashboard widget
* Disable WooCommerce status dashboard widget
* Remove OptinMonster ads
* Mailjet new version

### 1.0.9: October 11th, 2018
* YoastSEO: do not copy title and metadesc
* Hide Easy Updates Manager ads
* Fix for Elementor template conditions not compatible with Polylang

### 1.0.8: August 29th, 2018
* Move SmushIt to submenu
* ACF Disable Autocomplete
* Change link to Customers admin menu
* Remove admin footer
* Elementor: display hint in shortcodes

### 1.0.7: June 29nd, 2018
* Temporary security fix

### 1.0.6: May 25nd, 2018
* WooCommerce: Webhook trigger "order.paid"
* WooCommerce: action buttons colors
* Dashboard: remove welcome and Ocean news
* WooCommerce PDF Invoices & Packing Slips: Remove Action button
* MailChimp: move admin menu to submenu of settings
 
### 1.0.5: May 14nd, 2018
* Fix PDF title if order number is empty
* Plugin logo
* Github branch name

### 1.0.4: May 2nd, 2018
* WooCommerce PDF Invoices & Packing Slips: 
    * Customize appearence of PDF via CSS
    * Update FR localization

### 1.0.3: April 26th, 2018
* WooCommerce PDF Invoices & Packing Slips: 
    * Hide Invoice PDF action when order status is cancelled/pending/failed
    * Change "Invoice" to "Order Receipt"
    * Limit export orders to statuses: completed, processing, processed

### 1.0.2: April 17th, 2018
* Menu icons refactoring
* Menu icon for Elementor
* Menu icon for OceanWP
* Menu icon for Hustle
* Rename "Theme Panel" from Ocean to "Ocean"

### 1.0.1: April 10th, 2018
* Gravity Forms menu icon
* Scripts in footer

### 1.0.0: April 9th, 2018
* Admin cleanup