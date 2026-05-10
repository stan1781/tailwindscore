<?php
/**
 * Thumbnail strip — `ul.flex-control-nav` kept for WooCommerce variation image JS compatibility.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Expect `thumbs` list from gallery runtime adapter.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$thumbs = isset( $args['thumbs'] ) && is_array( $args['thumbs'] ) ? $args['thumbs'] : array();

if ( count( $thumbs ) < 2 ) {
	return;
}

?>
<div class="ts-gallery__thumbs-shell">
	<div class="ts-gallery__thumbs-viewport embla">
		<ul class="flex-control-nav embla__container ts-gallery__thumbs-list">
			<?php foreach ( $thumbs as $idx => $thumb ) : ?>
				<?php
				$tid = isset( $thumb['attachment_id'] ) ? (int) $thumb['attachment_id'] : 0;
				$src = isset( $thumb['src'] ) ? (string) $thumb['src'] : '';
				$alt = isset( $thumb['alt'] ) ? (string) $thumb['alt'] : '';
				?>
				<li class="embla__slide ts-gallery__thumb" data-thumb-index="<?php echo esc_attr( (string) $idx ); ?>">
					<button type="button" class="ts-gallery__thumb-btn" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: thumb index */ __( 'Go to image %d', 'tailwindscore' ), $idx + 1 ) ); ?>">
						<img
							src="<?php echo esc_url( $src ); ?>"
							alt="<?php echo esc_attr( $alt ); ?>"
							loading="lazy"
							decoding="async"
							data-attachment-id="<?php echo esc_attr( (string) $tid ); ?>"
						/>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
