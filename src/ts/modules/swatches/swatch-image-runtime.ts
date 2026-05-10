/**
 * 图片 Swatch：preload、hover 大图预览（与 variation / gallery 同源 URL，仅 UI）。
 */

function preloadUrl(url: string): void {
	if (!url) {
		return;
	}
	const im = new Image();
	im.decoding = 'async';
	im.src = url;
}

function mountPreviewHost(field: HTMLElement): () => void {
	const preview = field.querySelector<HTMLElement>('[data-ts-swatch-preview]');
	const img = preview?.querySelector<HTMLImageElement>('.ts-swatch-preview__img');
	if (!preview || !img) {
		return (): void => {};
	}

	let hideTimer = 0;

	const positionNear = (anchor: HTMLElement): void => {
		const r = anchor.getBoundingClientRect();
		const margin = 8;
		const vw = window.innerWidth;
		const panelW = 200;
		let left = r.right + margin;
		if (left + panelW > vw - margin) {
			left = r.left - panelW - margin;
		}
		left = Math.max(margin, Math.min(left, vw - panelW - margin));
		const top = Math.max(margin, r.top);
		preview.style.position = 'fixed';
		preview.style.left = `${left}px`;
		preview.style.top = `${top}px`;
		preview.style.width = `${panelW}px`;
		preview.style.zIndex = 'var(--ts-z-sticky)';
	};

	const showFromButton = (btn: HTMLElement): void => {
		const url = btn.getAttribute('data-preview-url') ?? btn.getAttribute('data-thumb-url');
		if (!url) {
			return;
		}
		img.src = url;
		img.alt = btn.getAttribute('aria-label') ?? '';
		positionNear(btn);
		preview.hidden = false;
		preview.setAttribute('aria-hidden', 'false');
	};

	const hide = (): void => {
		window.clearTimeout(hideTimer);
		hideTimer = window.setTimeout(() => {
			preview.hidden = true;
			preview.setAttribute('aria-hidden', 'true');
		}, 60);
	};

	const cancelHide = (): void => {
		window.clearTimeout(hideTimer);
	};

	const ac = new AbortController();

	field.querySelectorAll<HTMLElement>('[data-ts-swatch-type="image_stack"]').forEach((btn) => {
		btn.addEventListener(
			'pointerenter',
			() => {
				cancelHide();
				showFromButton(btn);
			},
			{ signal: ac.signal },
		);
		btn.addEventListener('pointerleave', hide, { signal: ac.signal });
	});

	preview.addEventListener('pointerenter', cancelHide, { signal: ac.signal });
	preview.addEventListener('pointerleave', hide, { signal: ac.signal });

	const seen = new Set<string>();
	field.querySelectorAll<HTMLElement>('[data-preview-url], [data-thumb-url]').forEach((el) => {
		const u = el.getAttribute('data-preview-url') ?? el.getAttribute('data-thumb-url');
		if (u && !seen.has(u)) {
			seen.add(u);
			preloadUrl(u);
		}
	});

	return () => {
		ac.abort();
		window.clearTimeout(hideTimer);
	};
}

/**
 * 在每个 `.ts-swatch-field` 上挂载预览层。
 */
export function mountSwatchImagePresentation(container: HTMLElement): () => void {
	const fields = container.querySelectorAll<HTMLElement>('.ts-swatch-field');
	const offs: Array<() => void> = [];
	fields.forEach((field) => {
		offs.push(mountPreviewHost(field));
	});
	return () => offs.forEach((o) => o());
}
