<?php
/**
 * Single product summary — short description (prose body).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'content_html' => '',
	'has_content'  => false,
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$html = is_string( $args['content_html'] ) ? $args['content_html'] : '';
$has  = (bool) $args['has_content'];

if ( ! $has && '' === trim( wp_strip_all_tags( $html ) ) ) {
	return;
}

$wrap = tailwindscore_component_classes(
	array(
		'ts-product-summary__excerpt',
		'ts-prose',
		'ts-body',
	),
	$args,
	'product-summary-excerpt'
);

?>
<div class="<?php echo esc_attr( $wrap ); ?>">
	<?php echo wp_kses_post( $html ); ?>
</div>
