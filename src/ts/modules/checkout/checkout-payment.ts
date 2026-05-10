type JQueryish = {
	(target: string | Element | Document): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: (...args: unknown[]) => void): JQueryCollection;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

function syncMethods(root: HTMLElement): void {
	const methods = Array.from(root.querySelectorAll<HTMLElement>('.ts-checkout-payment__method'));

	methods.forEach((method) => {
		const radio = method.querySelector<HTMLInputElement>('input[name="payment_method"]');
		const box = method.querySelector<HTMLElement>('.payment_box');
		const selected = Boolean(radio?.checked);

		method.classList.toggle('is-selected', selected);

		if (!box) {
			return;
		}

		box.classList.toggle('is-expanded', selected);
		box.classList.toggle('is-collapsed', !selected);

		if (selected) {
			box.hidden = false;
			box.style.maxHeight = `${box.scrollHeight}px`;
			return;
		}

		box.style.maxHeight = '0px';
		window.setTimeout(() => {
			if (!method.classList.contains('is-selected')) {
				box.hidden = true;
			}
		}, 240);
	});
}

export function mount(root: HTMLElement): void {
	if (root.dataset.checkoutPaymentMounted === '1') {
		return;
	}

	root.dataset.checkoutPaymentMounted = '1';
	syncMethods(root);

	root.addEventListener('change', (event) => {
		const target = event.target;
		if (!(target instanceof HTMLInputElement) || target.name !== 'payment_method') {
			return;
		}

		syncMethods(root);
	});

	const $ = getJQuery();
	if (!$) {
		return;
	}

	$(document.body).on('updated_checkout.ts-checkout-payment', () => {
		syncMethods(root);
	});
}
