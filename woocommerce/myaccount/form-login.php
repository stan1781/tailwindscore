<?php
/**
 * Premium account login / register surface.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$account_intro        = tailwindscore_account_surface_text( 'account-login-reassurance-message' );
$support_message      = tailwindscore_account_surface_text( 'support-message' );
?>
<section class="ts-account-auth" data-ts-module="account-focus">
	<header class="ts-account-auth__header">
		<p class="ts-account-auth__eyebrow"><?php esc_html_e( 'Customer account', 'tailwindscore' ); ?></p>
		<h1 class="ts-account-auth__title" data-account-focus-target><?php esc_html_e( 'Sign in', 'tailwindscore' ); ?></h1>
		<p class="ts-account-auth__intro"><?php echo esc_html( $account_intro ); ?></p>
	</header>

	<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

	<div class="ts-account-auth__grid<?php echo $registration_enabled ? ' has-register' : ''; ?>">
		<section class="ts-account-panel ts-account-auth__panel">
			<h2 class="ts-account-form__title"><?php esc_html_e( 'Returning customer', 'tailwindscore' ); ?></h2>
			<form class="woocommerce-form woocommerce-form-login login ts-account-form" method="post">
				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label class="ts-label" for="username"><?php esc_html_e( 'Email or username', 'tailwindscore' ); ?></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text ts-input" name="username" id="username" autocomplete="username" value="<?php echo ! empty( $_POST['username'] ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label class="ts-label" for="password"><?php esc_html_e( 'Password', 'tailwindscore' ); ?></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text ts-input" type="password" name="password" id="password" autocomplete="current-password" />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<p class="ts-account-auth__remember">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme ts-choice">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox ts-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<span class="ts-choice__label"><?php esc_html_e( 'Keep me signed in', 'tailwindscore' ); ?></span>
					</label>
				</p>

				<p class="ts-account-form__actions">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit ts-btn ts-btn--primary" name="login" value="<?php esc_attr_e( 'Sign in', 'tailwindscore' ); ?>">
						<?php esc_html_e( 'Sign in', 'tailwindscore' ); ?>
					</button>
					<a class="ts-account-auth__link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'tailwindscore' ); ?></a>
				</p>

				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</form>
		</section>

		<?php if ( $registration_enabled ) : ?>
			<section class="ts-account-panel ts-account-auth__panel">
				<h2 class="ts-account-form__title"><?php esc_html_e( 'Create account', 'tailwindscore' ); ?></h2>
				<form method="post" class="woocommerce-form woocommerce-form-register register ts-account-form"<?php do_action( 'woocommerce_register_form_tag' ); ?>>
					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="ts-label" for="reg_username"><?php esc_html_e( 'Username', 'tailwindscore' ); ?></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text ts-input" name="username" id="reg_username" autocomplete="username" value="<?php echo ! empty( $_POST['username'] ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
						</p>
					<?php endif; ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label class="ts-label" for="reg_email"><?php esc_html_e( 'Email address', 'tailwindscore' ); ?></label>
						<input type="email" class="woocommerce-Input woocommerce-Input--text input-text ts-input" name="email" id="reg_email" autocomplete="email" value="<?php echo ! empty( $_POST['email'] ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
					</p>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="ts-label" for="reg_password"><?php esc_html_e( 'Password', 'tailwindscore' ); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text ts-input" name="password" id="reg_password" autocomplete="new-password" />
						</p>
					<?php else : ?>
						<p class="ts-account-auth__support"><?php echo esc_html( '' !== trim( $support_message ) ? $support_message : __( 'A secure password will be prepared and sent after registration.', 'tailwindscore' ) ); ?></p>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<p class="ts-account-form__actions">
						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" class="woocommerce-Button woocommerce-button button ts-btn ts-btn--secondary" name="register" value="<?php esc_attr_e( 'Create account', 'tailwindscore' ); ?>">
							<?php esc_html_e( 'Create account', 'tailwindscore' ); ?>
						</button>
					</p>

					<?php do_action( 'woocommerce_register_form_end' ); ?>
				</form>
			</section>
		<?php endif; ?>
	</div>

	<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
</section>
