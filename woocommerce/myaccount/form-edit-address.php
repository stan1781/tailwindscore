<?php
/**
 * Premium account addresses.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

if ( empty( $load_address ) ) :
	$addresses = array(
		'billing' => __( 'Billing address', 'tailwindscore' ),
	);

	if ( function_exists( 'wc_shipping_enabled' ) && wc_shipping_enabled() && ! wc_ship_to_billing_address_only() ) {
		$addresses['shipping'] = __( 'Shipping address', 'tailwindscore' );
	}
	?>
	<section class="ts-account-panel ts-account-addresses">
		<div class="ts-account-addresses__grid">
			<?php foreach ( $addresses as $name => $title ) : ?>
				<?php
				$description = wc_get_account_formatted_address( $name );
				tailwindscore_account_part(
					'address-card',
					array(
						'title'        => $title,
						'description'  => $description,
						'action_url'   => wc_get_endpoint_url( 'edit-address', $name ),
						'action_label' => $description ? __( 'Edit address', 'tailwindscore' ) : __( 'Add address', 'tailwindscore' ),
					)
				);
				?>
			<?php endforeach; ?>
		</div>
	</section>
<?php else : ?>
	<?php $address_guidance = tailwindscore_account_surface_text( 'account-address-guidance-message' ); ?>
	<section class="ts-account-panel ts-account-address-form">
		<form method="post" class="ts-account-form">
			<div class="ts-account-form__header">
				<h2 class="ts-account-form__title">
					<?php echo esc_html( wc_edit_address_i18n( $load_address ) ); ?>
				</h2>
				<p class="ts-account-form__intro"><?php echo esc_html( '' !== trim( $address_guidance ) ? $address_guidance : __( 'Use the details below for a smoother return to checkout next time.', 'tailwindscore' ) ); ?></p>
			</div>

			<div class="ts-account-form__grid">
				<?php
				do_action( "woocommerce_before_edit_address_form_{$load_address}" );

				foreach ( $address as $key => $field ) {
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}

				do_action( "woocommerce_after_edit_address_form_{$load_address}" );
				?>
			</div>

			<p class="ts-account-form__actions">
				<button type="submit" class="ts-btn ts-btn--primary" name="save_address" value="<?php esc_attr_e( 'Save address', 'tailwindscore' ); ?>">
					<?php esc_html_e( 'Save address', 'tailwindscore' ); ?>
				</button>
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<input type="hidden" name="action" value="edit_address" />
			</p>
		</form>
	</section>
<?php endif; ?>
