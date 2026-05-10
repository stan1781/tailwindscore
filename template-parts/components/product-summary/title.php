<?php
/**
 * Single product summary — title (SSR).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'title'       => '',
	'heading_tag' => 'h1',
	'product_id'  => 0,
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$title = is_string( $args['title'] ) ? $args['title'] : '';

$allowed_tags = array( 'h1', 'h2', 'h3', 'h4' );
$tag          = is_string( $args['heading_tag'] ) ? strtolower( $args['heading_tag'] ) : 'h1';
if ( ! in_array( $tag, $allowed_tags, true ) ) {
	$tag = 'h1';
}

$classes = array(
	'ts-product-summary__title',
	'ts-heading-1',
);

$class_attr = tailwindscore_component_classes( $classes, $args, 'product-summary-title' );

?>
<div class="ts-product-summary__title-wrap">
	<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Tag name whitelisted above. ?>
	<?php printf( '<%s class="%s">', esc_attr( $tag ), esc_attr( $class_attr ) ); ?>
		<?php echo esc_html( $title ); ?>
	<?php printf( '</%s>', esc_attr( $tag ) ); ?>
</div>
