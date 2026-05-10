function syncPressed(buttons: HTMLButtonElement[], active: HTMLButtonElement): void {
	buttons.forEach((button) => {
		const selected = button === active;
		button.setAttribute('aria-pressed', selected ? 'true' : 'false');
		button.classList.toggle('is-selected', selected);
	});
}

export function mountArchiveSwatches(card: HTMLElement): () => void {
	const root = card.querySelector<HTMLElement>('[data-ts-archive-swatches]');
	const primary = card.querySelector<HTMLImageElement>('[data-ts-primary-image]');
	if (!root || !primary) {
		return () => {};
	}

	const buttons = Array.from(root.querySelectorAll<HTMLButtonElement>('[data-ts-archive-swatch]'));
	if (buttons.length === 0) {
		return () => {};
	}

	const originalSrc = primary.currentSrc || primary.src;

	const applyPreview = (button: HTMLButtonElement): void => {
		const preview = button.getAttribute('data-preview-image') ?? '';
		if (preview) {
			primary.src = preview;
		}
	};

	const findSelected = (): HTMLButtonElement | null => {
		return buttons.find((button) => button.getAttribute('aria-pressed') === 'true') ?? buttons[0] ?? null;
	};

	const onClick = (event: Event): void => {
		const button = (event.target as HTMLElement | null)?.closest<HTMLButtonElement>('[data-ts-archive-swatch]');
		if (!button) {
			return;
		}
		syncPressed(buttons, button);
		applyPreview(button);
	};

	const onPointerEnter = (event: Event): void => {
		const button = (event.target as HTMLElement | null)?.closest<HTMLButtonElement>('[data-ts-archive-swatch]');
		if (!button) {
			return;
		}
		applyPreview(button);
	};

	const restoreSelected = (): void => {
		const selected = findSelected();
		if (!selected) {
			primary.src = originalSrc;
			return;
		}
		const preview = selected.getAttribute('data-preview-image') ?? '';
		primary.src = preview || originalSrc;
	};

	root.addEventListener('click', onClick);
	root.addEventListener('pointerenter', onPointerEnter, true);
	root.addEventListener('focusin', onPointerEnter);
	root.addEventListener('pointerleave', restoreSelected);
	root.addEventListener('focusout', restoreSelected);

	return () => {
		root.removeEventListener('click', onClick);
		root.removeEventListener('pointerenter', onPointerEnter, true);
		root.removeEventListener('focusin', onPointerEnter);
		root.removeEventListener('pointerleave', restoreSelected);
		root.removeEventListener('focusout', restoreSelected);
	};
}
