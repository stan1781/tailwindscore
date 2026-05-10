<?php
/**
 * Unified archive/search discovery loop shell.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$archive_title = woocommerce_page_title( false );

ob_start();
do_action( 'woocommerce_archive_description' );
$archive_description = trim( (string) ob_get_clean() );
?>
<div class="ts-container">
	<section class="ts-archive-discovery">
		<header class="ts-archive-discovery__header">
			<div class="ts-archive-discovery__intro">
				<p class="ts-archive-discovery__eyebrow"><?php echo esc_html( is_search() ? __( 'Search results', 'tailwindscore' ) : __( 'Collection', 'tailwindscore' ) ); ?></p>
				<h1 class="ts-archive-discovery__title"><?php echo esc_html( $archive_title ); ?></h1>
				<?php if ( '' !== $archive_description ) : ?>
					<div class="ts-archive-discovery__description"><?php echo wp_kses_post( $archive_description ); ?></div>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( woocommerce_product_loop() ) : ?>
			<?php do_action( 'woocommerce_before_shop_loop' ); ?>

			<div class="ts-archive-discovery__toolbar">
				<div class="ts-archive-discovery__result-status">
					<div class="ts-archive-discovery__result-count">
						<?php tailwindscore_render_archive_result_count(); ?>
					</div>
				</div>

				<div class="ts-archive-discovery__sort-group">
					<p class="ts-archive-discovery__sort-label"><?php esc_html_e( 'Sort by', 'tailwindscore' ); ?></p>
					<div class="ts-archive-discovery__sort-control">
						<?php tailwindscore_render_archive_catalog_ordering(); ?>
					</div>
				</div>
			</div>

			<?php
			woocommerce_product_loop_start();

			while ( have_posts() ) {
				the_post();

				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}

			woocommerce_product_loop_end();

			if ( function_exists( 'woocommerce_pagination' ) ) {
				echo '<div class="ts-archive-discovery__pagination">';
				woocommerce_pagination();
				echo '</div>';
			}
			?>
		<?php else : ?>
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		<?php endif; ?>
	</section>
</div>
