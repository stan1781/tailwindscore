function getFocusableElements(root: HTMLElement): HTMLElement[] {
	return Array.from(
		root.querySelectorAll<HTMLElement>('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'),
	).filter((element) => !element.hasAttribute('hidden') && !element.getAttribute('aria-hidden'));
}

export function mount(root: HTMLElement): void {
	if (root.dataset.accountNavigationMounted === '1') {
		return;
	}

	root.dataset.accountNavigationMounted = '1';

	const toggle = root.querySelector<HTMLButtonElement>('[data-account-nav-toggle]');
	const panel = root.querySelector<HTMLElement>('.ts-account-nav__panel');
	const desktopQuery = window.matchMedia('(min-width: 64rem)');
	if (!toggle || !panel) {
		return;
	}

	const close = (): void => {
		toggle.setAttribute('aria-expanded', 'false');
		panel.hidden = true;
	};

	const open = (): void => {
		toggle.setAttribute('aria-expanded', 'true');
		panel.hidden = false;
		window.requestAnimationFrame(() => {
			getFocusableElements(panel)[0]?.focus();
		});
	};

	toggle.addEventListener('click', () => {
		if (desktopQuery.matches) {
			return;
		}

		if (panel.hidden) {
			open();
			return;
		}

		close();
		toggle.focus();
	});

	root.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && !panel.hidden) {
			event.preventDefault();
			close();
			toggle.focus();
		}
	});

	document.addEventListener('click', (event) => {
		if (panel.hidden || desktopQuery.matches) {
			return;
		}

		const target = event.target;
		if (!(target instanceof Node)) {
			return;
		}

		if (!root.contains(target)) {
			close();
		}
	});

	panel.querySelectorAll<HTMLAnchorElement>('a[href]').forEach((link) => {
		link.addEventListener('click', () => {
			if (!desktopQuery.matches) {
				close();
			}
		});
	});

	desktopQuery.addEventListener('change', (event) => {
		if (event.matches) {
			panel.hidden = false;
			toggle.setAttribute('aria-expanded', 'true');
			return;
		}

		close();
	});

	if (desktopQuery.matches) {
		panel.hidden = false;
		toggle.setAttribute('aria-expanded', 'true');
	}
}
