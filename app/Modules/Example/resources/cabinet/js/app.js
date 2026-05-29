(function () {

	'use strict';

	let typeSelect = document.querySelector('[name="type_val"]');
	if (!typeSelect) return;

	function applyVisibility(value) {
		document.querySelectorAll('[name]:not([name="type_val"])').forEach(function (el) {
			let container = el.closest('p') || el.parentElement;
			if (container) container.style.display = el.name.includes(value) ? '' : 'none';
		});
	}

	typeSelect.addEventListener('change', function () {
		applyVisibility(this.value);
	});

	// Применить при загрузке по текущему значению селекта
	applyVisibility(typeSelect.value);

}());
