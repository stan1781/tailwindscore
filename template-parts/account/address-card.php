<?php
/**
 * Address summary card.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'title'        => '',
	'description'  => '',
	'action_url'   => '',
	'action_label' => '',
);

$args         = wp_parse_args( (array) ( $args ?? array() ), $defaults );
$account_copy = tailwindscore_account_template_copy();
?>
<article class="ts-address-card">
	<div class="ts-address-card__body">
		<h2 class="ts-address-card__title"><?php echo esc_html( (string) $args['title'] ); ?></h2>
		<div class="ts-address-card__copy">
			<?php if ( '' !== trim( (string) $args['description'] ) ) : ?>
				<?php echo wp_kses_post( wpautop( (string) $args['description'] ) ); ?>
			<?php else : ?>
				<p><?php echo esc_html( $account_copy['address_empty_message'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( '' !== trim( (string) $args['action_url'] ) && '' !== trim( (string) $args['action_label'] ) ) : ?>
		<div class="ts-address-card__actions">
			<a class="ts-btn ts-btn--secondary ts-btn--sm" href="<?php echo esc_url( (string) $args['action_url'] ); ?>">
				<?php echo esc_html( (string) $args['action_label'] ); ?>
			</a>
		</div>
	<?php endif; ?>
</article>
