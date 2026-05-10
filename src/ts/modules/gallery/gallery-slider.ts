import EmblaCarousel, { type EmblaCarouselType } from 'embla-carousel';

/**
 * Main Embla viewport — gestures + responsive viewport scrolling.
 */
export function createGallerySlider(viewport: HTMLElement): EmblaCarouselType {
	return EmblaCarousel(viewport, {
		align: 'start',
		containScroll: 'trimSnaps',
		dragFree: false,
		loop: false,
	});
}
