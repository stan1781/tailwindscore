export function resolveVariationsFormFromHost(root: HTMLElement): HTMLFormElement | null {
	const direct = root.querySelector<HTMLFormElement>(':scope > form.variations_form');
	if (direct) {
		return direct;
	}
	if (root.matches('form.variations_form')) {
		return root as HTMLFormElement;
	}
	return root.querySelector<HTMLFormElement>('form.variations_form');
}
