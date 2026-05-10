import { announceFeedback, mountLiveRegion } from './feedback-live-region';
import { mountLoading, setFeedbackBusyState } from './feedback-loading';
import { clearFeedbackValidation, mountValidation, setFeedbackValidation } from './feedback-validation';
import { mountToastRegion, publishToast } from './feedback-toast';

const DEFAULT_NOTICE_DISMISS_MS = 8000;

function prefersReducedMotion(): boolean {
	return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function resolveNoticeTone(el: HTMLElement): 'info' | 'success' | 'error' {
	if (el.classList.contains('woocommerce-message')) {
		return 'success';
	}
	if (el.classList.contains('woocommerce-error')) {
		return 'error';
	}
	return 'info';
}

function enhanceNotice(el: HTMLElement, root: HTMLElement): void {
	if (el.dataset.feedbackEnhanced === '1') {
		return;
	}

	el.dataset.feedbackEnhanced = '1';

	const tone = resolveNoticeTone(el);
	el.classList.add('ts-feedback-notice', `ts-feedback-notice--${tone}`);
	el.setAttribute('role', el.getAttribute('role') || (tone === 'error' ? 'alert' : 'status'));
	el.setAttribute('aria-live', el.getAttribute('aria-live') || (tone === 'error' ? 'assertive' : 'polite'));

	const rawDuration = el.dataset.feedbackDismiss ?? root.dataset.feedbackDismiss ?? '';
	const parsedDuration = Number.parseInt(rawDuration, 10);
	const duration = Number.isFinite(parsedDuration) ? parsedDuration : DEFAULT_NOTICE_DISMISS_MS;

	if (duration <= 0 || prefersReducedMotion()) {
		return;
	}

	window.setTimeout(() => {
		if (!document.contains(el)) {
			return;
		}

		el.hidden = true;
		el.dataset.feedbackDismissed = '1';
	}, duration);
}

function mountNoticeRegion(root: HTMLElement): void {
	const scan = (): void => {
		root
			.querySelectorAll<HTMLElement>('.woocommerce-message, .woocommerce-error, .woocommerce-info, [data-feedback-notice]')
			.forEach((node) => enhanceNotice(node, root));
	};

	scan();
	new MutationObserver(scan).observe(root, { childList: true, subtree: true });
	mountValidation(root);
	mountLoading(root);
}

export function mount(root: HTMLElement): void {
	const role = root.dataset.feedbackRole ?? 'notice-region';

	if (role === 'toast-region') {
		mountToastRegion(root);
		return;
	}

	if (role === 'live-region') {
		mountLiveRegion(root);
		return;
	}

	mountNoticeRegion(root);
}

export { announceFeedback, clearFeedbackValidation, publishToast, setFeedbackBusyState, setFeedbackValidation };
