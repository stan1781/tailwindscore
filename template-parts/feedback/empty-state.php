<?php
/**
 * Generic feedback empty state.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'icon_name'   => 'bag',
	'eyebrow'     => '',
	'title'       => '',
	'message'     => '',
	'actions_html'=> '',
	'align'       => 'start',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$icon_name    = is_string( $args['icon_name'] ) ? sanitize_title( $args['icon_name'] ) : 'bag';
$eyebrow      = is_string( $args['eyebrow'] ) ? trim( $args['eyebrow'] ) : '';
$title        = is_string( $args['title'] ) ? trim( $args['title'] ) : '';
$message      = is_string( $args['message'] ) ? trim( $args['message'] ) : '';
$actions_html = is_string( $args['actions_html'] ) ? $args['actions_html'] : '';
$align        = is_string( $args['align'] ) ? sanitize_key( $args['align'] ) : 'start';

?>
<div class="ts-feedback-empty-state ts-feedback-empty-state--<?php echo esc_attr( in_array( $align, array( 'start', 'center' ), true ) ? $align : 'start' ); ?>">
	<div class="ts-feedback-empty-state__icon" aria-hidden="true">
		<?php echo tailwindscore_icon( $icon_name, array( 'class' => 'ts-icon--lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php if ( '' !== $eyebrow ) : ?>
		<p class="ts-feedback-empty-state__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
	<?php endif; ?>
	<?php if ( '' !== $title ) : ?>
		<h3 class="ts-feedback-empty-state__title"><?php echo esc_html( $title ); ?></h3>
	<?php endif; ?>
	<?php if ( '' !== $message ) : ?>
		<p class="ts-feedback-empty-state__message"><?php echo esc_html( $message ); ?></p>
	<?php endif; ?>
	<?php if ( '' !== trim( $actions_html ) ) : ?>
		<div class="ts-feedback-empty-state__actions">
			<?php echo tailwindscore_kses_actions_slot( $actions_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
</div>
