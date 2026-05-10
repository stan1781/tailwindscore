<?php
/**
 * Mobile drawer navigation.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$drawer_id = 'ts-site-mobile-drawer';
?>
<div
	id="<?php echo esc_attr( $drawer_id ); ?>"
	class="ts-mobile-drawer"
	data-ts-module="mobile-drawer"
	data-drawer-id="<?php echo esc_attr( $drawer_id ); ?>"
	hidden
>
	<div class="ts-mobile-drawer__backdrop" data-drawer-close></div>
	<aside class="ts-mobile-drawer__panel" aria-label="<?php esc_attr_e( 'Mobile navigation', 'tailwindscore' ); ?>" aria-modal="true" role="dialog">
		<div class="ts-mobile-drawer__header">
			<a class="ts-mobile-drawer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo esc_html( tailwindscore_site_shell_brand_label() ); ?>
			</a>
			<button class="ts-mobile-drawer__close" type="button" data-drawer-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'tailwindscore' ); ?></span>
				<span class="ts-commerce-icon ts-commerce-icon--utility" aria-hidden="true"><?php echo tailwindscore_icon( 'close', array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</button>
		</div>

		<div class="ts-mobile-drawer__body">
			<?php
			get_template_part(
				'template-parts/site/navigation',
				null,
				array(
					'location'    => 'primary',
					'label'       => __( 'Mobile navigation', 'tailwindscore' ),
					'menu_id'     => 'ts-site-mobile-navigation',
					'menu_class'  => 'ts-nav__list ts-nav__list--mobile',
					'nav_class'   => 'ts-nav ts-nav--mobile',
					'nav_context' => 'mobile',
					'depth'       => 3,
				)
			);
			?>
		</div>

		<div class="ts-mobile-drawer__footer">
			<div class="ts-site-header__utilities ts-site-header__utilities--drawer">
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
			</div>
		</div>
	</aside>
</div>
