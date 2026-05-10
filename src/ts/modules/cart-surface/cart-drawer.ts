import { on } from '../../utils/events';
import { applyCartFragments, requestCartSurface } from './cart-state';
import { clearFeedbackValidation, publishToast, setFeedbackBusyState, setFeedbackValidation } from '../feedback';

function syncTriggers(drawerId: string, open: boolean): void {
	document.querySelectorAll<HTMLElement>(`[data-cart-trigger="${drawerId}"]`).forEach((trigger) => {
		trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
	});
}

export function mount(root: HTMLElement): void {
	const endpointBase = root.dataset.cartEndpoint;
	const drawerId = root.id;
	if (!endpointBase || !drawerId) {
		return;
	}

	let activeTrigger: HTMLElement | null = null;

	const open = (trigger?: HTMLElement): void => {
		activeTrigger = trigger ?? (document.activeElement instanceof HTMLElement ? document.activeElement : null);
		root.hidden = false;
		root.classList.add('is-open');
		document.documentElement.classList.add('ts-has-cart-open');
		document.body.classList.add('ts-has-cart-open');
		syncTriggers(drawerId, true);
		void refresh().catch(() => {});
	};

	const close = (): void => {
		root.classList.remove('is-open');
		document.documentElement.classList.remove('ts-has-cart-open');
		document.body.classList.remove('ts-has-cart-open');
		syncTriggers(drawerId, false);
		window.setTimeout(() => {
			if (!root.classList.contains('is-open')) {
				root.hidden = true;
			}
		}, 240);
		activeTrigger?.focus();
	};

	const refresh = async (): Promise<void> => {
		const payload = await requestCartSurface(endpointBase);
		applyCartFragments(root, payload);
	};

	const surfaceRoot = (): HTMLElement => root.querySelector<HTMLElement>('[data-cart-surface-root]') ?? root;

	const mutate = async (path: 'update' | 'remove', body: Record<string, unknown>, successMessage: string): Promise<void> => {
		const scope = surfaceRoot();
		clearFeedbackValidation(scope);
		setFeedbackBusyState(scope, true, { message: scope.dataset.feedbackLoadingMessage ?? 'Updating bag' });

		try {
			const payload = await requestCartSurface(`${endpointBase}/${path}`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify(body),
			});
			applyCartFragments(root, payload);
			publishToast({ message: successMessage, tone: 'success' });
		} catch {
			const message = scope.dataset.feedbackUpdateErrorMessage ?? 'We could not update the bag just now. Please try again.';
			setFeedbackValidation(scope, message);
			publishToast({ message, tone: 'error' });
		} finally {
			setFeedbackBusyState(scope, false);
		}
	};

	document.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof HTMLElement)) {
			return;
		}

		const trigger = target.closest<HTMLElement>(`[data-cart-trigger="${drawerId}"]`);
		if (trigger) {
			event.preventDefault();
			if (root.classList.contains('is-open')) {
				close();
				return;
			}
			open(trigger);
			return;
		}

		const closer = target.closest<HTMLElement>('[data-cart-close]');
		if (closer && root.contains(closer)) {
			event.preventDefault();
			close();
			return;
		}

		const qtyButton = target.closest<HTMLElement>('[data-cart-qty-step]');
		if (qtyButton && root.contains(qtyButton)) {
			event.preventDefault();
			const qtyRoot = qtyButton.closest<HTMLElement>('[data-cart-item-key]');
			const input = qtyRoot?.querySelector<HTMLInputElement>('[data-cart-qty-input]');
			const key = qtyRoot?.dataset.cartItemKey;
			if (!qtyRoot || !input || !key) {
				return;
			}
			const step = Number(qtyButton.dataset.cartQtyStep ?? '0');
			const current = Number(input.value || '0');
			const next = Math.max(0, current + step);
			input.value = String(next);
			if (next === 0) {
				void mutate('remove', { key }, surfaceRoot().dataset.feedbackItemRemovedMessage ?? 'Removed from bag');
				return;
			}
			void mutate('update', { key, quantity: next }, surfaceRoot().dataset.feedbackItemUpdatedMessage ?? 'Bag updated');
			return;
		}

		const removeLink = target.closest<HTMLElement>('[data-cart-remove]');
		if (removeLink && root.contains(removeLink)) {
			event.preventDefault();
			const qtyRoot = removeLink.closest<HTMLElement>('[data-cart-item-key]');
			const key = qtyRoot?.dataset.cartItemKey;
			if (!key) {
				return;
			}
			void mutate('remove', { key }, surfaceRoot().dataset.feedbackItemRemovedMessage ?? 'Removed from bag');
		}
	});

	root.addEventListener('change', (event) => {
		const target = event.target;
		if (!(target instanceof HTMLInputElement) || !target.matches('[data-cart-qty-input]')) {
			return;
		}
		const qtyRoot = target.closest<HTMLElement>('[data-cart-item-key]');
		const key = qtyRoot?.dataset.cartItemKey;
		if (!key) {
			return;
		}
		const next = Math.max(0, Number(target.value || '0'));
		target.value = String(next);
		if (next === 0) {
			void mutate('remove', { key }, surfaceRoot().dataset.feedbackItemRemovedMessage ?? 'Removed from bag');
			return;
		}
		void mutate('update', { key, quantity: next }, surfaceRoot().dataset.feedbackItemUpdatedMessage ?? 'Bag updated');
	});

	root.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			event.preventDefault();
			close();
		}
	});

	on(document, 'ts:cart:updated', () => {
		void refresh().catch(() => {});
	});
}
