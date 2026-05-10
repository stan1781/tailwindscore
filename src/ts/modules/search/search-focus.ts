function getFocusable(root: HTMLElement): HTMLElement[] {
	return Array.from(
		root.querySelectorAll<HTMLElement>(
			'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
		),
	);
}

export function mount(root: HTMLElement): void {
	const surface = root.closest<HTMLElement>('.ts-search-surface');
	if (!surface) {
		return;
	}

	root.addEventListener('keydown', (event) => {
		if (event.key !== 'Tab' || !surface.classList.contains('is-open')) {
			return;
		}

		const focusables = getFocusable(root);
		if (focusables.length === 0) {
			return;
		}

		const first = focusables[0];
		const last = focusables[focusables.length - 1];
		if (!first || !last) {
			return;
		}

		const active = document.activeElement;

		if (event.shiftKey && active === first) {
			event.preventDefault();
			last.focus();
		} else if (!event.shiftKey && active === last) {
			event.preventDefault();
			first.focus();
		}
	});
}
