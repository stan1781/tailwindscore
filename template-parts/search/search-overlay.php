<?php
/**
 * Global commerce search surface.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$surface_id = 'ts-search-surface';
$endpoint   = tailwindscore_search_endpoint_url();
$copy       = tailwindscore_search_surface_copy();
?>
<div
	id="<?php echo esc_attr( $surface_id ); ?>"
	class="ts-search-surface"
	data-ts-module="search-surface"
	data-search-endpoint="<?php echo esc_url( $endpoint ); ?>"
	data-search-results-host="ts-search-results"
	hidden
>
	<div class="ts-search-surface__backdrop" data-search-close></div>
	<section class="ts-search-surface__panel" role="dialog" aria-modal="true" aria-labelledby="ts-search-surface-title" data-ts-module="search-focus">
		<div class="ts-search-surface__header">
			<div class="ts-search-surface__heading">
				<p class="ts-search-surface__eyebrow"><?php echo esc_html( (string) ( $copy['eyebrow'] ?? '' ) ); ?></p>
				<h2 id="ts-search-surface-title" class="ts-search-surface__title"><?php echo esc_html( (string) ( $copy['title'] ?? '' ) ); ?></h2>
			</div>

			<button class="ts-search-surface__close ts-icon-button ts-icon-button--utility" type="button" data-search-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Close search', 'tailwindscore' ); ?></span>
				<?php echo tailwindscore_icon( 'close', array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>

		<div class="ts-search-surface__body" data-ts-module="predictive-search">
			<form class="ts-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
				<label class="screen-reader-text" for="ts-search-field"><?php esc_html_e( 'Search products', 'tailwindscore' ); ?></label>
				<div class="ts-search-form__field">
					<span class="ts-search-form__icon ts-commerce-icon ts-commerce-icon--utility" aria-hidden="true">
						<?php echo tailwindscore_icon( 'search', array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<input id="ts-search-field" class="ts-search-form__input" type="search" name="s" placeholder="<?php echo esc_attr( (string) ( $copy['overlay_placeholder'] ?? '' ) ); ?>" autocomplete="off" data-search-input>
					<input type="hidden" name="post_type" value="product">
				</div>
			</form>

			<div class="ts-search-surface__content">
				<div class="ts-search-surface__default" data-search-default-state>
					<?php
					echo tailwindscore_search_render(
						'default-state',
						array(
							'popular'    => tailwindscore_search_popular_terms(),
							'categories' => tailwindscore_search_featured_categories(),
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>

				<div id="ts-search-results" class="ts-search-surface__results" data-search-results aria-live="polite" aria-busy="false"></div>
			</div>
		</div>
	</section>

	<template data-search-loading-template>
		<?php
		$loading_copy = tailwindscore_search_feedback_copy( 'loading' );
		tailwindscore_feedback_part(
			'empty-state',
			array(
				'icon_name' => 'search',
				'eyebrow'   => $loading_copy['eyebrow'] ?? '',
				'title'     => $loading_copy['title'] ?? '',
				'message'   => $loading_copy['message'] ?? '',
			)
		);
		?>
	</template>

	<template data-search-unavailable-template>
		<?php
		$unavailable_copy = tailwindscore_feedback_empty_state_copy( 'search-unavailable' );
		tailwindscore_feedback_part(
			'empty-state',
			array(
				'icon_name'    => 'search',
				'eyebrow'      => $unavailable_copy['eyebrow'] ?? '',
				'title'        => $unavailable_copy['title'] ?? '',
				'message'      => $unavailable_copy['message'] ?? '',
				'actions_html' => sprintf(
					'<button class="ts-search-feedback__retry" type="button" data-search-retry>%s</button>',
					esc_html__( 'Try again', 'tailwindscore' )
				),
			)
		);
		?>
	</template>
</div>
