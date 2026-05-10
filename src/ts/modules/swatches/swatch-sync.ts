/**
 * Swatch ↔ 原生 `<select>` 同步；监听 WC `update_variation_values`。
 */

import { bindSwatchKeyboard, updateSwatchRovingTabindex } from './swatch-keyboard';

type JQueryish = {
	(e: Element): JQueryCollection;
};

type JQueryCollection = {
	on(events: string, handler: () => void): JQueryCollection;
	off(events?: string): JQueryCollection;
};

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? (window as unknown as { jQuery?: JQueryish }).jQuery : undefined;
}

function resolveSelect(group: HTMLElement, form: HTMLFormElement): HTMLSelectElement | null {
	const id = group.dataset.selectId;
	if (!id) {
		return null;
	}
	const el = document.getElementById(id);
	if (!(el instanceof HTMLSelectElement) || !form.contains(el)) {
		return null;
	}
	return el;
}

function getButtons(group: HTMLElement): HTMLButtonElement[] {
	return Array.from(group.querySelectorAll<HTMLButtonElement>('[data-ts-swatch]'));
}

function primaryTabTarget(buttons: HTMLButtonElement[], select: HTMLSelectElement): HTMLButtonElement | null {
	const val = select.value;
	const enabled = buttons.filter((b) => b.getAttribute('aria-disabled') !== 'true');
	if (val !== '') {
		const match = enabled.find((b) => b.getAttribute('data-value') === val);
		if (match) {
			return match;
		}
	}
	return enabled[0] ?? null;
}

function syncGroup(group: HTMLElement, form: HTMLFormElement): void {
	const select = resolveSelect(group, form);
	if (!select) {
		return;
	}
	const buttons = getButtons(group);
	const val = select.value;

	buttons.forEach((btn) => {
		const v = btn.getAttribute('data-value') ?? '';
		const opt = Array.from(select.options).find((o) => o.value === v);
		const unavailable = !opt || opt.disabled;
		const isSelected = val !== '' && val === v;
		btn.classList.toggle('is-selected', isSelected);
		btn.classList.toggle('is-unavailable', unavailable);
		btn.setAttribute('aria-checked', isSelected ? 'true' : 'false');
		btn.setAttribute('aria-disabled', unavailable ? 'true' : 'false');
	});

	updateSwatchRovingTabindex(buttons, primaryTabTarget(buttons, select));
}

function initGroup(group: HTMLElement, form: HTMLFormElement): () => void {
	const select = resolveSelect(group, form);
	if (!select) {
		return () => {};
	}

	const runSync = (): void => {
		syncGroup(group, form);
	};

	const activate = (btn: HTMLButtonElement): void => {
		const v = btn.getAttribute('data-value');
		if (v === null) {
			return;
		}
		if (btn.getAttribute('aria-disabled') === 'true') {
			return;
		}
		select.value = v;
		select.dispatchEvent(new Event('change', { bubbles: true }));
		runSync();
	};

	const ac = new AbortController();

	group.addEventListener(
		'click',
		(e) => {
			const btn = (e.target as HTMLElement | null)?.closest?.<HTMLButtonElement>('[data-ts-swatch]');
			if (!btn || !group.contains(btn)) {
				return;
			}
			e.preventDefault();
			activate(btn);
		},
		{ signal: ac.signal },
	);

	select.addEventListener('change', runSync, { signal: ac.signal });

	runSync();

	const unKey = bindSwatchKeyboard(group, () => getButtons(group), activate);

	return () => {
		ac.abort();
		unKey();
	};
}

/**
 * 在 Variable 产品容器内挂载所有 `[data-ts-swatch-group]`。
 */
export function mountSwatchGroups(container: HTMLElement): () => void {
	const form = container.querySelector<HTMLFormElement>('form.variations_form');
	if (!form) {
		return () => {};
	}

	const groups = container.querySelectorAll<HTMLElement>('[data-ts-swatch-group]');
	const cleanups: Array<() => void> = [];

	groups.forEach((group) => {
		cleanups.push(initGroup(group, form));
	});

	const $ = getJQuery();
	if ($ && groups.length > 0) {
		const $f = $(form);
		const refresh = (): void => {
			groups.forEach((g) => {
				syncGroup(g, form);
			});
		};
		$f.on('update_variation_values.ts-swatch', refresh);
		$f.on('check_variations.ts-swatch', refresh);
		cleanups.push(() => {
			$f.off('.ts-swatch');
		});
	}

	return () => {
		cleanups.forEach((fn) => fn());
	};
}
