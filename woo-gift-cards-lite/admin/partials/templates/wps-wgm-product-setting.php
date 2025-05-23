<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Product Settings Template
 */
$flag = false;
$current_tab = 'wps_wgm_product_setting';
if ( isset( $_POST['wps_wgm_save_product'] ) ) {
	unset( $_POST['wps_wgm_save_product'] );
	if ( isset( $_REQUEST['wps-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-wgc-nonce'] ) ), 'wps-wgc-nonce' ) ) {
		if ( 'wps_wgm_product_setting' == $current_tab ) {
			$product_settings_array = array();
			$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				if ( isset( $postdata['wps_wgm_from_field'] ) && 'on' === $postdata['wps_wgm_from_field'] ) {
					$postdata['wps_wgm_remove_validation_from'] = 'on';
				}
				if ( isset( $postdata['wps_wgm_message_field'] ) && 'on' === $postdata['wps_wgm_message_field'] ) {
					$postdata['wps_wgm_remove_validation_msg'] = 'on';
				}
				if ( isset( $postdata['wps_wgm_to_email_field'] ) && 'on' === $postdata['wps_wgm_to_email_field'] ) {
					$postdata['wps_wgm_remove_validation_to'] = 'on';
					$postdata['wps_wgm_remove_validation_to_name'] = 'on';
				}
				foreach ( $postdata as $key => $value ) {
					$product_settings_array[ $key ] = $value;
					if ( 'wps_wgm_product_setting_expiry_extension' == $key && 'on' == $value ) {
						$product_id = get_option( 'gc_expiry_extension_product_id' );
						if ( $product_id ) {
							$productdata = wc_get_product( $product_id );
							$productdata->set_catalog_visibility( 'visible' );
							$productdata->save();
						}
					} else {
						$product_id = get_option( 'gc_expiry_extension_product_id' );
						if ( $product_id ) {
							$productdata = wc_get_product( $product_id );
							$productdata->set_catalog_visibility( 'hidden' );
							$productdata->save();
						}
					}
				}
			}
			if ( is_array( $product_settings_array ) && ! empty( $product_settings_array ) ) {
				update_option( 'wps_wgm_product_settings', $product_settings_array );
			}
		}
		$flag = true;
	}
}
require_once WPS_WGC_DIRPATH . 'admin/partials/templates/wps_wgm_settings/wps-wgm-product-settings-array.php';
if ( $flag ) {
	$settings_obj->wps_wgm_settings_saved();
}
?>
<?php $product_settings = get_option( 'wps_wgm_product_settings', array() ); ?>
<h3 class="wps_wgm_overview_heading"><?php esc_html_e( 'Product Settings', 'woo-gift-cards-lite' ); ?></h3>
<div class="wps_wgm_table_wrapper">
    <table class="form-table wps_wgm_product_setting">
        <tbody>
            <?php
				$settings_obj->wps_wgm_generate_common_settings( $wps_wgm_product_settings, $product_settings );
			?>
        </tbody>
    </table>
</div>
<?php
$settings_obj->wps_wgm_save_button_html( 'wps_wgm_save_product' );
?>
<div class="clear"></div>