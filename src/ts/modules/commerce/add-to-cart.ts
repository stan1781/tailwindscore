/**
 * Intercept simple-product add-to-cart and send it through the cart mutation pipeline.
 */
import { applyCartFragments, requestCartSurface } from '../cart/cart-state';
import { clearFeedbackValidation, publishToast, setFeedbackBusyState, setFeedbackValidation } from '../feedback';

const ADD_TO_CART_FEEDBACK = {
	validationTitle: 'Please review this selection',
	loadingMessage: 'Adding to bag',
	successMessage: 'Added to bag',
	errorMessage: 'We could not update the bag just now. Please try again.',
	selectionMessage: 'Select an option before adding to bag.',
} as const;

function markButtonsPending(form: HTMLElement, pending: boolean): void {
	const buttons = form.querySelectorAll<HTMLButtonElement>(
		'button.single_add_to_cart_button, button[name="add-to-cart"], button[type="submit"]',
	);
	buttons.forEach((btn) => {
		btn.classList.add('ts-atc-track');
		if (pending) {
			btn.classList.add('ts-btn--loading');
			btn.setAttribute('aria-busy', 'true');
		} else {
			btn.classList.remove('ts-btn--loading');
			btn.removeAttribute('aria-busy');
		}
	});
}

function openCartDrawer(drawer: HTMLElement): void {
	drawer.hidden = false;
	drawer.classList.add('is-open');
	document.documentElement.classList.add('ts-has-cart-open');
	document.body.classList.add('ts-has-cart-open');
	document.querySelectorAll<HTMLElement>('[data-cart-trigger="ts-cart-drawer"]').forEach((trigger) => {
		trigger.setAttribute('aria-expanded', 'true');
	});
}

export function mount(root: HTMLElement): void {
	const forms = root.querySelectorAll<HTMLFormElement>('form.cart, form.variations_form');
	forms.forEach((form) => {
		form.addEventListener('submit', (event) => {
			const submitter =
				event instanceof SubmitEvent && event.submitter instanceof HTMLButtonElement ? event.submitter : null;
			const formData =
				submitter && typeof FormData !== 'undefined' ? new FormData(form, submitter) : new FormData(form);
			const submitterProductId =
				submitter?.name === 'add-to-cart' ? Number(submitter.value || '0') : Number(submitter?.dataset.productId ?? '0');
			const productId = Number(formData.get('add-to-cart') ?? formData.get('product_id') ?? submitterProductId ?? '0');
			const quantity = Math.max(1, Number(formData.get('quantity') ?? '1'));
			const variationId = Math.max(0, Number(formData.get('variation_id') ?? '0'));
			const drawer = document.getElementById('ts-cart-drawer');
			const endpointBase = drawer?.dataset.cartEndpoint;
			const isVariationForm = form.classList.contains('variations_form');

			if (isVariationForm && variationId < 1) {
				setFeedbackValidation(root, root.dataset.feedbackSelectionMessage ?? ADD_TO_CART_FEEDBACK.selectionMessage, {
					title: root.dataset.feedbackValidationTitle ?? ADD_TO_CART_FEEDBACK.validationTitle,
				});
				return;
			}

			if (!drawer || !endpointBase || productId < 1) {
				return;
			}

			event.preventDefault();
			clearFeedbackValidation(root);
			markButtonsPending(form, true);
			setFeedbackBusyState(root, true, {
				message: root.dataset.feedbackLoadingMessage ?? ADD_TO_CART_FEEDBACK.loadingMessage,
			});

			const variation = isVariationForm ? {} as Record<string, string> : undefined;
			if (variation) {
				formData.forEach((value, key) => {
					if (typeof value === 'string' && key.startsWith('attribute_') && value !== '') {
						variation[key] = value;
					}
				});
			}

			void requestCartSurface(`${endpointBase}/add`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					product_id: productId,
					quantity,
					variation_id: variationId,
					variation,
				}),
			})
				.then((payload) => {
					applyCartFragments(drawer, payload);
					openCartDrawer(drawer);
					publishToast({
						message: root.dataset.feedbackSuccessMessage ?? ADD_TO_CART_FEEDBACK.successMessage,
						tone: 'success',
					});
				})
				.catch(() => {
					const message = root.dataset.feedbackErrorMessage ?? ADD_TO_CART_FEEDBACK.errorMessage;
					setFeedbackValidation(root, message, {
						title: root.dataset.feedbackValidationTitle ?? ADD_TO_CART_FEEDBACK.validationTitle,
					});
					publishToast({ message, tone: 'error' });
				})
				.finally(() => {
					markButtonsPending(form, false);
					setFeedbackBusyState(root, false);
				});
		});
	});
}
