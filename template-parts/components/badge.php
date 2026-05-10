<?php
/**
 * Badge component — `.ts-badge` + variant modifiers.
 *
 * Responsibility: compact status / promo label (sale, new, neutral, success).
 *
 * Unsupported: removable chips, dismiss buttons, numeric counters.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'label'   => '',
	'variant' => 'neutral',
	'size'    => 'md',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$variants = array( 'sale', 'new', 'neutral', 'success' );
$sizes    = array( 'sm', 'md' );

$variant = in_array( $args['variant'], $variants, true ) ? $args['variant'] : 'neutral';
$size    = in_array( $args['size'], $sizes, true ) ? $args['size'] : 'md';

$label = is_string( $args['label'] ) ? $args['label'] : '';

$classes = array(
	'ts-badge',
	'ts-badge--' . $variant,
);

if ( 'md' !== $size ) {
	$classes[] = 'ts-badge--' . $size;
}

$class_attr = tailwindscore_component_classes( $classes, $args, 'badge' );

?>
<span class="<?php echo esc_attr( $class_attr ); ?>"><?php echo esc_html( $label ); ?></span>
