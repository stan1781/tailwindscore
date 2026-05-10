function getFirstFocusable(root: HTMLElement): HTMLElement | null {
	return root.querySelector<HTMLElement>('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
}

export function mount(root: HTMLElement): void {
	if (root.dataset.orderToggleMounted === '1') {
		return;
	}

	root.dataset.orderToggleMounted = '1';

	const toggle = root.querySelector<HTMLButtonElement>('[data-account-order-toggle]');
	const detail = root.querySelector<HTMLElement>('[data-account-order-detail]');
	if (!toggle || !detail) {
		return;
	}

	toggle.addEventListener('click', () => {
		const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
		toggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
		detail.hidden = isExpanded;

		if (!isExpanded) {
			window.requestAnimationFrame(() => {
				getFirstFocusable(detail)?.focus();
			});
		}
	});
}
