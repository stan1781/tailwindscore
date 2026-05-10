<?php
/**
 * Archive product card badges.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$badges = isset( $args['badges'] ) && is_array( $args['badges'] ) ? $args['badges'] : array();

if ( array() === $badges ) {
	return;
}
?>
<div class="ts-product-card__badges">
	<?php foreach ( $badges as $badge_args ) : ?>
		<?php if ( is_array( $badge_args ) ) : ?>
			<?php tailwindscore_component( 'badge', $badge_args ); ?>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
