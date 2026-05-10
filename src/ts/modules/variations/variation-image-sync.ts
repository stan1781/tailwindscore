/**
 * Gallery index sync from variation `image_id` — uses TailwindScore gallery scroll API.
 */
import { scrollGalleryToIndex } from '../gallery/gallery-api';
import { slideIndexForGalleryAttachment } from '../../utils/gallery-slide-index';
import { on, type CommerceEventMap } from '../../utils/events';
import { resolveVariationsFormFromHost } from './form-root';

function resolveGalleryInProduct(form: HTMLElement): HTMLElement | null {
	const product = form.closest('.product');
	return product?.querySelector<HTMLElement>('.ts-gallery') ?? null;
}

function syncGalleryForVariation(form: HTMLElement, variation: Record<string, unknown> | null): void {
	const gallery = resolveGalleryInProduct(form);
	if (!gallery) {
		return;
	}
	if (!variation) {
		scrollGalleryToIndex(gallery, 0);
		return;
	}
	const rawId = variation.image_id;
	const id = typeof rawId === 'number' ? rawId : typeof rawId === 'string' ? parseInt(rawId, 10) : NaN;
	if (!Number.isFinite(id) || id <= 0) {
		scrollGalleryToIndex(gallery, 0);
		return;
	}
	const idx = slideIndexForGalleryAttachment(gallery, id);
	if (idx >= 0) {
		scrollGalleryToIndex(gallery, idx);
	}
}

export type VariationImageSyncHandles = {
	off(): void;
};

export function mountVariationImageSync(root: HTMLElement): VariationImageSyncHandles {
	const form = resolveVariationsFormFromHost(root);
	if (!form) {
		return { off() {} };
	}

	const handler = (event: CustomEvent<CommerceEventMap['ts:variation:changed']>): void => {
		const detail = event.detail;
		if (!detail.form || detail.form !== form) {
			return;
		}
		requestAnimationFrame(() => {
			syncGalleryForVariation(form, detail.variation);
		});
	};

	const unsub = on(document, 'ts:variation:changed', handler);

	return {
		off(): void {
			unsub();
		},
	};
}
