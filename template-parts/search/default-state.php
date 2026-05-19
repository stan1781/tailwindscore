<?php
/**
 * Default discovery state for search.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$popular    = is_array( $args['popular'] ?? null ) ? $args['popular'] : tailwindscore_search_popular_terms();
$categories = is_array( $args['categories'] ?? null ) ? $args['categories'] : tailwindscore_search_featured_categories();
$copy       = tailwindscore_search_surface_copy();
?>
<div class="ts-search-default">
	<div class="ts-search-default__intro">
		<p class="ts-search-default__eyebrow"><?php echo esc_html( (string) ( $copy['eyebrow'] ?? '' ) ); ?></p>
		<h3 class="ts-search-default__title"><?php echo esc_html( (string) ( $copy['default_state_title'] ?? '' ) ); ?></h3>
	</div>

	<div class="ts-search-default__grid">
		<div class="ts-search-default__group">
			<h4 class="ts-search-default__heading"><?php echo esc_html( (string) ( $copy['suggested_searches_heading'] ?? '' ) ); ?></h4>
			<div class="ts-search-default__chips">
				<?php foreach ( $popular as $item ) : ?>
					<a class="ts-search-default__chip" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>"><?php echo esc_html( (string) ( $item['label'] ?? '' ) ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( ! empty( $categories ) ) : ?>
			<div class="ts-search-default__group">
				<h4 class="ts-search-default__heading"><?php echo esc_html( (string) ( $copy['browse_collections_heading'] ?? '' ) ); ?></h4>
				<div class="ts-search-default__chips">
					<?php foreach ( $categories as $item ) : ?>
						<a class="ts-search-default__chip ts-search-default__chip--secondary" href="<?php echo esc_url( (string) ( $item['url'] ?? '#' ) ); ?>"><?php echo esc_html( (string) ( $item['label'] ?? '' ) ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="ts-search-default__group ts-search-default__group--recent" data-recent-searches hidden>
			<h4 class="ts-search-default__heading"><?php echo esc_html( (string) ( $copy['recent_searches_heading'] ?? '' ) ); ?></h4>
			<?php if ( '' !== trim( (string) ( $copy['recent_searches_guidance'] ?? '' ) ) ) : ?>
				<p class="ts-search-default__message"><?php echo esc_html( (string) $copy['recent_searches_guidance'] ); ?></p>
			<?php endif; ?>
			<div class="ts-search-default__chips" data-recent-searches-list></div>
		</div>
	</div>
</div>
