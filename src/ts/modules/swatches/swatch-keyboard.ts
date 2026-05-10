/**
 * Swatch 键盘：roving tabindex + 方向键导航（不实现 variation 匹配）。
 */

export function updateSwatchRovingTabindex(buttons: HTMLButtonElement[], primary: HTMLButtonElement | null): void {
	buttons.forEach((b) => {
		const isPrimary = primary !== null && b === primary;
		b.setAttribute('tabindex', isPrimary ? '0' : '-1');
	});
}

function listFocusable(buttons: HTMLButtonElement[]): HTMLButtonElement[] {
	return buttons.filter((b) => b.getAttribute('aria-disabled') !== 'true');
}

function currentFocusIndex(all: HTMLButtonElement[], active: Element | null): number {
	if (!active || !(active instanceof HTMLButtonElement)) {
		return -1;
	}
	return all.indexOf(active);
}

/**
 * @param getButtons 每次事件重新查询，便于 WC 刷新 DOM 后仍正确。
 */
export function bindSwatchKeyboard(
	group: HTMLElement,
	getButtons: () => HTMLButtonElement[],
	activate: (btn: HTMLButtonElement) => void,
): () => void {
	const onKeydown = (event: KeyboardEvent): void => {
		const buttons = getButtons();
		if (buttons.length === 0) {
			return;
		}
		const enabled = listFocusable(buttons);
		if (enabled.length === 0) {
			return;
		}

		const { key } = event;
		const vertical = key === 'ArrowDown' || key === 'ArrowUp';
		const horizontal = key === 'ArrowLeft' || key === 'ArrowRight';
		if (!vertical && !horizontal && key !== 'Home' && key !== 'End' && key !== ' ' && key !== 'Enter') {
			return;
		}

		const active = document.activeElement;
		const idx = currentFocusIndex(enabled, active);

		if (key === ' ' || key === 'Enter') {
			if (active instanceof HTMLButtonElement && group.contains(active)) {
				event.preventDefault();
				activate(active);
			}
			return;
		}

		event.preventDefault();

		let next = idx;
		if (key === 'Home') {
			next = 0;
		} else if (key === 'End') {
			next = enabled.length - 1;
		} else if (horizontal || vertical) {
			const dir =
				key === 'ArrowRight' || key === 'ArrowDown'
					? 1
					: -1;
			next = idx < 0 ? 0 : (idx + dir + enabled.length) % enabled.length;
		}

		const target = enabled[next];
		if (target) {
			target.focus();
			updateSwatchRovingTabindex(buttons, target);
		}
	};

	const onFocusIn = (): void => {
		const active = document.activeElement;
		if (active instanceof HTMLButtonElement && group.contains(active)) {
			const buttons = getButtons();
			updateSwatchRovingTabindex(buttons, active);
		}
	};

	group.addEventListener('keydown', onKeydown);
	group.addEventListener('focusin', onFocusIn);

	return () => {
		group.removeEventListener('keydown', onKeydown);
		group.removeEventListener('focusin', onFocusIn);
	};
}
