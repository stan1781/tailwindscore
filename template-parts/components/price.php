<?php
/**
 * Price component — `.ts-price-block` wrapper for WooCommerce HTML or structured amounts.
 *
 * Responsibility: consistent price presentation; never computes totals server-side beyond wc_price().
 *
 * Unsupported: subscription terms breakdown, tax-inclusive matrices (extend later).
 *
 * Modes:
 * - Pass `price_html` from WooCommerce filters (preferred).
 * - Or pass `sale_amount` / `regular_amount` (raw decimals) when WooCommerce is active.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'price_html'      => '',
	'sale_amount'     => '',
	'regular_amount'  => '',
	'suffix_html'     => '',
	'unit_html'       => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$price_html = is_string( $args['price_html'] ) ? $args['price_html'] : '';

$suffix_html = is_string( $args['suffix_html'] ) ? wp_kses_post( $args['suffix_html'] ) : '';
$unit_html   = is_string( $args['unit_html'] ) ? wp_kses_post( $args['unit_html'] ) : '';

?>
<div class="ts-price-block">
	<?php if ( '' !== $price_html ) : ?>
		<?php echo wp_kses_post( $price_html ); ?>
	<?php elseif ( function_exists( 'wc_price' ) ) : ?>
		<?php
		$sale_raw     = $args['sale_amount'];
		$regular_raw  = $args['regular_amount'];
		$sale_dec     = is_numeric( $sale_raw ) ? (string) $sale_raw : '';
		$regular_dec  = is_numeric( $regular_raw ) ? (string) $regular_raw : '';

		if ( '' !== $sale_dec && '' !== $regular_dec ) {
			echo '<del class="ts-price-block__compare">' . wp_kses_post( wc_price( $regular_dec ) ) . '</del> ';
			echo '<ins class="ts-price-block__current">' . wp_kses_post( wc_price( $sale_dec ) ) . '</ins>';
		} elseif ( '' !== $sale_dec ) {
			echo '<span class="ts-price-block__current">' . wp_kses_post( wc_price( $sale_dec ) ) . '</span>';
		} elseif ( '' !== $regular_dec ) {
			echo '<span class="ts-price-block__current">' . wp_kses_post( wc_price( $regular_dec ) ) . '</span>';
		}
		?>
	<?php endif; ?>

	<?php if ( '' !== $suffix_html ) : ?>
		<span class="ts-price-block__suffix"><?php echo $suffix_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<?php endif; ?>

	<?php if ( '' !== $unit_html ) : ?>
		<span class="ts-product-text"><?php echo $unit_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<?php endif; ?>
</div>
