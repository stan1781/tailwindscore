<?php
/**
 * Premium account details form.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$user = wp_get_current_user();
?>
<section class="ts-account-panel ts-account-details">
	<form class="ts-account-form" action="" method="post">
		<div class="ts-account-form__header">
			<h2 class="ts-account-form__title"><?php esc_html_e( 'Personal details', 'tailwindscore' ); ?></h2>
			<p class="ts-account-form__intro"><?php esc_html_e( 'Keep your customer profile current without leaving the post-purchase flow.', 'tailwindscore' ); ?></p>
		</div>

		<div class="ts-account-form__grid ts-account-form__grid--two-up">
			<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
				<label class="ts-label" for="account_first_name"><?php esc_html_e( 'First name', 'tailwindscore' ); ?></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text ts-input" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
				<label class="ts-label" for="account_last_name"><?php esc_html_e( 'Last name', 'tailwindscore' ); ?></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text ts-input" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
			</p>
			<div class="clear"></div>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label class="ts-label" for="account_display_name"><?php esc_html_e( 'Display name', 'tailwindscore' ); ?></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text ts-input" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label class="ts-label" for="account_email"><?php esc_html_e( 'Email address', 'tailwindscore' ); ?></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--email input-text ts-input" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
			</p>
		</div>

		<fieldset class="ts-account-form__fieldset">
			<legend class="ts-account-form__legend"><?php esc_html_e( 'Password change', 'tailwindscore' ); ?></legend>
			<div class="ts-account-form__grid">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label class="ts-label" for="password_current"><?php esc_html_e( 'Current password', 'tailwindscore' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text ts-input" name="password_current" id="password_current" autocomplete="off" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label class="ts-label" for="password_1"><?php esc_html_e( 'New password', 'tailwindscore' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text ts-input" name="password_1" id="password_1" autocomplete="off" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label class="ts-label" for="password_2"><?php esc_html_e( 'Confirm new password', 'tailwindscore' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text ts-input" name="password_2" id="password_2" autocomplete="off" />
				</p>
			</div>
		</fieldset>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<p class="ts-account-form__actions">
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="ts-btn ts-btn--primary" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'tailwindscore' ); ?>">
				<?php esc_html_e( 'Save changes', 'tailwindscore' ); ?>
			</button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>
</section>
