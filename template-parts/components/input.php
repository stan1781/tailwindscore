<?php
/**
 * Text input component — `.ts-field`, `.ts-label`, `.ts-input` (WooCommerce-friendly).
 *
 * Responsibility: accessible label + control + help/error slots matching WC field naming.
 *
 * Unsupported: checkbox/radio groups (use dedicated markup); file uploads.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'id'             => '',
	'name'           => '',
	'type'           => 'text',
	'value'          => '',
	'label'          => '',
	'placeholder'    => '',
	'help'           => '',
	'error'          => '',
	'required'       => false,
	'disabled'       => false,
	'autocomplete'   => '',
	'inputmode'      => '',
	'wrapper_class'  => '',
	'attributes'     => array(),
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$name = is_string( $args['name'] ) ? $args['name'] : '';
$id   = is_string( $args['id'] ) ? $args['id'] : '';

if ( '' === $id && '' !== $name ) {
	$id = sanitize_title( $name );
}

if ( '' === $id ) {
	$id = 'ts-field-' . wp_unique_id();
}

$types_allowed = array(
	'text',
	'email',
	'url',
	'tel',
	'number',
	'password',
	'search',
	'date',
	'datetime-local',
	'time',
);
$type = is_string( $args['type'] ) ? $args['type'] : 'text';
if ( ! in_array( $type, $types_allowed, true ) ) {
	$type = 'text';
}

$label       = is_string( $args['label'] ) ? $args['label'] : '';
$placeholder = is_string( $args['placeholder'] ) ? $args['placeholder'] : '';
$help        = is_string( $args['help'] ) ? $args['help'] : '';
$error       = is_string( $args['error'] ) ? $args['error'] : '';
$value       = is_string( $args['value'] ) ? $args['value'] : '';

$required = (bool) $args['required'];
$disabled = (bool) $args['disabled'];

$autocomplete = is_string( $args['autocomplete'] ) ? sanitize_text_field( $args['autocomplete'] ) : '';
$inputmode    = is_string( $args['inputmode'] ) ? sanitize_key( $args['inputmode'] ) : '';

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

$wrapper_class_attr = tailwindscore_component_classes( $wrapper_tokens, $args, 'input-field' );

$label_tokens = array( 'ts-label' );
if ( $required ) {
	$label_tokens[] = 'ts-label--required';
}

$label_class_attr = tailwindscore_component_classes( $label_tokens, $args, 'input-label' );

$input_attrs = array(
	'id'           => $id,
	'name'         => $name,
	'type'         => $type,
	'value'        => $value,
	'class'        => 'ts-input input-text',
	'autocomplete' => $autocomplete,
	'inputmode'    => $inputmode,
);

if ( '' !== $placeholder ) {
	$input_attrs['placeholder'] = $placeholder;
}

if ( $required ) {
	$input_attrs['required'] = true;
	$input_attrs['aria-required'] = 'true';
}

if ( $disabled ) {
	$input_attrs['disabled'] = true;
}

if ( '' !== $error ) {
	$input_attrs['aria-invalid'] = 'true';
	$input_attrs['aria-describedby'] = $id . '-error';
	if ( '' !== $help ) {
		$input_attrs['aria-describedby'] = $id . '-help ' . $id . '-error';
	}
} elseif ( '' !== $help ) {
	$input_attrs['aria-describedby'] = $id . '-help';
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
			$input_attrs[ $key ] = true;
			continue;
		}
		$input_attrs[ $key ] = is_scalar( $val ) ? (string) $val : '';
	}
}

$input_attr_html = tailwindscore_attributes_html( $input_attrs );

?>
<div class="<?php echo esc_attr( $wrapper_class_attr ); ?>">
	<?php if ( '' !== $label ) : ?>
		<label class="<?php echo esc_attr( $label_class_attr ); ?>" for="<?php echo esc_attr( $id ); ?>">
			<?php echo esc_html( $label ); ?>
		</label>
	<?php endif; ?>

	<input<?php echo $input_attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />

	<?php if ( '' !== $help ) : ?>
		<p class="ts-help" id="<?php echo esc_attr( $id ); ?>-help"><?php echo esc_html( $help ); ?></p>
	<?php endif; ?>

	<?php if ( '' !== $error ) : ?>
		<p class="ts-error" id="<?php echo esc_attr( $id ); ?>-error" role="alert"><?php echo esc_html( $error ); ?></p>
	<?php endif; ?>
</div>
