import { announceFeedback } from './feedback-live-region';

export type FeedbackToastTone = 'info' | 'success' | 'error';

export type FeedbackToastOptions = {
	message: string;
	tone?: FeedbackToastTone;
	duration?: number;
	announce?: boolean;
};

const DEFAULT_DISMISS_MS = 4200;

function prefersReducedMotion(): boolean {
	return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function createToastHost(): HTMLElement {
	const host = document.createElement('div');
	host.id = 'ts-feedback-toast-generated';
	host.className = 'ts-feedback-toast-region';
	host.setAttribute('data-ts-module', 'feedback-runtime');
	host.setAttribute('data-feedback-role', 'toast-region');
	host.setAttribute('data-feedback-toast-host', '');
	host.setAttribute('data-feedback-dismiss', String(DEFAULT_DISMISS_MS));
	host.setAttribute('aria-label', 'Store feedback');
	document.body.append(host);
	return host;
}

function resolveToastHost(): HTMLElement {
	return document.querySelector<HTMLElement>('[data-feedback-toast-host]') ?? createToastHost();
}

function dismissToast(toast: HTMLElement): void {
	toast.hidden = true;
	toast.dataset.feedbackDismissed = '1';

	const remove = (): void => {
		toast.remove();
	};

	if (prefersReducedMotion()) {
		remove();
		return;
	}

	window.setTimeout(remove, 220);
}

function queueDismiss(toast: HTMLElement, duration: number): void {
	if (duration <= 0) {
		return;
	}

	window.setTimeout(() => {
		if (document.contains(toast)) {
			dismissToast(toast);
		}
	}, duration);
}

function buildToast(options: FeedbackToastOptions): HTMLElement {
	const tone = options.tone ?? 'info';
	const toast = document.createElement('div');
	toast.className = `ts-feedback-toast ts-feedback-toast--${tone} is-entering`;
	toast.setAttribute('data-feedback-toast', '');
	toast.setAttribute('role', tone === 'error' ? 'alert' : 'status');
	toast.setAttribute('aria-live', tone === 'error' ? 'assertive' : 'polite');

	const body = document.createElement('div');
	body.className = 'ts-feedback-toast__body';

	const message = document.createElement('p');
	message.className = 'ts-feedback-toast__message';
	message.textContent = options.message.trim();

	const dismiss = document.createElement('button');
	dismiss.type = 'button';
	dismiss.className = 'ts-feedback-toast__dismiss';
	dismiss.setAttribute('data-feedback-dismiss-trigger', '');
	dismiss.setAttribute('aria-label', 'Dismiss feedback');
	dismiss.textContent = 'x';

	body.append(message);
	toast.append(body, dismiss);

	window.requestAnimationFrame(() => {
		toast.classList.remove('is-entering');
	});

	return toast;
}

export function publishToast(options: FeedbackToastOptions): void {
	const message = options.message.trim();
	if (!message) {
		return;
	}

	const host = resolveToastHost();
	const fallbackDuration = Number.parseInt(host.dataset.feedbackDismiss ?? String(DEFAULT_DISMISS_MS), 10);
	const duration = typeof options.duration === 'number' ? options.duration : fallbackDuration;
	const toast = buildToast({ ...options, message });

	host.append(toast);
	queueDismiss(toast, duration);
	if (options.announce !== false) {
		announceFeedback(message, options.tone === 'error' ? 'assertive' : 'polite');
	}
}

export function mountToastRegion(root: HTMLElement): void {
	if (root.dataset.feedbackToastMounted === '1') {
		return;
	}

	root.dataset.feedbackToastMounted = '1';

	root.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof HTMLElement)) {
			return;
		}

		const trigger = target.closest<HTMLElement>('[data-feedback-dismiss-trigger]');
		const toast = trigger?.closest<HTMLElement>('[data-feedback-toast]');
		if (!trigger || !toast) {
			return;
		}

		event.preventDefault();
		dismissToast(toast);
	});
}
