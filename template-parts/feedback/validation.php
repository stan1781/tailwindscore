<?php
/**
 * Validation feedback surface.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'id'      => '',
	'title'   => '',
	'message' => '',
	'hidden'  => true,
	'tone'    => 'error',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$id      = is_string( $args['id'] ) ? sanitize_html_class( $args['id'] ) : '';
$title   = is_string( $args['title'] ) ? trim( $args['title'] ) : '';
$message = is_string( $args['message'] ) ? trim( $args['message'] ) : '';
$hidden  = (bool) $args['hidden'];
$tone    = is_string( $args['tone'] ) ? sanitize_key( $args['tone'] ) : 'error';

if ( '' === $id ) {
	$id = 'ts-feedback-validation-' . wp_unique_id();
}

?>
<div
	id="<?php echo esc_attr( $id ); ?>"
	class="ts-feedback-validation ts-feedback-validation--<?php echo esc_attr( $tone ); ?>"
	data-feedback-validation
	role="alert"
	aria-live="assertive"
	<?php if ( $hidden && '' === $message ) : ?>
		hidden
	<?php endif; ?>
>
	<?php if ( '' !== $title ) : ?>
		<p class="ts-feedback-validation__title"><?php echo esc_html( $title ); ?></p>
	<?php endif; ?>
	<p class="ts-feedback-validation__message" data-feedback-validation-message><?php echo esc_html( $message ); ?></p>
</div>
