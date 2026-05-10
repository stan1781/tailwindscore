<?php
/**
 * Swatch 组 SSR — `role="radiogroup"` + 原生 `<select>` 同字段包裹。
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args {
 *   @type string              $select_id   `<select id>`（与 WC 一致）。
 *   @type string              $select_name `name`。
 *   @type string              $attribute   WC attribute 名。
 *   @type string              $aria_label  radiogroup  accessible name。
 *   @type array<int, mixed>   $items       swatch 项（见各子组件）。
 *   @type string              $inner_html  原始 `<select>` 标记。
 * }
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'select_id'   => '',
	'select_name' => '',
	'attribute'   => '',
	'aria_label'  => '',
	'items'       => array(),
	'inner_html'  => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$select_id   = is_string( $args['select_id'] ) ? $args['select_id'] : '';
$aria_label  = is_string( $args['aria_label'] ) ? $args['aria_label'] : '';
$items       = isset( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();
$inner_html  = is_string( $args['inner_html'] ) ? $args['inner_html'] : '';

if ( '' === $select_id || array() === $items ) {
	echo $inner_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$group_classes = tailwindscore_component_classes(
	array( 'ts-swatch-group' ),
	$args,
	'swatch-group'
);

?>
<div class="ts-swatch-field" data-ts-swatch-field data-attribute="<?php echo esc_attr( (string) $args['attribute'] ); ?>">
	<?php tailwindscore_component( 'swatches/swatch-preview', array() ); ?>
	<div
		id="<?php echo esc_attr( $select_id ); ?>-swatches"
		class="<?php echo esc_attr( $group_classes ); ?>"
		role="radiogroup"
		aria-label="<?php echo esc_attr( $aria_label ); ?>"
		data-ts-swatch-group
		data-select-id="<?php echo esc_attr( $select_id ); ?>"
	>
		<div class="ts-swatch-group__list" role="presentation">
			<?php
			foreach ( $items as $item ) {
				if ( ! is_array( $item ) ) {
					continue;
				}
				$type = isset( $item['type'] ) ? (string) $item['type'] : 'text';
				switch ( $type ) {
					case 'color':
						tailwindscore_component( 'swatches/swatch-color', $item );
						break;
					case 'image_stack':
						tailwindscore_component( 'swatches/swatch-image-stack', $item );
						break;
					case 'image':
						tailwindscore_component( 'swatches/swatch-image', $item );
						break;
					default:
						tailwindscore_component( 'swatches/swatch-button', $item );
						break;
				}
			}
			?>
		</div>
	</div>
	<div class="ts-swatch-native-wrap">
		<?php echo $inner_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC select HTML. ?>
	</div>
</div>
