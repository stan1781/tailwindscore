<?php
/**
 * Predictive search results panel.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$results     = is_array( $args['results'] ?? null ) ? $args['results'] : array();
$products    = is_array( $results['products'] ?? null ) ? $results['products'] : array();
$categories  = is_array( $results['categories'] ?? null ) ? $results['categories'] : array();
$suggestions = is_array( $results['suggestions'] ?? null ) ? $results['suggestions'] : array();
$query       = (string) ( $args['query'] ?? $results['query'] ?? '' );
$has_results = ! empty( $products ) || ! empty( $categories );
$empty_copy  = tailwindscore_feedback_empty_state_copy( 'search-results' );
$search_copy = tailwindscore_search_surface_copy();
?>
<div class="ts-predictive-results<?php echo $has_results ? ' has-results' : ' is-empty'; ?>" data-predictive-results-panel>
	<?php if ( $has_results ) : ?>
		<div class="ts-predictive-results__grid">
			<?php if ( ! empty( $products ) ) : ?>
				<section class="ts-predictive-results__group" aria-labelledby="ts-predictive-products">
					<h3 id="ts-predictive-products" class="ts-predictive-results__title"><?php esc_html_e( 'Pieces', 'tailwindscore' ); ?></h3>
					<ul class="ts-predictive-results__list">
						<?php foreach ( $products as $item ) : ?>
							<li class="ts-predictive-results__item">
								<a class="ts-predictive-results__link" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>">
									<span class="ts-predictive-results__media">
										<?php if ( '' !== (string) ( $item['image'] ?? '' ) ) : ?>
											<img src="<?php echo esc_url( (string) $item['image'] ); ?>" alt="" loading="lazy">
										<?php else : ?>
											<span class="ts-predictive-results__placeholder" aria-hidden="true"><?php echo tailwindscore_icon( 'search', array( 'class' => 'ts-icon--sm' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
										<?php endif; ?>
									</span>
									<span class="ts-predictive-results__content">
										<?php if ( '' !== (string) ( $item['category'] ?? '' ) ) : ?>
											<span class="ts-predictive-results__eyebrow"><?php echo esc_html( (string) $item['category'] ); ?></span>
										<?php endif; ?>
										<span class="ts-predictive-results__name"><?php echo esc_html( (string) ( $item['title'] ?? '' ) ); ?></span>
										<?php if ( '' !== (string) ( $item['price_html'] ?? '' ) ) : ?>
											<span class="ts-predictive-results__price">
												<?php
												tailwindscore_component(
													'price',
													array(
														'price_html' => (string) $item['price_html'],
													)
												);
												?>
											</span>
										<?php else : ?>
											<span class="ts-predictive-results__meta"><?php echo esc_html( (string) ( $item['type'] ?? '' ) ); ?></span>
										<?php endif; ?>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if ( ! empty( $categories ) || ! empty( $suggestions ) ) : ?>
				<section class="ts-predictive-results__group ts-predictive-results__group--editorial" aria-labelledby="ts-predictive-discovery">
					<h3 id="ts-predictive-discovery" class="ts-predictive-results__title"><?php esc_html_e( 'Collections', 'tailwindscore' ); ?></h3>

					<?php if ( ! empty( $categories ) ) : ?>
						<div class="ts-predictive-results__editorial-group">
							<p class="ts-predictive-results__section-label"><?php esc_html_e( 'Browse collections', 'tailwindscore' ); ?></p>
							<ul class="ts-predictive-results__editorial-list">
								<?php foreach ( $categories as $item ) : ?>
									<li>
										<a class="ts-predictive-results__editorial-link" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>">
											<span class="ts-predictive-results__editorial-copy">
												<span class="ts-predictive-results__meta"><?php echo esc_html( (string) ( $item['type'] ?? '' ) ); ?></span>
												<span class="ts-predictive-results__editorial-title"><?php echo esc_html( (string) ( $item['title'] ?? '' ) ); ?></span>
											</span>
											<span class="ts-predictive-results__count">
												<?php
												echo esc_html(
													sprintf(
														/* translators: %s: number of items. */
														_n( '%s item', '%s items', (int) ( $item['count'] ?? 0 ), 'tailwindscore' ),
														number_format_i18n( (int) ( $item['count'] ?? 0 ) )
													)
												);
												?>
											</span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $suggestions ) ) : ?>
						<div class="ts-predictive-results__editorial-group">
							<p class="ts-predictive-results__section-label"><?php esc_html_e( 'Suggested paths', 'tailwindscore' ); ?></p>
							<ul class="ts-predictive-results__stack ts-predictive-results__stack--queries">
								<?php foreach ( $suggestions as $item ) : ?>
									<li>
										<a class="ts-predictive-results__suggestion" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>">
											<span class="ts-predictive-results__suggestion-title"><?php echo esc_html( (string) ( $item['label'] ?? '' ) ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</section>
			<?php endif; ?>
		</div>
	<?php else : ?>
		<div class="ts-predictive-results__empty-state">
			<p class="ts-predictive-results__eyebrow"><?php echo esc_html( $empty_copy['eyebrow'] ?? '' ); ?></p>
			<h3 class="ts-predictive-results__title"><?php echo esc_html( $empty_copy['title'] ?? '' ); ?></h3>
			<p class="ts-predictive-results__message"><?php echo esc_html( (string) ( $search_copy['predictive_empty_message'] ?? ( $empty_copy['message'] ?? '' ) ) ); ?></p>
			<a class="ts-predictive-results__search-link" href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( $query ) . '&post_type=product' ) ); ?>">
				<?php esc_html_e( 'See all results', 'tailwindscore' ); ?>
			</a>
		</div>
	<?php endif; ?>
</div>
