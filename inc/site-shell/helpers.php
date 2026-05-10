<?php
/**
 * Site shell helpers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Capture an action slot so templates can decide whether to render a region.
 */
function tailwindscore_site_shell_capture_action( string $hook, array $args = array() ): string {
	ob_start();
	do_action_ref_array( $hook, $args );
	return trim( (string) ob_get_clean() );
}

/**
 * Brand label used across the global shell.
 */
function tailwindscore_site_shell_brand_label(): string {
	return wp_strip_all_tags( get_bloginfo( 'name' ) );
}

/**
 * Utility links rendered in the header shell.
 *
 * @return array<int, array<string, string>>
 */
function tailwindscore_site_shell_utility_items(): array {
	$items = array(
		array(
			'key'   => 'search',
			'label' => __( 'Search', 'tailwindscore' ),
			'href'  => '',
			'icon'  => 'search',
			'type'  => 'trigger',
		),
		array(
			'key'   => 'account',
			'label' => __( 'Account', 'tailwindscore' ),
			'href'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url(),
			'icon'  => 'user',
			'type'  => 'link',
		),
		array(
			'key'   => 'cart',
			'label' => __( 'Cart', 'tailwindscore' ),
			'href'  => '',
			'icon'  => 'bag',
			'type'  => 'trigger',
		),
	);

	/**
	 * Filter site-shell utility items.
	 *
	 * @param array<int, array<string, string>> $items Utility items.
	 */
	return apply_filters( 'tailwindscore/site_shell/utility_items', $items );
}

/**
 * Default message for the announcement bar slot.
 */
function tailwindscore_site_shell_announcement_text(): string {
	$message = function_exists( 'tailwindscore_content_surface_value' )
		? (string) tailwindscore_content_surface_value( 'announcement-bar-message' )
		: __( 'Complimentary shipping on domestic orders over $150.', 'tailwindscore' );

	return (string) apply_filters( 'tailwindscore/site_shell/announcement_text', $message );
}

/**
 * Footer summary surface.
 */
function tailwindscore_site_shell_footer_summary(): string {
	$blog_description = wp_strip_all_tags( (string) get_bloginfo( 'description' ) );

	if ( '' !== $blog_description ) {
		return $blog_description;
	}

	return function_exists( 'tailwindscore_content_surface_value' )
		? (string) tailwindscore_content_surface_value( 'footer-brand-summary' )
		: __( 'Editorial commerce foundation for a calm, premium shopping journey.', 'tailwindscore' );
}

/**
 * Footer legal copy with year interpolation.
 */
function tailwindscore_site_shell_footer_legal_text(): string {
	$template = function_exists( 'tailwindscore_content_surface_value' )
		? (string) tailwindscore_content_surface_value( 'footer-legal-notice' )
		: __( 'Copyright %s. All rights reserved.', 'tailwindscore' );

	return sprintf( $template, wp_date( 'Y' ) );
}

/**
 * Support copy placed alongside support links.
 */
function tailwindscore_site_shell_support_message(): string {
	return function_exists( 'tailwindscore_content_surface_value' )
		? (string) tailwindscore_content_surface_value( 'support-message' )
		: '';
}

/**
 * Structured governed social links.
 *
 * @return array<int, array<string, string>>
 */
function tailwindscore_site_shell_social_links(): array {
	$links = function_exists( 'tailwindscore_content_surface_value' )
		? tailwindscore_content_surface_value( 'footer-social-links' )
		: array();

	return is_array( $links ) ? $links : array();
}

/**
 * Render governed social links when no menu or slot overrides them.
 */
function tailwindscore_site_shell_render_social_links(): void {
	$links = tailwindscore_site_shell_social_links();

	if ( array() === $links ) {
		return;
	}

	?>
	<nav class="ts-site-footer__nav ts-site-footer__nav--social" aria-label="<?php esc_attr_e( 'Social links', 'tailwindscore' ); ?>">
		<ul class="ts-site-footer__menu ts-site-footer__menu--social">
			<?php foreach ( $links as $link ) : ?>
				<li class="menu-item ts-nav__item">
					<a class="ts-nav__link" href="<?php echo esc_url( (string) $link['url'] ); ?>" target="_blank" rel="noreferrer noopener">
						<span class="ts-site-header__utility-icon ts-commerce-icon ts-commerce-icon--utility" aria-hidden="true">
							<?php echo tailwindscore_icon( (string) $link['platform'], array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<span><?php echo esc_html( (string) $link['label'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Structured fallback links for nav/footer foundations.
 *
 * @return array<int, array<string, string>>
 */
function tailwindscore_site_shell_fallback_menu_items( string $location ): array {
	$items = array();

	$items[] = array(
		'label' => __( 'Home', 'tailwindscore' ),
		'url'   => home_url( '/' ),
	);

	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$shop_url = wc_get_page_permalink( 'shop' );
		if ( $shop_url ) {
			$items[] = array(
				'label' => __( 'Shop', 'tailwindscore' ),
				'url'   => $shop_url,
			);
		}
	}

	$posts_page_id = (int) get_option( 'page_for_posts' );
	if ( $posts_page_id > 0 ) {
		$items[] = array(
			'label' => get_the_title( $posts_page_id ),
			'url'   => get_permalink( $posts_page_id ) ?: home_url( '/blog/' ),
		);
	}

	if ( 'footer_support' === $location ) {
		$items = array(
			array(
				'label' => __( 'Contact', 'tailwindscore' ),
				'url'   => home_url( '/contact/' ),
			),
			array(
				'label' => __( 'Shipping', 'tailwindscore' ),
				'url'   => home_url( '/shipping/' ),
			),
			array(
				'label' => __( 'Returns', 'tailwindscore' ),
				'url'   => home_url( '/returns/' ),
			),
		);
	} elseif ( 'footer_editorial' === $location ) {
		$items = array(
			array(
				'label' => __( 'Journal', 'tailwindscore' ),
				'url'   => $posts_page_id > 0 ? ( get_permalink( $posts_page_id ) ?: home_url( '/journal/' ) ) : home_url( '/journal/' ),
			),
			array(
				'label' => __( 'About', 'tailwindscore' ),
				'url'   => home_url( '/about/' ),
			),
		);
	}

	/**
	 * Filter fallback menu items per location.
	 *
	 * @param array<int, array<string, string>> $items    Fallback items.
	 * @param string                            $location Menu location.
	 */
	return apply_filters( 'tailwindscore/site_shell/fallback_menu_items', $items, $location );
}

/**
 * Render a menu with the site-shell walker and a conservative fallback.
 *
 * @param array<string, mixed> $args Render arguments.
 */
function tailwindscore_site_shell_render_menu( array $args ): void {
	$defaults = array(
		'location'    => 'primary',
		'label'       => __( 'Navigation', 'tailwindscore' ),
		'menu_id'     => 'ts-nav-menu',
		'menu_class'  => 'ts-nav__list',
		'depth'       => 3,
		'nav_class'   => 'ts-nav',
		'nav_context' => 'desktop',
	);

	$args = wp_parse_args( $args, $defaults );

	$location    = sanitize_key( (string) $args['location'] );
	$label       = sanitize_text_field( (string) $args['label'] );
	$menu_id     = sanitize_html_class( (string) $args['menu_id'] );
	$menu_class  = sanitize_text_field( (string) $args['menu_class'] );
	$nav_class   = sanitize_text_field( (string) $args['nav_class'] );
	$nav_context = sanitize_html_class( (string) $args['nav_context'] );
	$depth       = max( 1, (int) $args['depth'] );

	?>
	<nav class="<?php echo esc_attr( $nav_class ); ?>" aria-label="<?php echo esc_attr( $label ); ?>" data-ts-module="navigation-focus" data-nav-context="<?php echo esc_attr( $nav_context ); ?>">
		<?php
		if ( has_nav_menu( $location ) ) {
			wp_nav_menu(
				array(
					'theme_location' => $location,
					'container'      => false,
					'menu_id'        => $menu_id,
					'menu_class'     => $menu_class,
					'depth'          => $depth,
					'fallback_cb'    => false,
					'walker'         => new TailwindScore_Site_Shell_Walker(),
					'ts_menu_id'     => $menu_id,
				)
			);
		} else {
			$fallback_items = tailwindscore_site_shell_fallback_menu_items( $location );
			if ( ! empty( $fallback_items ) ) :
				?>
				<ul id="<?php echo esc_attr( $menu_id ); ?>" class="<?php echo esc_attr( $menu_class ); ?>">
					<?php foreach ( $fallback_items as $item ) : ?>
						<li class="menu-item ts-nav__item">
							<a class="ts-nav__link" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>">
								<?php echo esc_html( (string) ( $item['label'] ?? '' ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php
			endif;
		}
		?>
	</nav>
	<?php
}
