import { applyCartFragments, CartSurfaceRequestError, requestCartSurface } from './cart-state';
import { clearFeedbackValidation, publishToast, setFeedbackBusyState, setFeedbackValidation } from '../feedback';

const CART_FEEDBACK = {
	validationTitle: 'Please review your bag',
	loadingMessage: 'Updating bag',
	updateErrorMessage: 'We could not update the bag just now. Please try again.',
	itemUpdatedMessage: 'Bag updated',
	itemRemovedMessage: 'Removed from bag',
} as const;

type FocusSnapshot = {
	type: 'qty' | 'remove' | 'close';
	key?: string;
};

function getFocusable(root: HTMLElement): HTMLElement[] {
	return Array.from(
		root.querySelectorAll<HTMLElement>(
			'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
		),
	);
}

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
	let pendingFocus: FocusSnapshot | null = null;

	const trapFocus = (event: KeyboardEvent): void => {
		if (event.key !== 'Tab' || !root.classList.contains('is-open')) {
			return;
		}

		const panel = root.querySelector<HTMLElement>('.ts-cart-drawer__panel');
		if (!panel) {
			return;
		}

		const focusables = getFocusable(panel);
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
	};

	const restoreFocusAfterRefresh = (): void => {
		const snapshot = pendingFocus;
		pendingFocus = null;

		if (!snapshot) {
			return;
		}

		if (snapshot.type === 'qty' && snapshot.key) {
			const nextInput = root.querySelector<HTMLElement>(`[data-cart-item-key="${snapshot.key}"] [data-cart-qty-input]`);
			if (nextInput) {
				nextInput.focus();
				return;
			}
		}

		if (snapshot.type === 'remove' && snapshot.key) {
			const nextRemove = root.querySelector<HTMLElement>(`[data-cart-item-key="${snapshot.key}"] [data-cart-remove]`);
			if (nextRemove) {
				nextRemove.focus();
				return;
			}
		}

		const fallback = root.querySelector<HTMLElement>('[data-cart-close]') ?? root.querySelector<HTMLElement>('.ts-cart-drawer__panel');
		fallback?.focus();
	};

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
		restoreFocusAfterRefresh();
	};

	const surfaceRoot = (): HTMLElement => root.querySelector<HTMLElement>('[data-cart-surface-root]') ?? root;

	const mutate = async (
		path: 'update' | 'remove',
		body: Record<string, unknown>,
		successMessage: string,
		focusSnapshot?: FocusSnapshot,
	): Promise<void> => {
		const scope = surfaceRoot();
		pendingFocus = focusSnapshot ?? null;
		clearFeedbackValidation(scope);
		setFeedbackBusyState(scope, true, { message: scope.dataset.feedbackLoadingMessage ?? CART_FEEDBACK.loadingMessage });

		try {
			const payload = await requestCartSurface(`${endpointBase}/${path}`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify(body),
			});
			applyCartFragments(root, payload);
			restoreFocusAfterRefresh();
			publishToast({ message: successMessage, tone: 'success', announce: false });
		} catch (error) {
			const message =
				error instanceof CartSurfaceRequestError && error.responseMessage
					? error.responseMessage
					: scope.dataset.feedbackUpdateErrorMessage ?? CART_FEEDBACK.updateErrorMessage;
			setFeedbackValidation(scope, message, {
				title: scope.dataset.feedbackValidationTitle ?? CART_FEEDBACK.validationTitle,
			});
			publishToast({ message, tone: 'error', announce: false });
			restoreFocusAfterRefresh();
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
				void mutate('remove', { key }, surfaceRoot().dataset.feedbackItemRemovedMessage ?? CART_FEEDBACK.itemRemovedMessage, {
					type: 'close',
				});
				return;
			}
			void mutate('update', { key, quantity: next }, surfaceRoot().dataset.feedbackItemUpdatedMessage ?? CART_FEEDBACK.itemUpdatedMessage, {
				type: 'qty',
				key,
			});
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
			void mutate('remove', { key }, surfaceRoot().dataset.feedbackItemRemovedMessage ?? CART_FEEDBACK.itemRemovedMessage, {
				type: 'close',
			});
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
			void mutate('remove', { key }, surfaceRoot().dataset.feedbackItemRemovedMessage ?? CART_FEEDBACK.itemRemovedMessage, {
				type: 'close',
			});
			return;
		}
		void mutate('update', { key, quantity: next }, surfaceRoot().dataset.feedbackItemUpdatedMessage ?? CART_FEEDBACK.itemUpdatedMessage, {
			type: 'qty',
			key,
		});
	});

	root.addEventListener('keydown', (event) => {
		trapFocus(event);

		if (event.key === 'Escape') {
			event.preventDefault();
			close();
		}
	});
}
