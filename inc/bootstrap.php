<?php
/**
 * Load theme subsystems in dependency-safe order.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

require_once TAILWINDSCORE_PATH . 'inc/helpers/manifest.php';
require_once TAILWINDSCORE_PATH . 'inc/helpers/component.php';
require_once TAILWINDSCORE_PATH . 'inc/helpers/icon.php';
require_once TAILWINDSCORE_PATH . 'inc/helpers/kses.php';
require_once TAILWINDSCORE_PATH . 'inc/configuration/governance.php';
require_once TAILWINDSCORE_PATH . 'inc/configuration/kirki/bootstrap.php';
require_once TAILWINDSCORE_PATH . 'inc/content-moods/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/content-surfaces/sanitizers.php';
require_once TAILWINDSCORE_PATH . 'inc/content-surfaces/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/presets/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/presets/loader.php';
require_once TAILWINDSCORE_PATH . 'inc/account/helpers.php';
require_once TAILWINDSCORE_PATH . 'inc/checkout/helpers.php';
require_once TAILWINDSCORE_PATH . 'inc/feedback/helpers.php';
require_once TAILWINDSCORE_PATH . 'inc/feedback/hooks.php';
require_once TAILWINDSCORE_PATH . 'inc/cart-surface/helpers.php';
require_once TAILWINDSCORE_PATH . 'inc/cart-surface/rest.php';
require_once TAILWINDSCORE_PATH . 'inc/cart-surface/hooks.php';
require_once TAILWINDSCORE_PATH . 'inc/search/helpers.php';
require_once TAILWINDSCORE_PATH . 'inc/search/rest.php';
require_once TAILWINDSCORE_PATH . 'inc/site-shell/helpers.php';
require_once TAILWINDSCORE_PATH . 'inc/site-shell/walker.php';

require_once TAILWINDSCORE_PATH . 'inc/setup/theme-support.php';
require_once TAILWINDSCORE_PATH . 'inc/setup/cleanup.php';

require_once TAILWINDSCORE_PATH . 'inc/woocommerce/support.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/load-adapters.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/hooks.php';

require_once TAILWINDSCORE_PATH . 'inc/enqueue/front.php';

require_once TAILWINDSCORE_PATH . 'inc/hooks/template-hooks.php';
