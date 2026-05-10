type JQueryish = {
	(target: string | Element | Document): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: (...args: unknown[]) => void): JQueryCollection;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

function focusPriority(scope: ParentNode): void {
	const invalidControl = scope.querySelector<HTMLElement>(
		'.woocommerce-invalid input, .woocommerce-invalid select, .woocommerce-invalid textarea, .is-feedback-invalid input, .is-feedback-invalid select, .is-feedback-invalid textarea, [aria-invalid="true"]',
	);
	if (invalidControl) {
		invalidControl.focus();
		return;
	}

	const notice = scope.querySelector<HTMLElement>('.ts-feedback-validation, .woocommerce-error, [data-feedback-notice]');
	if (notice) {
		notice.setAttribute('tabindex', '-1');
		notice.focus();
	}
}

export function mount(root: HTMLElement): void {
	if (root.dataset.checkoutFocusMounted === '1') {
		return;
	}

	root.dataset.checkoutFocusMounted = '1';

	const shell = root.closest<HTMLElement>('.ts-checkout-shell') ?? root.parentElement;
	if (!shell) {
		return;
	}

	const form = shell.querySelector<HTMLFormElement>('form.checkout');
	if (!form) {
		return;
	}

	form.addEventListener(
		'invalid',
		() => {
			window.requestAnimationFrame(() => focusPriority(shell));
		},
		true,
	);

	const $ = getJQuery();
	if (!$) {
		return;
	}

	$(document.body).on('checkout_error.ts-checkout-focus', () => {
		window.requestAnimationFrame(() => focusPriority(shell));
	});
}
