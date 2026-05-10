<?php
/**
 * Archive product card media.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$media     = isset( $args['media'] ) && is_array( $args['media'] ) ? $args['media'] : array();
$badges    = isset( $args['badges'] ) && is_array( $args['badges'] ) ? $args['badges'] : array();
$primary   = isset( $media['primary'] ) && is_array( $media['primary'] ) ? $media['primary'] : array();
$secondary = isset( $media['secondary'] ) && is_array( $media['secondary'] ) ? $media['secondary'] : array();
$ratio     = isset( $media['aspect_ratio'] ) && is_string( $media['aspect_ratio'] ) ? $media['aspect_ratio'] : '4 / 5';
$hover     = ! empty( $media['hover_enabled'] );
$primary_w = isset( $primary['width'] ) && is_numeric( $primary['width'] ) ? (int) $primary['width'] : null;
$primary_h = isset( $primary['height'] ) && is_numeric( $primary['height'] ) ? (int) $primary['height'] : null;
$secondary_w = isset( $secondary['width'] ) && is_numeric( $secondary['width'] ) ? (int) $secondary['width'] : null;
$secondary_h = isset( $secondary['height'] ) && is_numeric( $secondary['height'] ) ? (int) $secondary['height'] : null;
?>
<div
	class="ts-product-card__media"
	data-ts-archive-media
	data-hover-enabled="<?php echo $hover ? 'true' : 'false'; ?>"
	style="--ts-product-card-ratio: <?php echo esc_attr( $ratio ); ?>"
>
	<?php tailwindscore_component( 'archive/product-card-badges', array( 'badges' => $badges ) ); ?>
	<div class="ts-product-card__media-frame">
		<?php if ( ! empty( $primary['url'] ) ) : ?>
			<img
				class="ts-product-card__image ts-product-card__image--primary"
				src="<?php echo esc_url( (string) $primary['url'] ); ?>"
				alt="<?php echo esc_attr( (string) ( $primary['alt'] ?? '' ) ); ?>"
				<?php echo null !== $primary_w ? ' width="' . esc_attr( (string) $primary_w ) . '"' : ''; ?>
				<?php echo null !== $primary_h ? ' height="' . esc_attr( (string) $primary_h ) . '"' : ''; ?>
				loading="lazy"
				decoding="async"
				data-ts-primary-image
			/>
		<?php endif; ?>

		<?php if ( ! empty( $secondary['url'] ) ) : ?>
			<img
				class="ts-product-card__image ts-product-card__image--secondary"
				src="<?php echo esc_url( (string) $secondary['url'] ); ?>"
				alt=""
				aria-hidden="true"
				<?php echo null !== $secondary_w ? ' width="' . esc_attr( (string) $secondary_w ) . '"' : ''; ?>
				<?php echo null !== $secondary_h ? ' height="' . esc_attr( (string) $secondary_h ) . '"' : ''; ?>
				loading="lazy"
				decoding="async"
				data-ts-secondary-image
			/>
		<?php endif; ?>
	</div>
</div>
