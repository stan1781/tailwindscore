<?php
/**
 * Unified feedback notice region.
 *
 * Supports:
 * - SSR WooCommerce notice HTML passthrough
 * - Inline feedback messages
 * - Live-region only host for JS announcements
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'id'               => '',
	'context'          => 'notice-region',
	'tone'             => 'info',
	'title'            => '',
	'message'          => '',
	'content_html'     => '',
	'scope_label'      => __( 'Store notices', 'tailwindscore' ),
	'aria_live'        => 'polite',
	'role'             => 'region',
	'dismiss_after_ms' => '',
	'live_only'        => false,
	'render_if_empty'  => true,
	'attributes'       => array(),
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$context      = is_string( $args['context'] ) ? sanitize_key( $args['context'] ) : 'notice-region';
$tone         = is_string( $args['tone'] ) ? sanitize_key( $args['tone'] ) : 'info';
$title        = is_string( $args['title'] ) ? trim( $args['title'] ) : '';
$message      = is_string( $args['message'] ) ? trim( $args['message'] ) : '';
$content_html = is_string( $args['content_html'] ) ? trim( $args['content_html'] ) : '';
$scope_label  = is_string( $args['scope_label'] ) ? $args['scope_label'] : __( 'Store notices', 'tailwindscore' );
$aria_live    = is_string( $args['aria_live'] ) ? sanitize_key( $args['aria_live'] ) : 'polite';
$role         = is_string( $args['role'] ) ? sanitize_key( $args['role'] ) : 'region';
$dismiss      = is_string( $args['dismiss_after_ms'] ) ? preg_replace( '/[^0-9\-]/', '', $args['dismiss_after_ms'] ) : '';
$live_only    = (bool) $args['live_only'];
$render_empty = (bool) $args['render_if_empty'];
$id           = is_string( $args['id'] ) ? sanitize_html_class( $args['id'] ) : '';

if ( '' === $id ) {
	$id = 'ts-feedback-' . wp_unique_id();
}

$has_structured_content = '' !== $title || '' !== $message;
$has_content            = '' !== $content_html || $has_structured_content;

if ( ! $render_empty && ! $has_content && ! $live_only ) {
	return;
}

$classes = array(
	'ts-feedback-region',
	'ts-feedback-region--' . ( $live_only ? 'live' : $context ),
);

if ( ! $live_only ) {
	$classes[] = 'ts-feedback-stack';
}

$attrs = array(
	'id'                  => $id,
	'class'               => tailwindscore_component_classes( $classes, $args, 'feedback-notice-region' ),
	'data-ts-module'      => 'feedback-runtime',
	'data-feedback-role'  => $live_only ? 'live-region' : 'notice-region',
	'data-feedback-tone'  => $tone,
	'data-feedback-context'=> $context,
	'aria-live'           => $aria_live,
	'role'                => $role,
	'aria-label'          => $scope_label,
);

if ( '' !== $dismiss ) {
	$attrs['data-feedback-dismiss'] = $dismiss;
}

if ( is_array( $args['attributes'] ) ) {
	foreach ( $args['attributes'] as $key => $value ) {
		$key = sanitize_key( (string) $key );
		if ( '' === $key ) {
			continue;
		}
		if ( 'class' === $key && is_scalar( $value ) ) {
			$extra_classes   = preg_split( '/\s+/', trim( (string) $value ) ) ?: array();
			$existing_classes = explode( ' ', (string) $attrs['class'] );
			$attrs['class']  = tailwindscore_component_classes(
				array_merge( $existing_classes, $extra_classes ),
				$args,
				'feedback-notice-region'
			);
			continue;
		}
		if ( isset( $attrs[ $key ] ) ) {
			continue;
		}
		if ( null === $value || false === $value ) {
			continue;
		}
		$attrs[ $key ] = true === $value ? true : ( is_scalar( $value ) ? (string) $value : '' );
	}
}

?>
<div<?php echo tailwindscore_attributes_html( $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $live_only ) : ?>
		<span class="screen-reader-text" data-feedback-live-region></span>
	<?php elseif ( '' !== $content_html ) : ?>
		<?php echo wp_kses_post( $content_html ); ?>
	<?php elseif ( $has_structured_content ) : ?>
		<div class="ts-feedback-notice ts-feedback-notice--<?php echo esc_attr( $tone ); ?>" data-feedback-notice>
			<div class="ts-feedback-notice__body">
				<?php if ( '' !== $title ) : ?>
					<p class="ts-feedback-notice__title"><?php echo esc_html( $title ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $message ) : ?>
					<p class="ts-feedback-notice__message"><?php echo esc_html( $message ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
