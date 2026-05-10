/**
 * Quantity +/- and keyboard nudge — does not change cart totals (WC server-side).
 */
import { emit } from '../../utils/events';

function parseStep(input: HTMLInputElement): number {
	const s = parseFloat(input.step);
	return Number.isFinite(s) && s > 0 ? s : 1;
}

function clamp(input: HTMLInputElement, next: number): number {
	const minAttr = input.min;
	const maxAttr = input.max;
	const min = minAttr !== '' && !Number.isNaN(parseFloat(minAttr)) ? parseFloat(minAttr) : -Infinity;
	const max = maxAttr !== '' && !Number.isNaN(parseFloat(maxAttr)) ? parseFloat(maxAttr) : Infinity;
	return Math.min(max, Math.max(min, next));
}

function setQty(input: HTMLInputElement, root: HTMLElement, value: number): void {
	const next = clamp(input, value);
	input.value = String(next);
	input.dispatchEvent(new Event('input', { bubbles: true }));
	input.dispatchEvent(new Event('change', { bubbles: true }));
	emit(document, 'ts:qty:change', { value: next, input, root });
}

export function mount(root: HTMLElement): void {
	const input = root.querySelector<HTMLInputElement>('input.qty, input[type="number"]');
	if (!input) {
		return;
	}

	const sync = (): void => {
		const v = parseFloat(input.value);
		const num = Number.isFinite(v) ? v : 0;
		emit(document, 'ts:qty:change', { value: num, input, root });
	};

	root.addEventListener(
		'click',
		(event) => {
			const t = event.target;
			if (!(t instanceof Element)) {
				return;
			}
			const btn = t.closest('.plus, .minus, .ts-qty__btn');
			if (!(btn instanceof HTMLElement) || !root.contains(btn)) {
				return;
			}
			event.preventDefault();
			const step = parseStep(input);
			const current = parseFloat(input.value);
			const base = Number.isFinite(current) ? current : 0;
			if (btn.classList.contains('plus')) {
				setQty(input, root, base + step);
			} else {
				setQty(input, root, base - step);
			}
		},
		true,
	);

	input.addEventListener('change', sync);
	input.addEventListener(
		'keydown',
		(event) => {
			if (event.key !== 'ArrowUp' && event.key !== 'ArrowDown') {
				return;
			}
			event.preventDefault();
			const step = parseStep(input);
			const current = parseFloat(input.value);
			const base = Number.isFinite(current) ? current : 0;
			setQty(input, root, event.key === 'ArrowUp' ? base + step : base - step);
		},
		{ passive: false },
	);
}
