import { emitRaw } from '../../utils/events';

export function mount(root: HTMLElement): void {
	const input = root.querySelector<HTMLInputElement>('[data-search-input]');
	const surface = root.closest<HTMLElement>('.ts-search-surface');
	const resultsHost = root.querySelector<HTMLElement>('[data-search-results]');
	const endpoint = surface?.dataset.searchEndpoint;
	const loadingTemplate = surface?.querySelector<HTMLTemplateElement>('[data-search-loading-template]');
	const unavailableTemplate = surface?.querySelector<HTMLTemplateElement>('[data-search-unavailable-template]');

	if (!input || !surface || !resultsHost || !endpoint) {
		return;
	}

	let controller: AbortController | null = null;
	let timer: number | null = null;
	let lastQuery = '';

	const renderTemplate = (template: HTMLTemplateElement | null | undefined): void => {
		if (!template) {
			return;
		}

		resultsHost.innerHTML = template.innerHTML;
		resultsHost.removeAttribute('hidden');
	};

	const reset = (): void => {
		resultsHost.innerHTML = '';
		resultsHost.setAttribute('hidden', 'true');
		resultsHost.setAttribute('aria-busy', 'false');
		emitRaw(surface, 'ts:search:query-change', { query: '', hasResults: false });
	};

	const run = async (query: string): Promise<void> => {
		lastQuery = query;
		controller?.abort();
		controller = new AbortController();
		emitRaw(surface, 'ts:search:query-change', { query, hasResults: false });
		renderTemplate(loadingTemplate);
		resultsHost.setAttribute('aria-busy', 'true');

		try {
			const url = new URL(endpoint);
			url.searchParams.set('q', query);

			const response = await fetch(url.toString(), {
				signal: controller.signal,
				headers: { Accept: 'application/json' },
			});

			if (!response.ok) {
				throw new Error(`Search request failed: ${response.status}`);
			}

			const payload = (await response.json()) as { html?: string };
			resultsHost.innerHTML = payload.html ?? '';
			resultsHost.removeAttribute('hidden');
			resultsHost.setAttribute('aria-busy', 'false');
			emitRaw(surface, 'ts:search:query-change', { query, hasResults: true });
		} catch (error) {
			if ((error as Error).name === 'AbortError') {
				return;
			}
			renderTemplate(unavailableTemplate);
			resultsHost.setAttribute('aria-busy', 'false');
		}
	};

	input.addEventListener('input', () => {
		const query = input.value.trim();
		if (timer) {
			window.clearTimeout(timer);
		}

		if (!query) {
			reset();
			return;
		}

		timer = window.setTimeout(() => {
			void run(query);
		}, 120);
	});

	input.form?.addEventListener('submit', () => {
		emitRaw(surface, 'ts:search:submit-query', { query: input.value.trim() });
	});

	resultsHost.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof Element)) {
			return;
		}

		const retry = target.closest<HTMLElement>('[data-search-retry]');
		if (!retry || !lastQuery) {
			return;
		}

		event.preventDefault();
		void run(lastQuery);
	});
}
