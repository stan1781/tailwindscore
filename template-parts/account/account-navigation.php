<?php
/**
 * Account navigation.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$items            = isset( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();
$current_endpoint = isset( $args['current_endpoint'] ) && is_string( $args['current_endpoint'] ) ? $args['current_endpoint'] : '';
$nav_id           = isset( $args['nav_id'] ) && is_string( $args['nav_id'] ) ? $args['nav_id'] : 'ts-account-nav-panel';
?>
<div class="ts-account-nav" data-ts-module="account-navigation" data-account-nav-id="<?php echo esc_attr( $nav_id ); ?>">
	<button
		type="button"
		class="ts-account-nav__toggle"
		aria-expanded="false"
		aria-controls="<?php echo esc_attr( $nav_id ); ?>"
		data-account-nav-toggle
	>
		<span class="ts-account-nav__toggle-label"><?php esc_html_e( 'Browse account', 'tailwindscore' ); ?></span>
	</button>

	<nav class="ts-account-nav__panel" id="<?php echo esc_attr( $nav_id ); ?>" aria-label="<?php esc_attr_e( 'Account navigation', 'tailwindscore' ); ?>" hidden>
		<ul class="ts-account-nav__list">
			<?php foreach ( $items as $endpoint => $label ) : ?>
				<?php
				$endpoint = is_string( $endpoint ) ? $endpoint : '';
				$label    = is_string( $label ) ? $label : '';

				if ( '' === $endpoint || '' === $label ) {
					continue;
				}

				$url = wc_get_account_endpoint_url( $endpoint );
				if ( 'dashboard' === $endpoint ) {
					$url = wc_get_page_permalink( 'myaccount' );
				}

				$is_current = $current_endpoint === $endpoint || ( 'dashboard' === $endpoint && '' === $current_endpoint );
				?>
				<li class="ts-account-nav__item">
					<a class="ts-account-nav__link<?php echo $is_current ? ' is-current' : ''; ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $is_current ? ' aria-current="page"' : ''; ?>>
						<?php echo esc_html( $label ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
</div>
