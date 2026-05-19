<?php
/**
 * Global site header.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$is_sticky      = tailwindscore_site_header_is_sticky();
$is_transparent = tailwindscore_site_header_is_transparent( is_front_page() );
$header_classes = array( 'ts-site-header' );

if ( $is_sticky ) {
	$header_classes[] = 'ts-site-header--sticky';
}

if ( $is_transparent ) {
	$header_classes[] = 'ts-site-header--transparent';
}
?>
<header
	class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $header_classes ) ) ); ?>"
	data-ts-module="sticky-header"
	data-sticky="<?php echo $is_sticky ? 'true' : 'false'; ?>"
	data-transparent="<?php echo $is_transparent ? 'true' : 'false'; ?>"
	data-sticky-threshold="12"
>
	<div class="ts-site-header__inner ts-container">
		<div class="ts-site-header__start">
			<button class="ts-site-header__menu-toggle" type="button" data-drawer-toggle="ts-site-mobile-drawer" aria-controls="ts-site-mobile-drawer" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'tailwindscore' ); ?></span>
				<span class="ts-commerce-icon ts-commerce-icon--utility" aria-hidden="true"><?php echo tailwindscore_icon( 'menu', array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</button>

			<a class="ts-site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="ts-site-header__brand-mark"><?php echo esc_html( tailwindscore_site_shell_brand_label() ); ?></span>
			</a>
		</div>

		<div class="ts-site-header__nav-shell">
			<?php get_template_part( 'template-parts/site/navigation' ); ?>
		</div>

		<div class="ts-site-header__utilities" aria-label="<?php esc_attr_e( 'Store utilities', 'tailwindscore' ); ?>">
			<?php
			$utility_start = tailwindscore_site_shell_capture_action( 'tailwindscore/site_shell/header_utilities_start' );
			if ( '' !== $utility_start ) {
				echo $utility_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<?php foreach ( tailwindscore_site_shell_utility_items() as $item ) : ?>
				<?php if ( 'search' === (string) ( $item['key'] ?? '' ) && 'trigger' === (string) ( $item['type'] ?? 'link' ) ) : ?>
					<?php echo tailwindscore_search_render( 'search-trigger', array( 'label' => (string) $item['label'], 'context' => 'utility', 'class' => 'ts-site-header__utility-link', 'icon_class' => 'ts-icon--utility', 'show_label' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php elseif ( 'cart' === (string) ( $item['key'] ?? '' ) && 'trigger' === (string) ( $item['type'] ?? 'link' ) ) : ?>
					<?php echo tailwindscore_cart_surface_render( 'cart-trigger', array( 'label' => (string) $item['label'], 'context' => 'utility', 'class' => 'ts-site-header__utility-link', 'show_label' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<a class="ts-site-header__utility-link" href="<?php echo esc_url( (string) $item['href'] ); ?>">
						<span class="ts-site-header__utility-icon ts-commerce-icon ts-commerce-icon--utility" aria-hidden="true"><?php echo tailwindscore_icon( (string) $item['icon'], array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="ts-site-header__utility-label"><?php echo esc_html( (string) $item['label'] ); ?></span>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php
			$utility_end = tailwindscore_site_shell_capture_action( 'tailwindscore/site_shell/header_utilities_end' );
			if ( '' !== $utility_end ) {
				echo $utility_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
	</div>

	<?php get_template_part( 'template-parts/site/mobile-navigation' ); ?>
</header>
<?php get_template_part( 'template-parts/search/search-overlay' ); ?>
<?php echo tailwindscore_cart_surface_render( 'cart-drawer', tailwindscore_cart_surface_payload() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
