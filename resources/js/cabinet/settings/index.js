// При ошибке валидации сохраняем фокус на первом поле с ошибкой
document.addEventListener("DOMContentLoaded", function () {
	const error = document.querySelector('.text-danger');
	if (error) {
		error.closest('.mb-3').querySelector('input').focus();
	}
});
