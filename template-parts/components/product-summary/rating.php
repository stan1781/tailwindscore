<?php
/**
 * Single product summary — rating (SSR, WC rating HTML).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'rating_html'   => '',
	'average'       => 0.0,
	'review_count'  => 0,
	'show_if_empty' => false,
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$rating_html = is_string( $args['rating_html'] ) ? $args['rating_html'] : '';

$review_count = (int) $args['review_count'];
$show_empty   = (bool) $args['show_if_empty'];

if ( ! $show_empty && '' === trim( $rating_html ) ) {
	return;
}

$wrap_classes = tailwindscore_component_classes(
	array( 'ts-product-summary__rating', 'ts-rating' ),
	$args,
	'product-summary-rating'
);

?>
<div class="<?php echo esc_attr( $wrap_classes ); ?>">
	<?php echo wp_kses_post( $rating_html ); ?>
</div>
