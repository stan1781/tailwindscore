<?php
/**
 * Prev / next controls for the main Embla viewport (progressive enhancement).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

?>
<div class="ts-gallery__controls" aria-hidden="false">
	<button type="button" class="ts-gallery__nav ts-gallery__nav--prev" data-gallery-nav="prev" aria-label="<?php esc_attr_e( 'Previous image', 'tailwindscore' ); ?>">
		<span class="ts-gallery__nav-icon ts-commerce-icon ts-commerce-icon--action" aria-hidden="true"><?php echo tailwindscore_icon( 'chevron-right', array( 'class' => 'ts-icon--nav ts-gallery__nav-icon-svg ts-gallery__nav-icon-svg--prev' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</button>
	<button type="button" class="ts-gallery__nav ts-gallery__nav--next" data-gallery-nav="next" aria-label="<?php esc_attr_e( 'Next image', 'tailwindscore' ); ?>">
		<span class="ts-gallery__nav-icon ts-commerce-icon ts-commerce-icon--action" aria-hidden="true"><?php echo tailwindscore_icon( 'chevron-right', array( 'class' => 'ts-icon--nav ts-gallery__nav-icon-svg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</button>
</div>
