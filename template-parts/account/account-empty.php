<?php
/**
 * Account empty state wrapper.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'context'      => 'orders',
	'actions_html' => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$context      = is_string( $args['context'] ) ? $args['context'] : 'orders';
$actions_html = is_string( $args['actions_html'] ) ? $args['actions_html'] : '';
$copy         = tailwindscore_account_empty_state( $context );
?>
<section class="ts-account-empty">
	<?php
	tailwindscore_feedback_part(
		'empty-state',
		array(
			'icon_name'    => 'user',
			'eyebrow'      => $copy['eyebrow'] ?? '',
			'title'        => $copy['title'] ?? '',
			'message'      => $copy['message'] ?? '',
			'actions_html' => $actions_html,
		)
	);
	?>
</section>
