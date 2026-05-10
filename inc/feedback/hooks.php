<?php
/**
 * Feedback hooks shared across the theme.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_footer',
	static function (): void {
		tailwindscore_feedback_part(
			'toast',
			array(
				'id' => 'ts-feedback-toast-root',
			)
		);
		tailwindscore_feedback_part(
			'notice',
			array(
				'id'                => 'ts-feedback-live-region-root',
				'context'           => 'live-region',
				'live_only'         => true,
				'aria_live'         => 'polite',
				'role'              => 'status',
				'render_if_empty'   => true,
				'dismiss_after_ms'  => '0',
				'scope_label'       => __( 'Store updates', 'tailwindscore' ),
			)
		);
	},
	40
);
