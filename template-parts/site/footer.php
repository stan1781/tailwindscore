<?php
/**
 * Global footer shell.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$newsletter_html = tailwindscore_site_shell_capture_action( 'tailwindscore/site_shell/footer_newsletter' );
$social_html     = tailwindscore_site_shell_capture_action( 'tailwindscore/site_shell/footer_social' );
?>
<footer class="ts-site-footer" aria-labelledby="ts-site-footer-title">
	<div class="ts-site-footer__inner ts-container">
		<div class="ts-site-footer__grid">
			<section class="ts-site-footer__brand" aria-labelledby="ts-site-footer-title">
				<h2 id="ts-site-footer-title" class="ts-site-footer__heading">
					<?php echo esc_html( tailwindscore_site_shell_brand_label() ); ?>
				</h2>
				<p class="ts-site-footer__summary">
					<?php echo esc_html( tailwindscore_site_shell_footer_summary() ); ?>
				</p>
			</section>

			<section class="ts-site-footer__group" aria-labelledby="ts-site-footer-commerce">
				<h3 id="ts-site-footer-commerce" class="ts-site-footer__heading"><?php esc_html_e( 'Commerce', 'tailwindscore' ); ?></h3>
				<?php
				tailwindscore_site_shell_render_menu(
					array(
						'location'    => 'footer',
						'label'       => __( 'Commerce footer links', 'tailwindscore' ),
						'menu_id'     => 'ts-footer-commerce',
						'menu_class'  => 'ts-site-footer__menu',
						'nav_class'   => 'ts-site-footer__nav',
						'nav_context' => 'footer',
						'depth'       => 1,
					)
				);
				?>
			</section>

			<section class="ts-site-footer__group" aria-labelledby="ts-site-footer-support">
				<h3 id="ts-site-footer-support" class="ts-site-footer__heading"><?php esc_html_e( 'Support', 'tailwindscore' ); ?></h3>
				<?php
				tailwindscore_site_shell_render_menu(
					array(
						'location'    => 'footer_support',
						'label'       => __( 'Support footer links', 'tailwindscore' ),
						'menu_id'     => 'ts-footer-support',
						'menu_class'  => 'ts-site-footer__menu',
						'nav_class'   => 'ts-site-footer__nav',
						'nav_context' => 'footer',
						'depth'       => 1,
					)
				);
				?>
				<?php if ( '' !== trim( tailwindscore_site_shell_support_message() ) ) : ?>
					<p class="ts-site-footer__summary"><?php echo esc_html( tailwindscore_site_shell_support_message() ); ?></p>
				<?php endif; ?>
			</section>

			<section class="ts-site-footer__group" aria-labelledby="ts-site-footer-editorial">
				<h3 id="ts-site-footer-editorial" class="ts-site-footer__heading"><?php esc_html_e( 'Editorial', 'tailwindscore' ); ?></h3>
				<?php
				tailwindscore_site_shell_render_menu(
					array(
						'location'    => 'footer_editorial',
						'label'       => __( 'Editorial footer links', 'tailwindscore' ),
						'menu_id'     => 'ts-footer-editorial',
						'menu_class'  => 'ts-site-footer__menu',
						'nav_class'   => 'ts-site-footer__nav',
						'nav_context' => 'footer',
						'depth'       => 1,
					)
				);
				?>
			</section>

			<?php if ( '' !== $newsletter_html ) : ?>
				<section class="ts-site-footer__group ts-site-footer__group--newsletter" aria-labelledby="ts-site-footer-newsletter">
					<h3 id="ts-site-footer-newsletter" class="ts-site-footer__heading"><?php esc_html_e( 'Newsletter', 'tailwindscore' ); ?></h3>
					<div class="ts-site-footer__slot">
						<?php echo $newsletter_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</section>
			<?php endif; ?>
		</div>

		<div class="ts-site-footer__bottom">
			<p class="ts-site-footer__legal">
				<?php
				echo esc_html(
					tailwindscore_site_shell_footer_legal_text()
				);
				?>
			</p>

			<?php if ( '' !== $social_html ) : ?>
				<div class="ts-site-footer__social">
					<?php echo $social_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php elseif ( has_nav_menu( 'footer_social' ) ) : ?>
				<?php
				tailwindscore_site_shell_render_menu(
					array(
						'location'    => 'footer_social',
						'label'       => __( 'Social links', 'tailwindscore' ),
						'menu_id'     => 'ts-footer-social',
						'menu_class'  => 'ts-site-footer__menu ts-site-footer__menu--social',
						'nav_class'   => 'ts-site-footer__nav ts-site-footer__nav--social',
						'nav_context' => 'footer',
						'depth'       => 1,
					)
				);
				?>
			<?php else : ?>
				<?php tailwindscore_site_shell_render_social_links(); ?>
			<?php endif; ?>
		</div>
	</div>
</footer>
