type JQueryish = {
	(target: string | Element | Document): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: (...args: unknown[]) => void): JQueryCollection;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

function syncItemCount(root: HTMLElement): void {
	const countNode = root.querySelector<HTMLElement>('[data-checkout-summary-count]');
	if (!countNode) {
		return;
	}

	const rows = root.querySelectorAll('tbody tr.cart_item').length;
	countNode.textContent = String(rows);
}

export function mount(root: HTMLElement): void {
	if (root.dataset.checkoutSummaryMounted === '1') {
		return;
	}

	root.dataset.checkoutSummaryMounted = '1';
	syncItemCount(root);

	const $ = getJQuery();
	if (!$) {
		return;
	}

	const $body = $(document.body);
	$body.on('update_checkout.ts-checkout-summary', () => {
		root.classList.add('is-refreshing');
		root.setAttribute('aria-busy', 'true');
	});
	$body.on('updated_checkout.ts-checkout-summary checkout_error.ts-checkout-summary', () => {
		root.classList.remove('is-refreshing');
		root.setAttribute('aria-busy', 'false');
		syncItemCount(root);
	});
}
