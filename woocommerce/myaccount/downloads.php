<?php
/**
 * Premium account downloads.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;
?>
<section class="ts-account-panel ts-account-downloads">
	<?php if ( ! empty( $downloads ) ) : ?>
		<div class="ts-account-downloads__list">
			<?php foreach ( $downloads as $download ) : ?>
				<article class="ts-account-download-card">
					<div class="ts-account-download-card__body">
						<h2 class="ts-account-download-card__title"><?php echo esc_html( $download['download_name'] ?? $download['product_name'] ?? '' ); ?></h2>
						<div class="ts-account-download-card__meta">
							<?php if ( ! empty( $download['product_name'] ) ) : ?>
								<p><?php echo esc_html( $download['product_name'] ); ?></p>
							<?php endif; ?>
							<?php if ( isset( $download['downloads_remaining'] ) ) : ?>
								<p>
									<?php
									printf(
										/* translators: %s: remaining downloads */
										esc_html__( '%s remaining', 'tailwindscore' ),
										esc_html( is_numeric( $download['downloads_remaining'] ) ? (string) $download['downloads_remaining'] : __( 'Unlimited', 'tailwindscore' ) )
									);
									?>
								</p>
							<?php endif; ?>
							<?php if ( ! empty( $download['access_expires'] ) ) : ?>
								<?php
								$expires = $download['access_expires'];
								$expiry  = $expires instanceof WC_DateTime ? wc_format_datetime( $expires ) : ( is_string( $expires ) ? $expires : '' );
								?>
								<p>
									<?php
									printf(
										/* translators: %s: expiry date */
										esc_html__( 'Available until %s', 'tailwindscore' ),
										esc_html( $expiry )
									);
									?>
								</p>
							<?php endif; ?>
						</div>
					</div>
					<div class="ts-account-download-card__actions">
						<a class="ts-btn ts-btn--primary ts-btn--sm" href="<?php echo esc_url( $download['download_url'] ?? '#' ); ?>">
							<?php esc_html_e( 'Download', 'tailwindscore' ); ?>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<?php tailwindscore_account_part( 'account-empty', array( 'context' => 'downloads' ) ); ?>
	<?php endif; ?>
</section>
