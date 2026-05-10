type SearchSurfaceState = {
	root: HTMLElement;
	panel: HTMLElement;
	input: HTMLInputElement | null;
	results: HTMLElement | null;
	defaultState: HTMLElement | null;
	triggerSelectors: string;
};

function setRecentSearch(query: string): void {
	const normalized = query.trim();
	if (!normalized) {
		return;
	}

	const key = 'tailwindscore:recent-searches';
	const existing = window.localStorage.getItem(key);
	const parsed = existing ? (JSON.parse(existing) as string[]) : [];
	const next = [normalized, ...parsed.filter((item) => item.toLowerCase() !== normalized.toLowerCase())].slice(0, 5);
	window.localStorage.setItem(key, JSON.stringify(next));
}

function renderRecentSearches(root: HTMLElement): void {
	const key = 'tailwindscore:recent-searches';
	const host = root.querySelector<HTMLElement>('[data-recent-searches]');
	const list = root.querySelector<HTMLElement>('[data-recent-searches-list]');
	if (!host || !list) {
		return;
	}

	const existing = window.localStorage.getItem(key);
	const parsed = existing ? (JSON.parse(existing) as string[]) : [];
	if (parsed.length === 0) {
		host.hidden = true;
		list.innerHTML = '';
		return;
	}

	list.innerHTML = parsed
		.map(
			(term) =>
				`<a class="ts-search-default__chip" href="${window.location.origin}/?s=${encodeURIComponent(term)}&post_type=product">${term}</a>`,
		)
		.join('');
	host.hidden = false;
}

function syncTriggers(surfaceId: string, open: boolean): void {
	document.querySelectorAll<HTMLElement>(`[data-search-trigger="${surfaceId}"]`).forEach((trigger) => {
		trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
	});
}

export function mount(root: HTMLElement): void {
	const panel = root.querySelector<HTMLElement>('.ts-search-surface__panel');
	if (!panel) {
		return;
	}

	const input = root.querySelector<HTMLInputElement>('[data-search-input]');
	const results = root.querySelector<HTMLElement>('[data-search-results]');
	const defaultState = root.querySelector<HTMLElement>('[data-search-default-state]');
	const surfaceId = root.id;
	let lastTrigger: HTMLElement | null = null;

	const open = (trigger?: HTMLElement): void => {
		lastTrigger = trigger ?? (document.activeElement instanceof HTMLElement ? document.activeElement : null);
		root.hidden = false;
		root.classList.add('is-open');
		document.documentElement.classList.add('ts-has-search-open');
		document.body.classList.add('ts-has-search-open');
		syncTriggers(surfaceId, true);
		renderRecentSearches(root);
		window.requestAnimationFrame(() => input?.focus());
	};

	const close = (): void => {
		root.classList.remove('is-open');
		document.documentElement.classList.remove('ts-has-search-open');
		document.body.classList.remove('ts-has-search-open');
		syncTriggers(surfaceId, false);
		window.setTimeout(() => {
			if (!root.classList.contains('is-open')) {
				root.hidden = true;
			}
		}, 240);
		lastTrigger?.focus();
	};

	document.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof Element)) {
			return;
		}

		const trigger = target.closest<HTMLElement>(`[data-search-trigger="${surfaceId}"]`);
		if (trigger) {
			event.preventDefault();
			if (root.classList.contains('is-open')) {
				close();
				return;
			}
			if (document.getElementById('ts-site-mobile-drawer')?.classList.contains('is-open')) {
				document.querySelector<HTMLElement>('#ts-site-mobile-drawer [data-drawer-close]')?.click();
			}
			open(trigger);
			return;
		}

		const closer = target.closest<HTMLElement>('[data-search-close]');
		if (closer && root.contains(closer)) {
			event.preventDefault();
			close();
		}
	});

	root.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			event.preventDefault();
			close();
		}
	});

	root.querySelector<HTMLFormElement>('form[role="search"]')?.addEventListener('submit', () => {
		if (input?.value) {
			setRecentSearch(input.value);
		}
	});

	root.addEventListener('ts:search:query-change', (event) => {
		const detail = (event as CustomEvent<{ query: string; hasResults: boolean }>).detail;
		if (detail.query.trim()) {
			defaultState?.setAttribute('hidden', 'true');
			results?.removeAttribute('hidden');
			return;
		}
		results?.setAttribute('hidden', 'true');
		defaultState?.removeAttribute('hidden');
		renderRecentSearches(root);
	});

	root.addEventListener('ts:search:submit-query', (event) => {
		const detail = (event as CustomEvent<{ query: string }>).detail;
		setRecentSearch(detail.query);
	});
}
