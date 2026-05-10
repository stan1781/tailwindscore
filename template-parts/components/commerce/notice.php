<?php
/**
 * Notice enhancement host — MutationObserver scans subtree for WC alert classes.
 *
 * Prefer wrapping the region where WooCommerce prints notices (or theme equivalent).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args {
 *   @type string $dismiss_after_ms Empty = default (see notices.ts); "0" = no auto-dismiss.
 * }
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

tailwindscore_feedback_notice_region(
	array(
		'dismiss_after_ms' => $args['dismiss_after_ms'] ?? '',
		'context'          => 'notice-region',
		'scope_label'      => __( 'Store notices', 'tailwindscore' ),
		'render_if_empty'  => true,
		'attributes'       => array(
			'class' => 'ts-commerce-notices-host',
		),
	)
);
