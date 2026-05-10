import { setFeedbackBusyState } from '../feedback';
import { mountRegisteredModulesIn } from '../../bootstrap-registry';

type JQueryish = {
	(target: string | Element | Document): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: (...args: unknown[]) => void): JQueryCollection;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

export function mount(root: HTMLElement): void {
	if (root.dataset.checkoutLoadingMounted === '1') {
		return;
	}

	root.dataset.checkoutLoadingMounted = '1';

	const $ = getJQuery();
	if (!$) {
		return;
	}

	const $body = $(document.body);
	const message = root.dataset.checkoutLoadingMessage ?? 'Updating checkout';

	$body.on('update_checkout.ts-checkout-loading', () => {
		root.setAttribute('aria-busy', 'true');
		setFeedbackBusyState(root, true, { message, announce: false });
	});

	$body.on('updated_checkout.ts-checkout-loading checkout_error.ts-checkout-loading', () => {
		root.setAttribute('aria-busy', 'false');
		setFeedbackBusyState(root, false);
		mountRegisteredModulesIn(root);
	});
}
