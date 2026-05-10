<?php
/**
 * Empty cart state.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$copy = tailwindscore_feedback_empty_state_copy( 'cart' );

$actions_html = sprintf(
	'<a class="ts-btn ts-btn--primary" href="%s">%s</a>',
	esc_url( (string) ( $args['shop_url'] ?? home_url( '/' ) ) ),
	esc_html__( 'Continue shopping', 'tailwindscore' )
);
?>
<div class="ts-cart-empty">
	<?php
	tailwindscore_feedback_part(
		'empty-state',
		array(
			'icon_name'    => 'bag',
			'eyebrow'      => $copy['eyebrow'] ?? '',
			'title'        => $copy['title'] ?? '',
			'message'      => $copy['message'] ?? '',
			'actions_html' => $actions_html,
		)
	);
	?>
</div>
