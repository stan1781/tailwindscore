function findPriorityTarget(root: HTMLElement): HTMLElement | null {
	return (
		root.querySelector<HTMLElement>(
			'.woocommerce-error, .woocommerce-Message, .woocommerce-message, .woocommerce-NoticeGroup, .woocommerce-invalid input, [aria-invalid="true"]',
		) ?? root.querySelector<HTMLElement>('[data-account-focus-target]')
	);
}

export function mount(root: HTMLElement): void {
	if (root.dataset.accountFocusMounted === '1') {
		return;
	}

	root.dataset.accountFocusMounted = '1';

	const target = findPriorityTarget(root);
	if (!target || target === root.querySelector('[data-account-focus-target]')) {
		return;
	}

	window.requestAnimationFrame(() => {
		target.setAttribute('tabindex', '-1');
		target.focus();
	});
}
