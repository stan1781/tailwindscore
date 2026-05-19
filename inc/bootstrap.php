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
require_once TAILWINDSCORE_PATH . 'inc/configuration/behaviors/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/configuration/kirki/bootstrap.php';
require_once TAILWINDSCORE_PATH . 'inc/content-moods/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/content-surfaces/sanitizers.php';
require_once TAILWINDSCORE_PATH . 'inc/content-surfaces/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/presets/metadata/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/presets/registry.php';
require_once TAILWINDSCORE_PATH . 'inc/presets/loader.php';
require_once TAILWINDSCORE_PATH . 'inc/site-shell/helpers.php';
require_once TAILWINDSCORE_PATH . 'inc/site-shell/walker.php';

require_once TAILWINDSCORE_PATH . 'inc/setup/theme-support.php';
require_once TAILWINDSCORE_PATH . 'inc/setup/cleanup.php';

require_once TAILWINDSCORE_PATH . 'inc/woocommerce/support.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/feedback.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/cart.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/checkout.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/account.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/search.php';

require_once TAILWINDSCORE_PATH . 'inc/woocommerce/adapters/price.php';

require_once TAILWINDSCORE_PATH . 'inc/woocommerce/hooks/archive-hooks.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/hooks/single-product-hooks.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/hooks/variation-experience.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/hooks/review-experience.php';
require_once TAILWINDSCORE_PATH . 'inc/woocommerce/swatches/load.php';

require_once TAILWINDSCORE_PATH . 'inc/enqueue/front.php';

require_once TAILWINDSCORE_PATH . 'inc/hooks/template-hooks.php';
