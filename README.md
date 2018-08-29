TMSM Admin Cleanup
=================

Admin cleanup for Thermes Marins de Saint-Malo

Features
-----------

* Renames menu items: "WooCommerce" to "Orders", "Theme Panel" to "Ocean"
* Change menu icons for: WooCommerce, Yoast, WPML, iThemes Security, Gravity Forms, Elementor, Ocean, Hustle
* Roles/Users:
    * Adds "Customers" menu
    * Sort users by registered field
    * Adds new role "Shop Orders Manager"
* Redirect login for role "Shop Manager" and "Shop Orders Manager" to go directly to orders page
* Moves Mailjet, MailChimp, Smushit to submenu of Settings
* Polylang: display languages as post_state with country flag
* WP Rocket: empty cache on save product
* Medias: filter by PDF
* Dashboard: remove boxes
* Content Blocks: allow posts to be translated by Polylang
* Gravity Forms: Label Visibility Settings
* WooCommerce webhook trigger "order.paid"
* WooCommerce PDF Invoices & Packing Slips: 
    * Hide Invoice PDF action when order status is cancelled/pending/failed
    * Change "Invoice" to "Order Receipt"
    * Limit export orders to statuses: completed, processing, processed
    * Customize PDF appearence
    * Remove Action button
* Elementor:
    * Display hint in shortcodes
* ACF:
    * Disable autocomple