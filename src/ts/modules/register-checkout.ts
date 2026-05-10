/**
 * Register checkout-page TS modules.
 */
import { registerModule } from '../bootstrap-registry';
import { mount as mountCheckoutFocus } from './checkout/checkout-focus';
import { mount as mountCheckoutLoading } from './checkout/checkout-loading';
import { mount as mountCheckoutPayment } from './checkout/checkout-payment';
import { mount as mountCheckoutSummary } from './checkout/checkout-summary';
import { mount as mountCheckoutFeedback } from './checkout-feedback';

registerModule('checkout-feedback', mountCheckoutFeedback);
registerModule('checkout-focus', mountCheckoutFocus);
registerModule('checkout-loading', mountCheckoutLoading);
registerModule('checkout-payment', mountCheckoutPayment);
registerModule('checkout-summary', mountCheckoutSummary);
