<?php
/**
 * Global utility bar / announcement shell.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$announcement_html = tailwindscore_site_shell_capture_action( 'tailwindscore/site_shell/announcement' );
$meta_html         = tailwindscore_site_shell_capture_action( 'tailwindscore/site_shell/utility_bar_end' );
$has_meta          = '' !== $meta_html;
?>
<div class="ts-utility-bar" aria-label="<?php esc_attr_e( 'Store announcement', 'tailwindscore' ); ?>">
	<div class="ts-utility-bar__inner ts-container">
		<div class="ts-utility-bar__announcement">
			<?php if ( '' !== $announcement_html ) : ?>
				<?php echo $announcement_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<p class="ts-utility-bar__message"><?php echo esc_html( tailwindscore_site_shell_announcement_text() ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $has_meta ) : ?>
			<div class="ts-utility-bar__meta">
				<?php echo $meta_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</div>
