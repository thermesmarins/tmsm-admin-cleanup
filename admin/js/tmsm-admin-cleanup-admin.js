/* global tmsm_admin_cleanup_i18n */
jQuery( function ( $ ) {

  if ( 'undefined' === typeof woocommerce_admin ) {
    return;
  }

  // Add buttons to orders screen.
  var $orders_screen = $( '.edit-php.post-type-shop_order' ),
    $title_action   = $orders_screen.find( '.page-title-action:first' ),
    $blankslate     = $orders_screen.find( '.woocommerce-BlankState' );

  if(tmsm_admin_cleanup_i18n.export_orders == true) {
    $title_action.after( '<a href="' + tmsm_admin_cleanup_i18n.urls.export_orders + '" class="page-title-action">' + tmsm_admin_cleanup_i18n.strings.export_orders + '</a>' );
  }

});
