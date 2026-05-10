<?php
/**
 * TailwindScore — thin bootstrap only.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

define( 'TAILWINDSCORE_PATH', trailingslashit( get_template_directory() ) );
define( 'TAILWINDSCORE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'TAILWINDSCORE_VERSION', '0.1.0' );

require_once TAILWINDSCORE_PATH . 'inc/bootstrap.php';
