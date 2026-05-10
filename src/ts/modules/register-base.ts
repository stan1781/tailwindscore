/**
 * Register globally available TS modules.
 */
import { registerModule } from '../bootstrap-registry';
import { mount as mountAccountFocus } from './account/account-focus';
import { mount as mountCartDrawer } from './cart-surface/cart-drawer';
import { mount as mountCartFocus } from './cart-surface/cart-focus';
import { mount as mountFeedbackRuntime } from './feedback';
import { mount as mountPredictiveSearch } from './search/predictive-search';
import { mount as mountSearchFocus } from './search/search-focus';
import { mount as mountSearchSurface } from './search/search-surface';
import { mount as mountMobileDrawer } from './site-shell/mobile-drawer';
import { mount as mountNavigationFocus } from './site-shell/navigation-focus';
import { mount as mountStickyHeader } from './site-shell/sticky-header';

registerModule('account-focus', mountAccountFocus);
registerModule('cart-drawer', mountCartDrawer);
registerModule('cart-focus', mountCartFocus);
registerModule('feedback-runtime', mountFeedbackRuntime);
registerModule('mobile-drawer', mountMobileDrawer);
registerModule('navigation-focus', mountNavigationFocus);
registerModule('predictive-search', mountPredictiveSearch);
registerModule('search-focus', mountSearchFocus);
registerModule('search-surface', mountSearchSurface);
registerModule('sticky-header', mountStickyHeader);
