<?php
/**
 * Product card — foundation layout only (no carousel / quick view / wishlist).
 *
 * Responsibility: SSR shell for archive tiles — media, title, optional badge, price, optional actions slot.
 *
 * Unsupported: gallery, variation swatches, AJAX interactions. Nested interactive controls must live in
 * `.ts-product-card__footer` (not inside the optional `.ts-product-card__shell` link).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'permalink'        => '',
	'title'            => '',
	'title_tag'        => 'h3',
	'media_html'       => '',
	'media'            => array(),
	'image_url'        => '',
	'image_alt'        => '',
	'image_width'      => '',
	'image_height'     => '',
	'badge'            => null,
	'badges'           => array(),
	'swatches'         => array(),
	'actions'          => array(),
	'collection'       => array(),
	'price'            => array(),
	'actions_html'     => '',
	'title_attributes' => array(),
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$permalink = is_string( $args['permalink'] ) ? esc_url( $args['permalink'] ) : '';

$title = is_string( $args['title'] ) ? $args['title'] : '';

$title_tags = array( 'h2', 'h3', 'h4', 'p' );
$title_tag  = is_string( $args['title_tag'] ) ? strtolower( $args['title_tag'] ) : 'h3';
if ( ! in_array( $title_tag, $title_tags, true ) ) {
	$title_tag = 'h3';
}

$media_html = is_string( $args['media_html'] ) ? $args['media_html'] : '';
$media      = isset( $args['media'] ) && is_array( $args['media'] ) ? $args['media'] : array();

$image_url = is_string( $args['image_url'] ) ? esc_url( $args['image_url'] ) : '';
$image_alt = is_string( $args['image_alt'] ) ? $args['image_alt'] : '';

$image_w = $args['image_width'];
$image_h = $args['image_height'];
$width   = is_numeric( $image_w ) ? (int) $image_w : null;
$height  = is_numeric( $image_h ) ? (int) $image_h : null;

$actions_html = is_string( $args['actions_html'] ) ? $args['actions_html'] : '';
$actions      = isset( $args['actions'] ) && is_array( $args['actions'] ) ? $args['actions'] : array();
$swatches     = isset( $args['swatches'] ) && is_array( $args['swatches'] ) ? $args['swatches'] : array();

$card_classes = array( 'ts-product-card' );

$card_class_attr = tailwindscore_component_classes( $card_classes, $args, 'product-card' );

?>
<article class="<?php echo esc_attr( $card_class_attr ); ?>" data-ts-module="tailwindscore-archive-runtime">
	<?php if ( '' !== $permalink ) : ?>
		<a class="ts-product-card__shell" href="<?php echo esc_url( $permalink ); ?>">
	<?php endif; ?>

		<?php if ( '' !== $media_html ) : ?>
			<?php echo wp_kses_post( $media_html ); ?>
		<?php elseif ( array() !== $media ) : ?>
			<?php tailwindscore_component( 'archive/product-card-media', array( 'media' => $media, 'badges' => $args['badges'] ) ); ?>
		<?php elseif ( '' !== $image_url ) : ?>
			<div class="ts-product-card__media">
				<img
					class="ts-product-card__image ts-product-card__image--primary"
					src="<?php echo esc_url( $image_url ); ?>"
					alt="<?php echo esc_attr( $image_alt ); ?>"
					<?php echo null !== $width ? ' width="' . esc_attr( (string) $width ) . '"' : ''; ?>
					<?php echo null !== $height ? ' height="' . esc_attr( (string) $height ) . '"' : ''; ?>
					loading="lazy"
					decoding="async"
					data-ts-primary-image
				/>
			</div>
		<?php endif; ?>

		<div class="ts-product-card__body">
			<?php if ( '' !== $title ) : ?>
				<?php
				$title_attrs = array(
					'class' => 'ts-product-card__title',
				);
				if ( is_array( $args['title_attributes'] ) ) {
					foreach ( $args['title_attributes'] as $key => $val ) {
						$key = sanitize_key( (string) $key );
						if ( '' === $key || 'class' === $key ) {
							continue;
						}
						if ( null === $val || false === $val ) {
							continue;
						}
						if ( true === $val ) {
							$title_attrs[ $key ] = true;
							continue;
						}
						$title_attrs[ $key ] = is_scalar( $val ) ? (string) $val : '';
					}
				}
				$title_attr_html = tailwindscore_attributes_html( $title_attrs );
				?>
				<<?php echo esc_attr( $title_tag ); ?><?php echo $title_attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php echo esc_html( $title ); ?>
				</<?php echo esc_attr( $title_tag ); ?>>
			<?php endif; ?>
		</div>

	<?php if ( '' !== $permalink ) : ?>
		</a>
	<?php endif; ?>

	<?php if ( array() !== $swatches ) : ?>
		<?php tailwindscore_component( 'archive/product-card-swatches', array( 'swatches' => $swatches ) ); ?>
	<?php endif; ?>

	<div class="ts-product-card__footer">
		<?php if ( ! empty( $args['price'] ) && is_array( $args['price'] ) ) : ?>
			<?php tailwindscore_component( 'price', $args['price'] ); ?>
		<?php endif; ?>

		<?php if ( array() !== $actions ) : ?>
			<?php tailwindscore_component( 'archive/product-card-actions', array( 'actions' => $actions ) ); ?>
		<?php elseif ( '' !== $actions_html ) : ?>
			<div class="ts-product-card__actions">
				<?php echo tailwindscore_kses_actions_slot( $actions_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</article>
