type JQueryish = {
	(e: string | Element | Document): JQueryCollection;
};

type JQueryCollection = {
	on(
		events: string,
		selectorOrHandler?: string | ((this: HTMLElement, ev: Event, ...args: unknown[]) => void),
		handler?: (this: HTMLElement, ev: Event, ...args: unknown[]) => void,
	): JQueryCollection;
	off(events?: string): JQueryCollection;
	find(sel: string): JQueryCollection;
	length: number;
	[index: number]: HTMLElement;
};

declare global {
	interface Window {
		jQuery?: JQueryish;
	}
}

function getJQuery(): JQueryish | undefined {
	return typeof window !== 'undefined' ? window.jQuery : undefined;
}

export type VariationSyncHandles = {
	off(): void;
};

/**
 * Bridges WooCommerce variation + gallery jQuery events — does not implement variation matching logic.
 *
 * @param scrollToIndex Scroll main + thumbs + active state (provided by gallery shell).
 */
export function bindGalleryVariationSync(root: HTMLElement, scrollToIndex: (index: number) => void): VariationSyncHandles {
	const $ = getJQuery();
	if (!$) {
		return { off() {} };
	}

	const productEl = root.closest('.product');
	const $root = $(root);

	const onResetSlidePosition = (): void => {
		scrollToIndex(0);
	};

	const onFlexsliderClick = (e: Event): void => {
		const li = (e.target as HTMLElement | null)?.closest?.('li');
		if (!li?.parentElement?.classList.contains('flex-control-nav')) {
			return;
		}
		const ul = li.parentElement;
		const index = Array.prototype.indexOf.call(ul.children, li);
		if (index >= 0) {
			scrollToIndex(index);
		}
	};

	$root.on('woocommerce_gallery_reset_slide_position.ts-gallery', onResetSlidePosition);
	$root.on('flexslider-click.ts-gallery', '.flex-control-nav li img', onFlexsliderClick);

	const forms: JQueryCollection | undefined = productEl ? $(productEl).find('.variations_form') : undefined;

	if (forms && forms.length > 0) {
		for (let i = 0; i < forms.length; i++) {
			const form = forms[i];
			if (!form) {
				continue;
			}
			$(form).on('reset_image.ts-gallery', () => {
				scrollToIndex(0);
			});
		}
	}

	return {
		off(): void {
			$root.off('.ts-gallery');
			if (forms && forms.length > 0) {
				for (let i = 0; i < forms.length; i++) {
					const form = forms[i];
					if (!form) {
						continue;
					}
					$(form).off('.ts-gallery');
				}
			}
		},
	};
}
