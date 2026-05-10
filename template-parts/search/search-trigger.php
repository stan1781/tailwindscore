<?php
/**
 * Search trigger component.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'label'       => __( 'Search', 'tailwindscore' ),
	'context'     => 'utility',
	'target'      => 'ts-search-surface',
	'class'       => '',
	'icon_class'  => 'ts-icon--utility',
	'show_label'  => true,
	'search_text' => __( 'Search', 'tailwindscore' ),
);

$args       = wp_parse_args( (array) ( $args ?? array() ), $defaults );
$classes    = array_filter( array( 'ts-search-trigger', 'ts-search-trigger--' . sanitize_html_class( (string) $args['context'] ), sanitize_html_class( (string) $args['class'] ) ) );
$class_attr = implode( ' ', $classes );
?>
<button
	type="button"
	class="<?php echo esc_attr( $class_attr ); ?>"
	data-search-trigger="<?php echo esc_attr( (string) $args['target'] ); ?>"
	aria-controls="<?php echo esc_attr( (string) $args['target'] ); ?>"
	aria-expanded="false"
>
	<span class="ts-search-trigger__icon ts-commerce-icon ts-commerce-icon--utility" aria-hidden="true">
		<?php echo tailwindscore_icon( 'search', array( 'class' => (string) $args['icon_class'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</span>
	<?php if ( (bool) $args['show_label'] ) : ?>
		<span class="ts-search-trigger__label"><?php echo esc_html( (string) $args['label'] ); ?></span>
	<?php else : ?>
		<span class="screen-reader-text"><?php echo esc_html( (string) $args['search_text'] ); ?></span>
	<?php endif; ?>
</button>
