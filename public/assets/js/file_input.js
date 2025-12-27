document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.file-input-native').forEach((input) => {
        const wrapper = input.closest('.file-input');
        const text = wrapper?.querySelector('.file-input-text');
        if (!text) {
            return;
        }
        const defaultText = text.dataset.default || text.textContent;
        const updateText = () => {
            const count = input.files ? input.files.length : 0;
            if (!count) {
                text.textContent = defaultText;
                return;
            }
            if (count === 1) {
                text.textContent = input.files[0].name;
                return;
            }
            text.textContent = `${count} archivos seleccionados`;
        };
        input.addEventListener('change', updateText);
        updateText();
    });
});
