<?php
/**
 * Review adapter — aggregates + single-comment props (no templates).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Product-level rating aggregate (for loops / summary blocks).
 *
 * @param WC_Product $product Product instance.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_product_rating_aggregate_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array(
			'average_rating' => 0.0,
			'review_count'     => 0,
		);
	}

	$props = array(
		'average_rating' => (float) $product->get_average_rating(),
		'review_count'   => (int) $product->get_review_count(),
	);

	/**
	 * Filter aggregate rating props.
	 *
	 * @param array<string, mixed> $props   Keys: average_rating, review_count.
	 * @param WC_Product             $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/product/rating_aggregate_props', $props, $product );
}

/**
 * Single review comment → structured props (excerpt for lists).
 *
 * @param WP_Comment $comment Comment object.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_review_comment_props( $comment ): array {
	if ( ! $comment instanceof WP_Comment ) {
		return array();
	}

	$rating = (int) get_comment_meta( $comment->comment_ID, 'rating', true );

	$words = (int) apply_filters( 'tailwindscore/adapter/review/excerpt_words', 24, $comment );

	$props = array(
		'rating'           => $rating,
		'author_name'      => $comment->comment_author,
		'author_email'     => $comment->comment_author_email,
		'content_raw'      => $comment->comment_content,
		'content_excerpt'  => wp_trim_words( wp_strip_all_tags( $comment->comment_content ), $words ),
		'comment_id'       => (int) $comment->comment_ID,
		'product_id'       => (int) $comment->comment_post_ID,
	);

	/**
	 * Filter review comment adapter props.
	 *
	 * @param array<string, mixed> $props   Structured review data.
	 * @param WP_Comment             $comment Comment.
	 */
	return apply_filters( 'tailwindscore/adapter/review/comment_props', $props, $comment );
}
