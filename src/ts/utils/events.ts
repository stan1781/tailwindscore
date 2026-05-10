/**
 * DOM delegation + unified CustomEvent bus (generic + commerce catalog).
 */

export type DelegateEventMap = {
	click: MouseEvent;
	change: Event;
	submit: SubmitEvent;
	input: Event;
	keydown: KeyboardEvent;
};

export function delegate<K extends keyof DelegateEventMap>(
	root: Document | HTMLElement,
	eventName: K,
	selector: string,
	handler: (event: DelegateEventMap[K], target: HTMLElement) => void,
	options?: AddEventListenerOptions,
): () => void {
	const listener = (event: Event) => {
		const target = event.target;
		if (!(target instanceof Element)) {
			return;
		}
		const match = target.closest(selector);
		if (!(match instanceof HTMLElement)) {
			return;
		}

		const scope = root instanceof Document ? root.documentElement : root;
		if (!scope.contains(match)) {
			return;
		}

		handler(event as DelegateEventMap[K], match);
	};

	root.addEventListener(eventName, listener as EventListener, options);

	return () => {
		root.removeEventListener(eventName, listener as EventListener, options);
	};
}

/** Commerce event payloads (theme coordination only; WC remains source of truth). */
export type CommerceQtyChangeDetail = {
	value: number;
	input: HTMLInputElement;
	root: HTMLElement;
};

export type CommerceCartUpdatedDetail = {
	source: 'wc-jquery' | 'manual';
	fragments?: Record<string, string>;
	cartHash?: string;
};

export type CommerceVariationChangedDetail = {
	variation: Record<string, unknown> | null;
	productId: number;
	form: HTMLElement | null;
};

export type CommerceEventMap = {
	'ts:qty:change': CommerceQtyChangeDetail;
	'ts:cart:updated': CommerceCartUpdatedDetail;
	'ts:variation:changed': CommerceVariationChangedDetail;
};

function dispatch(target: EventTarget, type: string, detail: unknown): void {
	target.dispatchEvent(
		new CustomEvent(type, {
			bubbles: true,
			cancelable: false,
			detail,
		}),
	);
}

/** Emit commerce catalog events (`ts:*`). */
export function emit<K extends keyof CommerceEventMap>(
	target: EventTarget,
	type: K,
	detail: CommerceEventMap[K],
): void {
	dispatch(target, type, detail);
}

/** Subscribe to commerce catalog events; returns unsubscribe. */
export function on<K extends keyof CommerceEventMap>(
	target: EventTarget,
	type: K,
	handler: (event: CustomEvent<CommerceEventMap[K]>) => void,
	options?: AddEventListenerOptions,
): () => void {
	const wrapped: EventListener = (event) => {
		handler(event as CustomEvent<CommerceEventMap[K]>);
	};
	target.addEventListener(type, wrapped, options);
	return () => target.removeEventListener(type, wrapped, options);
}

/** Remove a listener registered with the same function reference (use unsubscribe from `on` when possible). */
export function off(target: EventTarget, type: string, handler: EventListener): void {
	target.removeEventListener(type, handler);
}

/** Generic emit for arbitrary event names (non-catalog). */
export function emitRaw(target: EventTarget, type: string, detail?: unknown): void {
	dispatch(target, type, detail);
}

/** Generic subscribe; returns unsubscribe. */
export function onRaw<T extends Event>(
	target: EventTarget,
	type: string,
	handler: (event: T) => void,
	options?: AddEventListenerOptions,
): () => void {
	const wrapped = (event: Event) => handler(event as T);
	target.addEventListener(type, wrapped, options);
	return () => target.removeEventListener(type, wrapped, options);
}
