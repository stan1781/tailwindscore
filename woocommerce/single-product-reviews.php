<?php
/**
 * TailwindScore single product reviews.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

$reviewers  = $product instanceof WC_Product ? (int) $product->get_review_count() : 0;
$average    = $product instanceof WC_Product ? (float) $product->get_average_rating() : 0.0;
$empty_copy = tailwindscore_feedback_empty_state_copy( 'reviews' );
?>
<div id="reviews" class="ts-reviews" data-ts-module="account-focus">
	<header class="ts-reviews__header">
		<p class="ts-reviews__eyebrow"><?php echo esc_html( $empty_copy['eyebrow'] ?? '' ); ?></p>
		<div class="ts-reviews__heading">
			<h2 class="ts-reviews__title"><?php esc_html_e( 'Customer reviews', 'tailwindscore' ); ?></h2>
			<p class="ts-reviews__intro"><?php esc_html_e( 'Measured notes from customers, arranged with the same quiet hierarchy as the rest of the product story.', 'tailwindscore' ); ?></p>
		</div>
		<div class="ts-reviews__summary">
			<?php if ( wc_review_ratings_enabled() ) : ?>
				<div class="ts-reviews__aggregate">
					<p class="ts-reviews__aggregate-value"><?php echo esc_html( number_format_i18n( (float) $average, 1 ) ); ?></p>
					<div class="ts-rating">
						<?php echo wp_kses_post( wc_get_rating_html( (float) $average, (int) $reviewers ) ); ?>
					</div>
					<p class="ts-reviews__aggregate-count">
						<?php
						echo esc_html(
							sprintf(
								_n( '%s review', '%s reviews', (int) $reviewers, 'tailwindscore' ),
								number_format_i18n( (int) $reviewers )
							)
						);
						?>
					</p>
				</div>
			<?php else : ?>
				<p class="ts-reviews__aggregate-count">
					<?php
					echo esc_html(
						sprintf(
							_n( '%s review', '%s reviews', (int) $reviewers, 'tailwindscore' ),
							number_format_i18n( (int) $reviewers )
						)
					);
					?>
				</p>
			<?php endif; ?>
		</div>
	</header>

	<div class="ts-reviews__body">
		<div class="ts-reviews__list-wrap">
			<?php if ( have_comments() ) : ?>
				<ol class="ts-reviews__list commentlist">
					<?php
					wp_list_comments(
						array(
							'callback' => 'tailwindscore_review_list_item',
							'style'    => 'ol',
							'type'     => 'comment',
						)
					);
					?>
				</ol>

				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
					<nav class="ts-reviews__pagination" aria-label="<?php esc_attr_e( 'Reviews pagination', 'tailwindscore' ); ?>">
						<?php paginate_comments_links(); ?>
					</nav>
				<?php endif; ?>
			<?php else : ?>
				<div class="ts-reviews__empty">
					<?php
					tailwindscore_feedback_part(
						'empty-state',
						array(
							'icon_name' => 'heart',
							'eyebrow'   => $empty_copy['eyebrow'] ?? '',
							'title'     => $empty_copy['title'] ?? '',
							'message'   => $empty_copy['message'] ?? '',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>

		<div class="ts-reviews__form-wrap">
			<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product ? $product->get_id() : 0 ) ) : ?>
				<?php comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', tailwindscore_review_form_args() ) ); ?>
			<?php else : ?>
				<div class="ts-reviews__empty">
					<?php
					tailwindscore_feedback_part(
						'empty-state',
						array(
							'icon_name'    => 'user',
							'eyebrow'      => __( 'Review access', 'tailwindscore' ),
							'title'        => __( 'Purchase required to review', 'tailwindscore' ),
							'message'      => __( 'Only customers who have purchased this item can leave a review, which keeps the conversation grounded in ownership.', 'tailwindscore' ),
							'actions_html' => ! is_user_logged_in() ? sprintf( '<a class="ts-btn ts-btn--secondary ts-btn--sm" href="%s">%s</a>', esc_url( wc_get_page_permalink( 'myaccount' ) ), esc_html__( 'Sign in', 'tailwindscore' ) ) : '',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
