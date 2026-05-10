function getControlledShell(trigger: HTMLElement): HTMLElement | null {
	const id = trigger.getAttribute('data-nav-submenu-toggle') ?? trigger.getAttribute('data-nav-trigger');
	if (!id) {
		return null;
	}
	return document.getElementById(id);
}

function getItemTrigger(item: HTMLElement): HTMLElement | null {
	return item.querySelector<HTMLElement>(':scope > .ts-nav__item-row > .ts-nav__submenu-toggle, :scope > .ts-nav__item-row > .ts-nav__link');
}

function getFocusableWithin(root: HTMLElement): HTMLElement[] {
	return Array.from(root.querySelectorAll<HTMLElement>('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'));
}

function setOpen(item: HTMLElement, open: boolean): void {
	item.classList.toggle('is-open', open);
	item.querySelector<HTMLElement>(':scope > .ts-nav__item-row > .ts-nav__submenu-toggle')?.setAttribute('aria-expanded', open ? 'true' : 'false');
}

function closeSiblings(item: HTMLElement): void {
	const siblings = item.parentElement?.children ?? [];
	Array.from(siblings).forEach((sibling) => {
		if (sibling !== item && sibling instanceof HTMLElement && sibling.classList.contains('ts-nav__item--has-children')) {
			setOpen(sibling, false);
		}
	});
}

function focusEdgeInSubmenu(item: HTMLElement, mode: 'first' | 'last'): void {
	const shell = item.querySelector<HTMLElement>(':scope > .ts-nav__submenu-shell');
	if (!shell) {
		return;
	}
	const focusables = getFocusableWithin(shell);
	if (focusables.length === 0) {
		return;
	}
	const first = focusables[0];
	const last = focusables[focusables.length - 1];
	if (!first || !last) {
		return;
	}
	if (mode === 'first') {
		first.focus();
		return;
	}
	last.focus();
}

export function mount(root: HTMLElement): void {
	root.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof HTMLElement)) {
			return;
		}

		const toggle = target.closest<HTMLElement>('[data-nav-submenu-toggle]');
		if (!toggle || !root.contains(toggle)) {
			return;
		}

		event.preventDefault();
		const item = toggle.closest<HTMLElement>('.ts-nav__item--has-children');
		if (!item) {
			return;
		}

		const nextOpen = !item.classList.contains('is-open');
		closeSiblings(item);
		setOpen(item, nextOpen);
	});

	root.addEventListener('keydown', (event) => {
		const target = event.target;
		if (!(target instanceof HTMLElement)) {
			return;
		}

		const item = target.closest<HTMLElement>('.ts-nav__item--has-children');
		if (!item || !root.contains(item)) {
			return;
		}

		if (event.key === 'Escape') {
			setOpen(item, false);
			getItemTrigger(item)?.focus();
			return;
		}

		if (event.key === 'ArrowDown') {
			const controlled = getControlledShell(target);
			if (!controlled) {
				return;
			}
			event.preventDefault();
			closeSiblings(item);
			setOpen(item, true);
			focusEdgeInSubmenu(item, 'first');
			return;
		}

		if (event.key === 'ArrowUp') {
			const controlled = getControlledShell(target);
			if (!controlled) {
				return;
			}
			event.preventDefault();
			closeSiblings(item);
			setOpen(item, true);
			focusEdgeInSubmenu(item, 'last');
			return;
		}

		if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
			const row = item.parentElement;
			if (!row) {
				return;
			}
			const siblings = Array.from(row.children).filter(
				(el): el is HTMLElement => el instanceof HTMLElement && el.classList.contains('ts-nav__item'),
			);
			const currentIndex = siblings.indexOf(item);
			if (currentIndex < 0) {
				return;
			}
			const delta = event.key === 'ArrowRight' ? 1 : -1;
			const next = siblings[currentIndex + delta];
			if (!next) {
				return;
			}
			event.preventDefault();
			getItemTrigger(next)?.focus();
		}
	});

	if (root.dataset.navContext !== 'desktop') {
		return;
	}

	document.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof Node) || root.contains(target)) {
			return;
		}
		root.querySelectorAll<HTMLElement>('.ts-nav__item.is-open').forEach((item) => setOpen(item, false));
	});
}
