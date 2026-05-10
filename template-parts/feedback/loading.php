<?php
/**
 * Inline loading / updating indicator.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'id'      => '',
	'message' => __( 'Updating', 'tailwindscore' ),
	'hidden'  => true,
	'tone'    => 'neutral',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$id      = is_string( $args['id'] ) ? sanitize_html_class( $args['id'] ) : '';
$message = is_string( $args['message'] ) ? trim( $args['message'] ) : __( 'Updating', 'tailwindscore' );
$hidden  = (bool) $args['hidden'];
$tone    = is_string( $args['tone'] ) ? sanitize_key( $args['tone'] ) : 'neutral';

if ( '' === $id ) {
	$id = 'ts-feedback-loading-' . wp_unique_id();
}

?>
<div
	id="<?php echo esc_attr( $id ); ?>"
	class="ts-feedback-loading ts-feedback-loading--<?php echo esc_attr( $tone ); ?>"
	data-feedback-loading
	<?php if ( $hidden ) : ?>
		hidden
	<?php endif; ?>
>
	<span class="ts-feedback-loading__dot" aria-hidden="true"></span>
	<span class="ts-feedback-loading__message" data-feedback-loading-message><?php echo esc_html( $message ); ?></span>
</div>
