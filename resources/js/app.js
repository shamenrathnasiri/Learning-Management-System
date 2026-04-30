import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

function initializeRichTextEditors() {
	document.querySelectorAll('[data-rich-text-editor]').forEach((editorRoot) => {
		const surface = editorRoot.querySelector('[data-editor-surface]');
		const input = editorRoot.querySelector('[data-editor-input]');
		const toolbar = editorRoot.querySelector('[data-editor-toolbar]');

		if (!surface || !input) {
			return;
		}

		if (surface.textContent && surface.textContent.trim() === '') {
			surface.innerHTML = '';
		} else {
			surface.innerHTML = surface.innerHTML.trim();
		}

		const syncInput = () => {
			input.value = surface.innerHTML;
		};

		const runCommand = (command) => {
			surface.focus();
			document.execCommand(command, false, null);
			syncInput();
		};

		const insertLink = () => {
			const url = window.prompt('Enter a link URL');

			if (!url) {
				return;
			}

			surface.focus();
			document.execCommand('createLink', false, url);
			syncInput();
		};

		if (!surface.dataset.editorInitialized) {
			surface.dataset.editorInitialized = 'true';
			surface.addEventListener('input', syncInput);
			surface.addEventListener('blur', syncInput);
			surface.addEventListener('paste', () => {
				window.setTimeout(syncInput, 0);
			});
		}

		if (toolbar && !toolbar.dataset.editorInitialized) {
			toolbar.dataset.editorInitialized = 'true';
			toolbar.addEventListener('click', (event) => {
				const button = event.target.closest('[data-editor-action]');

				if (!button) {
					return;
				}

				event.preventDefault();

				const action = button.dataset.editorAction;

				if (action === 'link') {
					insertLink();
					return;
				}

				if (action === 'insertUnorderedList') {
					runCommand('insertUnorderedList');
					return;
				}

				if (action === 'insertOrderedList') {
					runCommand('insertOrderedList');
					return;
				}

				runCommand(action);
			});
		}

		syncInput();
	});

	document.querySelectorAll('form[data-rich-text-form]').forEach((form) => {
		if (form.dataset.editorInitialized) {
			return;
		}

		form.dataset.editorInitialized = 'true';
		form.addEventListener('submit', () => {
			form.querySelectorAll('[data-editor-surface]').forEach((surface) => {
				const input = surface.closest('[data-rich-text-editor]')?.querySelector('[data-editor-input]');

				if (input) {
					input.value = surface.innerHTML;
				}
			});
		});
	});
}

document.addEventListener('DOMContentLoaded', initializeRichTextEditors);

Alpine.start();
