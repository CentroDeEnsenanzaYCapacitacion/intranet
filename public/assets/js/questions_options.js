document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('addOption');
    const container = document.getElementById('optionsContainer');

    if (!addBtn || !container) {
        return;
    }

    let optionCount = window.initialOptionCount || document.querySelectorAll('.option-row').length;
    const maxOptions = 6;
    const minOptions = 3;

    const toggleAddButton = () => {
        addBtn.style.display = optionCount >= maxOptions ? 'none' : 'inline-flex';
    };

    addBtn.addEventListener('click', function() {
        if (optionCount < maxOptions) {
            const newOption = document.createElement('div');
            newOption.className = 'row d-flex align-items-center mt-2 option-row';
            newOption.setAttribute('data-option', optionCount);
            newOption.innerHTML = `
                <div class="col-auto pr-2">
                    <input class="form-check-input correct-radio" type="radio" name="correct_option" value="${optionCount}" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control modern-input option-input" name="options[${optionCount}][text]" placeholder="Opci&oacute;n ${optionCount + 1}" required>
                </div>
                <div class="col-auto">
                    <button type="button" class="action-btn action-delete remove-option" title="Eliminar opci&oacute;n">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(newOption);
            optionCount++;
            updateRemoveButtons();
        }
        toggleAddButton();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            const optionRow = e.target.closest('.option-row');
            optionRow.remove();
            optionCount--;
            updateRemoveButtons();
            reindexOptions();
            toggleAddButton();
        }
    });

    function updateRemoveButtons() {
        const options = document.querySelectorAll('.option-row');
        const removeButtons = document.querySelectorAll('.remove-option');

        removeButtons.forEach((btn, index) => {
            if (options.length > minOptions && index >= minOptions) {
                btn.style.display = 'inline-flex';
            } else {
                btn.style.display = 'none';
            }
        });
    }

    function reindexOptions() {
        const options = document.querySelectorAll('.option-row');
        options.forEach((option, index) => {
            option.setAttribute('data-option', index);
            const radio = option.querySelector('.correct-radio');
            const input = option.querySelector('.option-input');

            radio.value = index;
            input.name = `options[${index}][text]`;
            input.placeholder = `Opci&oacute;n ${index + 1}`;
        });
        optionCount = options.length;
    }

    updateRemoveButtons();
    toggleAddButton();
});
