<?php
/**
 * Account layout shell.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'eyebrow'          => '',
	'title'            => '',
	'intro'            => '',
	'navigation_items' => array(),
	'current_endpoint' => '',
	'content_html'     => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );
?>
<section class="ts-account-shell" data-ts-module="account-focus">
	<header class="ts-account-shell__header">
		<?php if ( is_string( $args['eyebrow'] ) && '' !== $args['eyebrow'] ) : ?>
			<p class="ts-account-shell__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1 class="ts-account-shell__title" data-account-focus-target><?php echo esc_html( (string) $args['title'] ); ?></h1>
		<?php if ( is_string( $args['intro'] ) && '' !== $args['intro'] ) : ?>
			<p class="ts-account-shell__intro"><?php echo esc_html( $args['intro'] ); ?></p>
		<?php endif; ?>
	</header>

	<div class="ts-account-shell__body">
		<aside class="ts-account-shell__nav">
			<?php
			tailwindscore_account_part(
				'account-navigation',
				array(
					'items'            => is_array( $args['navigation_items'] ) ? $args['navigation_items'] : array(),
					'current_endpoint' => is_string( $args['current_endpoint'] ) ? $args['current_endpoint'] : '',
					'nav_id'           => 'ts-account-nav-panel',
				)
			);
			?>
		</aside>

		<div class="ts-account-shell__main">
			<?php echo (string) $args['content_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
