<?php
/**
 * Lightweight toast host for progressive enhancement.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'id'               => '',
	'scope_label'      => __( 'Store feedback', 'tailwindscore' ),
	'dismiss_after_ms' => '4200',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$id          = is_string( $args['id'] ) ? sanitize_html_class( $args['id'] ) : '';
$scope_label = is_string( $args['scope_label'] ) ? $args['scope_label'] : __( 'Store feedback', 'tailwindscore' );
$dismiss     = is_string( $args['dismiss_after_ms'] ) ? preg_replace( '/[^0-9\-]/', '', $args['dismiss_after_ms'] ) : '4200';

if ( '' === $id ) {
	$id = 'ts-feedback-toast-' . wp_unique_id();
}

?>
<div
	id="<?php echo esc_attr( $id ); ?>"
	class="ts-feedback-toast-region"
	data-ts-module="feedback-runtime"
	data-feedback-role="toast-region"
	data-feedback-toast-host
	data-feedback-dismiss="<?php echo esc_attr( $dismiss ); ?>"
	aria-label="<?php echo esc_attr( $scope_label ); ?>"
></div>
