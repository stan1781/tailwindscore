<?php
/**
 * TailwindScore lost password form.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$recovery = tailwindscore_account_recovery_copy();
?>
<section class="ts-account-auth" data-ts-module="account-focus">
	<header class="ts-account-auth__header">
		<p class="ts-account-auth__eyebrow"><?php esc_html_e( 'Account access', 'tailwindscore' ); ?></p>
		<h1 class="ts-account-auth__title" data-account-focus-target><?php esc_html_e( 'Reset password', 'tailwindscore' ); ?></h1>
		<p class="ts-account-auth__intro"><?php echo esc_html( $recovery['intro'] ); ?></p>
	</header>

	<div class="ts-account-auth__grid">
		<section class="ts-account-panel ts-account-auth__panel">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password ts-account-form">
				<p class="ts-account-auth__caption"><?php echo esc_html( $recovery['caption'] ); ?></p>
				<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
					<label class="ts-label" for="user_login"><?php esc_html_e( 'Email or username', 'tailwindscore' ); ?></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text ts-input" type="text" name="user_login" id="user_login" autocomplete="username" />
				</p>
				<div class="ts-account-auth__footer">
					<?php do_action( 'woocommerce_lostpassword_form' ); ?>
					<p class="ts-account-form__actions">
						<input type="hidden" name="wc_reset_password" value="true" />
						<button type="submit" class="ts-btn ts-btn--primary" value="<?php esc_attr_e( 'Reset password', 'tailwindscore' ); ?>">
							<?php esc_html_e( 'Send reset link', 'tailwindscore' ); ?>
						</button>
						<a class="ts-account-auth__link" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Back to sign in', 'tailwindscore' ); ?></a>
					</p>
				</div>
				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
			</form>
		</section>
	</div>
</section>
