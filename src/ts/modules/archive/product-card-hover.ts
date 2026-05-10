function preload(url: string): void {
	if (!url) {
		return;
	}
	const image = new Image();
	image.decoding = 'async';
	image.src = url;
}

export function mountProductCardHover(card: HTMLElement): () => void {
	const secondary = card.querySelector<HTMLImageElement>('[data-ts-secondary-image]');
	if (!secondary) {
		return () => {};
	}

	preload(secondary.currentSrc || secondary.src);

	const activate = (): void => {
		card.classList.add('is-media-active');
	};

	const deactivate = (): void => {
		card.classList.remove('is-media-active');
	};

	card.addEventListener('pointerenter', activate);
	card.addEventListener('pointerleave', deactivate);
	card.addEventListener('focusin', activate);
	card.addEventListener('focusout', deactivate);

	return () => {
		card.removeEventListener('pointerenter', activate);
		card.removeEventListener('pointerleave', deactivate);
		card.removeEventListener('focusin', activate);
		card.removeEventListener('focusout', deactivate);
	};
}
