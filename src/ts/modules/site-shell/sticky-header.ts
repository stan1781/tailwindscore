export function mount(root: HTMLElement): void {
	if (root.dataset.sticky !== 'true') {
		return;
	}

	const threshold = Number(root.dataset.stickyThreshold ?? '12');
	let ticking = false;

	const sync = (): void => {
		const scrolled = window.scrollY > threshold;
		root.classList.toggle('is-scrolled', scrolled);
		document.body.classList.toggle('ts-site-header-is-scrolled', scrolled);
		ticking = false;
	};

	const onScroll = (): void => {
		if (ticking) {
			return;
		}
		ticking = true;
		window.requestAnimationFrame(sync);
	};

	sync();
	window.addEventListener('scroll', onScroll, { passive: true });
}
