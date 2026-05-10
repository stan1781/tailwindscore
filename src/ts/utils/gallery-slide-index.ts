/**
 * Shared gallery slide lookup for TailwindScore Embla markup (attachment id → zero-based index).
 */
export function slideIndexForGalleryAttachment(galleryRoot: HTMLElement, attachmentId: number): number {
	const slides = galleryRoot.querySelectorAll<HTMLElement>(
		'.ts-gallery__main-track .woocommerce-product-gallery__image[data-attachment-id]',
	);
	for (let i = 0; i < slides.length; i++) {
		const slide = slides[i];
		if (!slide) {
			continue;
		}
		const id = Number(slide.dataset.attachmentId);
		if (id === attachmentId) {
			return i;
		}
	}
	return -1;
}
