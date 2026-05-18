import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

function formatCurrencyInput(value) {
    const digits = value.toString().replace(/\D/g, '');
    if (!digits) {
        return '';
    }
    return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function attachCurrencyFormatting() {
    const inputs = document.querySelectorAll('.currency-input');

    inputs.forEach((input) => {
        input.addEventListener('input', (event) => {
            const cursorPosition = input.selectionStart;
            const oldValue = input.value;
            const digitsBeforeCursor = oldValue.slice(0, cursorPosition).replace(/\D/g, '').length;
            const formatted = formatCurrencyInput(oldValue);
            input.value = formatted;

            const newCursor = formatted.length - (formatted.replace(/\D/g, '').length - digitsBeforeCursor);
            input.setSelectionRange(newCursor, newCursor);
        });

        input.addEventListener('paste', (event) => {
            event.preventDefault();
            const pastedText = (event.clipboardData || window.clipboardData).getData('text') || '';
            input.value = formatCurrencyInput(pastedText);
        });
    });
}

Alpine.start();

window.addEventListener('DOMContentLoaded', () => {
    attachCurrencyFormatting();
});
