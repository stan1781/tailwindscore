<?php
/**
 * TailwindScore reset password form.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$recovery = tailwindscore_account_recovery_copy();
$account_copy = tailwindscore_account_template_copy();
?>
<section class="ts-account-auth" data-ts-module="account-focus">
	<header class="ts-account-auth__header">
		<p class="ts-account-auth__eyebrow"><?php esc_html_e( 'Account access', 'tailwindscore' ); ?></p>
		<h1 class="ts-account-auth__title" data-account-focus-target><?php esc_html_e( 'Choose a new password', 'tailwindscore' ); ?></h1>
		<p class="ts-account-auth__intro"><?php echo esc_html( $recovery['reset'] ); ?></p>
	</header>

	<div class="ts-account-auth__grid">
		<section class="ts-account-panel ts-account-auth__panel">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password ts-account-form">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label class="ts-label" for="password_1"><?php echo esc_html( $account_copy['reset_new_password_label'] ); ?></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text ts-input" type="password" name="password_1" id="password_1" autocomplete="new-password" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label class="ts-label" for="password_2"><?php echo esc_html( $account_copy['reset_confirm_label'] ); ?></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text ts-input" type="password" name="password_2" id="password_2" autocomplete="new-password" />
				</p>
				<p class="ts-account-auth__support"><?php echo esc_html( $recovery['support'] ); ?></p>
				<?php do_action( 'woocommerce_resetpassword_form' ); ?>
				<p class="ts-account-form__actions">
					<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ?? $key ?? '' ); ?>" />
					<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ?? $login ?? '' ); ?>" />
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="ts-btn ts-btn--primary" value="<?php echo esc_attr( $account_copy['reset_submit_label'] ); ?>">
						<?php echo esc_html( $account_copy['reset_submit_label'] ); ?>
					</button>
				</p>
				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
			</form>
		</section>
	</div>
</section>
