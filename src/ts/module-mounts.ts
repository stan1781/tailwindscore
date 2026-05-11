import { mount as mountCommerceQuantity } from './modules/commerce/quantity';
import { mount as mountCommerceAddToCart } from './modules/commerce/add-to-cart';
import { mount as mountCommerceNotices } from './modules/commerce/notices';
import { mount as mountFeedbackRuntime } from './modules/feedback';
import { mount as mountCheckoutFeedback } from './modules/checkout-feedback';
import { mount as mountCheckoutLoading } from './modules/checkout/checkout-loading';
import { mount as mountCheckoutSummary } from './modules/checkout/checkout-summary';
import { mount as mountCheckoutPayment } from './modules/checkout/checkout-payment';
import { mount as mountCheckoutFocus } from './modules/checkout/checkout-focus';
import { mount as mountArchiveRuntime } from './modules/archive';
import { mount as mountProductGallery } from './modules/gallery';
import { mount as mountVariationRuntime } from './modules/variations';
import { mount as mountMobileDrawer } from './modules/site-shell/mobile-drawer';
import { mount as mountStickyHeader } from './modules/site-shell/sticky-header';
import { mount as mountNavigationFocus } from './modules/site-shell/navigation-focus';
import { mount as mountSearchSurface } from './modules/search/search-surface';
import { mount as mountPredictiveSearch } from './modules/search/predictive-search';
import { mount as mountSearchFocus } from './modules/search/search-focus';
import { mount as mountCartDrawer } from './modules/cart/cart-drawer';
import { mount as mountAccountNavigation } from './modules/account/account-navigation';
import { mount as mountOrderToggle } from './modules/account/order-toggle';
import { mount as mountAccountFocus } from './modules/account/account-focus';

export type MountFn = (root: HTMLElement) => void;

type ModuleDefinition = {
	key: string;
	selector: string;
	mount: MountFn;
};

const mountedModules = new WeakMap<HTMLElement, Set<string>>();

const moduleDefinitions: ModuleDefinition[] = [
	{ key: 'commerce-quantity', selector: '[data-ts-module="commerce-quantity"]', mount: mountCommerceQuantity },
	{ key: 'commerce-add-to-cart', selector: '[data-ts-module="commerce-add-to-cart"]', mount: mountCommerceAddToCart },
	{ key: 'commerce-notices', selector: '[data-ts-module="commerce-notices"]', mount: mountCommerceNotices },
	{ key: 'feedback-runtime', selector: '[data-ts-module="feedback-runtime"]', mount: mountFeedbackRuntime },
	{ key: 'checkout-feedback', selector: '[data-ts-module="checkout-feedback"]', mount: mountCheckoutFeedback },
	{ key: 'checkout-loading', selector: '[data-ts-module="checkout-loading"]', mount: mountCheckoutLoading },
	{ key: 'checkout-summary', selector: '[data-ts-module="checkout-summary"]', mount: mountCheckoutSummary },
	{ key: 'checkout-payment', selector: '[data-ts-module="checkout-payment"]', mount: mountCheckoutPayment },
	{ key: 'checkout-focus', selector: '[data-ts-module="checkout-focus"]', mount: mountCheckoutFocus },
	{ key: 'tailwindscore-archive-runtime', selector: '[data-ts-module="tailwindscore-archive-runtime"]', mount: mountArchiveRuntime },
	{ key: 'tailwindscore-variation-runtime', selector: '[data-ts-module="tailwindscore-variation-runtime"]', mount: mountVariationRuntime },
	{ key: 'tailwindscore-product-gallery', selector: '[data-ts-module="tailwindscore-product-gallery"]', mount: mountProductGallery },
	{ key: 'mobile-drawer', selector: '[data-ts-module="mobile-drawer"]', mount: mountMobileDrawer },
	{ key: 'sticky-header', selector: '[data-ts-module="sticky-header"]', mount: mountStickyHeader },
	{ key: 'navigation-focus', selector: '[data-ts-module="navigation-focus"]', mount: mountNavigationFocus },
	{ key: 'search-surface', selector: '[data-ts-module="search-surface"]', mount: mountSearchSurface },
	{ key: 'predictive-search', selector: '[data-ts-module="predictive-search"]', mount: mountPredictiveSearch },
	{ key: 'search-focus', selector: '[data-ts-module="search-focus"]', mount: mountSearchFocus },
	{ key: 'cart-drawer', selector: '[data-ts-module="cart-drawer"]', mount: mountCartDrawer },
	{ key: 'account-navigation', selector: '[data-ts-module="account-navigation"]', mount: mountAccountNavigation },
	{ key: 'order-toggle', selector: '[data-ts-module="order-toggle"]', mount: mountOrderToggle },
	{ key: 'account-focus', selector: '[data-ts-module="account-focus"]', mount: mountAccountFocus },
];

const checkoutModuleKeys = new Set<string>([
	'checkout-feedback',
	'checkout-loading',
	'checkout-summary',
	'checkout-payment',
	'checkout-focus',
	'commerce-quantity',
	'feedback-runtime',
]);

function getMountRoots(root: ParentNode, selector: string): HTMLElement[] {
	const matches: HTMLElement[] = [];
	const rootElement = root instanceof HTMLElement ? root : null;

	if (rootElement && rootElement.matches(selector)) {
		matches.push(rootElement);
	}

	root.querySelectorAll<HTMLElement>(selector).forEach((el) => {
		matches.push(el);
	});

	return matches;
}

function mountDefinitionIn(root: ParentNode, definition: ModuleDefinition): void {
	getMountRoots(root, definition.selector).forEach((el) => {
		const existing = mountedModules.get(el);
		if (existing?.has(definition.key)) {
			return;
		}

		definition.mount(el);

		const next = existing ?? new Set<string>();
		next.add(definition.key);
		mountedModules.set(el, next);
	});
}

export function mountRegisteredModulesIn(root: ParentNode = document): void {
	moduleDefinitions.forEach((definition) => {
		mountDefinitionIn(root, definition);
	});
}

export function mountCheckoutModulesIn(root: ParentNode = document): void {
	moduleDefinitions.forEach((definition) => {
		if (!checkoutModuleKeys.has(definition.key)) {
			return;
		}

		mountDefinitionIn(root, definition);
	});
}

export function mountRegisteredModules(): void {
	mountRegisteredModulesIn(document);
}
