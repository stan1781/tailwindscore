<?php
/**
 * Select component — `.ts-field`, `.ts-label`, `.ts-select` + WC-friendly classes.
 *
 * Responsibility: render accessible select with explicit options map.
 *
 * Unsupported: multi-select (use native multi component later); searchable selects.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'id'           => '',
	'name'         => '',
	'options'      => array(),
	'selected'     => '',
	'label'        => '',
	'placeholder'  => '',
	'help'         => '',
	'error'        => '',
	'required'     => false,
	'disabled'     => false,
	'wrapper_class' => '',
	'attributes'   => array(),
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$name = is_string( $args['name'] ) ? $args['name'] : '';
$id   = is_string( $args['id'] ) ? $args['id'] : '';

if ( '' === $id && '' !== $name ) {
	$id = sanitize_title( $name );
}

if ( '' === $id ) {
	$id = 'ts-select-' . wp_unique_id();
}

$options = $args['options'];
if ( ! is_array( $options ) ) {
	$options = array();
}

$selected = is_string( $args['selected'] ) ? $args['selected'] : (string) $args['selected'];

$label       = is_string( $args['label'] ) ? $args['label'] : '';
$placeholder = is_string( $args['placeholder'] ) ? $args['placeholder'] : '';
$help        = is_string( $args['help'] ) ? $args['help'] : '';
$error       = is_string( $args['error'] ) ? $args['error'] : '';

$required = (bool) $args['required'];
$disabled = (bool) $args['disabled'];

$wrapper_tokens = array( 'ts-field' );
if ( is_string( $args['wrapper_class'] ) && '' !== $args['wrapper_class'] ) {
	foreach ( preg_split( '/\s+/', trim( $args['wrapper_class'] ) ) as $piece ) {
		if ( '' === $piece ) {
			continue;
		}
		$c = sanitize_html_class( $piece );
		if ( '' !== $c && 0 === strpos( $c, 'ts-' ) ) {
			$wrapper_tokens[] = $c;
		}
	}
}

$wrapper_class_attr = tailwindscore_component_classes( $wrapper_tokens, $args, 'select-field' );

$label_tokens = array( 'ts-label' );
if ( $required ) {
	$label_tokens[] = 'ts-label--required';
}

$label_class_attr = tailwindscore_component_classes( $label_tokens, $args, 'select-label' );

$select_attrs = array(
	'id'    => $id,
	'name'  => $name,
	'class' => 'ts-select',
);

if ( $required ) {
	$select_attrs['required']      = true;
	$select_attrs['aria-required'] = 'true';
}

if ( $disabled ) {
	$select_attrs['disabled'] = true;
}

if ( '' !== $error ) {
	$select_attrs['aria-invalid'] = 'true';
	$select_attrs['aria-describedby'] = $id . '-error';
	if ( '' !== $help ) {
		$select_attrs['aria-describedby'] = $id . '-help ' . $id . '-error';
	}
} elseif ( '' !== $help ) {
	$select_attrs['aria-describedby'] = $id . '-help';
}

if ( is_array( $args['attributes'] ) ) {
	foreach ( $args['attributes'] as $key => $val ) {
		$key = sanitize_key( (string) $key );
		if ( '' === $key || 'class' === $key ) {
			continue;
		}
		if ( null === $val || false === $val ) {
			continue;
		}
		if ( true === $val ) {
			$select_attrs[ $key ] = true;
			continue;
		}
		$select_attrs[ $key ] = is_scalar( $val ) ? (string) $val : '';
	}
}

$select_attr_html = tailwindscore_attributes_html( $select_attrs );

?>
<div class="<?php echo esc_attr( $wrapper_class_attr ); ?>">
	<?php if ( '' !== $label ) : ?>
		<label class="<?php echo esc_attr( $label_class_attr ); ?>" for="<?php echo esc_attr( $id ); ?>">
			<?php echo esc_html( $label ); ?>
		</label>
	<?php endif; ?>

	<select<?php echo $select_attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( '' !== $placeholder ) : ?>
			<option value="" <?php selected( $selected, '', false ); ?>><?php echo esc_html( $placeholder ); ?></option>
		<?php endif; ?>
		<?php foreach ( $options as $opt_value => $opt_label ) : ?>
			<?php
			$opt_value = (string) $opt_value;
			$opt_label = is_string( $opt_label ) ? $opt_label : (string) $opt_label;
			?>
			<option value="<?php echo esc_attr( $opt_value ); ?>" <?php selected( $selected, $opt_value, true ); ?>><?php echo esc_html( $opt_label ); ?></option>
		<?php endforeach; ?>
	</select>

	<?php if ( '' !== $help ) : ?>
		<p class="ts-help" id="<?php echo esc_attr( $id ); ?>-help"><?php echo esc_html( $help ); ?></p>
	<?php endif; ?>

	<?php if ( '' !== $error ) : ?>
		<p class="ts-error" id="<?php echo esc_attr( $id ); ?>-error" role="alert"><?php echo esc_html( $error ); ?></p>
	<?php endif; ?>
</div>
