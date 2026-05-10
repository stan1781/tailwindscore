function getFocusableElements(root: HTMLElement): HTMLElement[] {
	return Array.from(
		root.querySelectorAll<HTMLElement>(
			'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
		),
	).filter((el) => !el.hasAttribute('hidden') && !el.getAttribute('aria-hidden'));
}

export function mount(root: HTMLElement): void {
	const drawerId = root.dataset.drawerId;
	if (!drawerId) {
		return;
	}

	const panel = root.querySelector<HTMLElement>('.ts-mobile-drawer__panel');
	if (!panel) {
		return;
	}

	const toggles = Array.from(document.querySelectorAll<HTMLElement>(`[data-drawer-toggle="${drawerId}"]`));
	const closers = Array.from(root.querySelectorAll<HTMLElement>('[data-drawer-close]'));
	const desktopQuery = window.matchMedia('(min-width: 64rem)');

	let activeTrigger: HTMLElement | null = null;

	const syncToggleState = (open: boolean): void => {
		toggles.forEach((toggle) => {
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	};

	const open = (trigger?: HTMLElement): void => {
		activeTrigger = trigger ?? (document.activeElement instanceof HTMLElement ? document.activeElement : null);
		root.hidden = false;
		root.classList.add('is-open');
		root.setAttribute('aria-hidden', 'false');
		document.documentElement.classList.add('ts-has-drawer-open');
		document.body.classList.add('ts-has-drawer-open');
		syncToggleState(true);

		window.requestAnimationFrame(() => {
			getFocusableElements(panel)[0]?.focus();
		});
	};

	const close = (): void => {
		root.classList.remove('is-open');
		root.setAttribute('aria-hidden', 'true');
		document.documentElement.classList.remove('ts-has-drawer-open');
		document.body.classList.remove('ts-has-drawer-open');
		syncToggleState(false);

		window.setTimeout(() => {
			if (!root.classList.contains('is-open')) {
				root.hidden = true;
			}
		}, 260);

		activeTrigger?.focus();
	};

	toggles.forEach((toggle) => {
		toggle.addEventListener('click', () => {
			if (root.classList.contains('is-open')) {
				close();
				return;
			}
			open(toggle);
		});
	});

	closers.forEach((closer) => {
		closer.addEventListener('click', () => close());
	});

	root.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			event.preventDefault();
			close();
			return;
		}

		if (event.key !== 'Tab') {
			return;
		}

		const focusables = getFocusableElements(panel);
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

	document.addEventListener('click', (event) => {
		if (!root.classList.contains('is-open')) {
			return;
		}
		const target = event.target;
		if (!(target instanceof Node)) {
			return;
		}
		if (!panel.contains(target) && !toggles.some((toggle) => toggle.contains(target))) {
			close();
		}
	});

	desktopQuery.addEventListener('change', (event) => {
		if (event.matches) {
			close();
		}
	});
}
