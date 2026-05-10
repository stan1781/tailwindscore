/**
 * Register account-page TS modules.
 */
import { registerModule } from '../bootstrap-registry';
import { mount as mountAccountNavigation } from './account/account-navigation';
import { mount as mountOrderToggle } from './account/order-toggle';

registerModule('account-navigation', mountAccountNavigation);
registerModule('order-toggle', mountOrderToggle);
