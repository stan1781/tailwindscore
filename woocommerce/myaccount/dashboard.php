<?php
/**
 * Premium account overview.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$items             = tailwindscore_account_dashboard_cards();
$dashboard_message = tailwindscore_account_surface_text( 'account-dashboard-message' );
$open_label        = tailwindscore_account_surface_text( 'account-secondary-action-label', __( 'Open', 'tailwindscore' ) );
?>
<section class="ts-account-panel ts-account-overview">
	<?php if ( '' !== trim( $dashboard_message ) ) : ?>
		<p class="ts-account-overview__intro"><?php echo esc_html( $dashboard_message ); ?></p>
	<?php endif; ?>
	<div class="ts-account-overview__cards">
		<?php foreach ( $items as $item ) : ?>
			<article class="ts-account-overview-card">
				<h2 class="ts-account-overview-card__title"><?php echo esc_html( $item['title'] ); ?></h2>
				<p class="ts-account-overview-card__copy"><?php echo esc_html( $item['copy'] ); ?></p>
				<a class="ts-btn ts-btn--secondary ts-btn--sm" href="<?php echo esc_url( $item['url'] ); ?>">
					<?php echo esc_html( $open_label ); ?>
				</a>
			</article>
		<?php endforeach; ?>
	</div>

	<div class="ts-account-overview__body">
		<?php do_action( 'woocommerce_account_dashboard' ); ?>
	</div>
</section>
