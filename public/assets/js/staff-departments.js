document.addEventListener('DOMContentLoaded', function() {
    const config = window.staffDepartmentsConfig || {};
    const departments = config.departments || [];
    const existingDepartments = config.existingDepartments || [];
    const container = document.getElementById('departments-container');
    const addBtn = document.getElementById('add-department-btn');
    let rowIndex = 0;

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function createDepartmentRow(data = {}) {
        const row = document.createElement('div');
        row.className = 'department-row';
        row.style.cssText = 'display: flex; gap: 12px; align-items: flex-end; margin-bottom: 12px; padding: 12px; background: white; border-radius: 6px; border: 1px solid #e0e0e0;';

        const usedDepartmentIds = Array.from(container.querySelectorAll('select[name*="department_id"]'))
            .map(select => select.value)
            .filter(val => val && val !== (data.department_id || '').toString());

        let departmentOptions = '<option value="">Selecciona departamento</option>';
        departments.forEach(dept => {
            const isUsed = usedDepartmentIds.includes(dept.id.toString());
            const isSelected = data.department_id && data.department_id == dept.id;
            if (!isUsed || isSelected) {
                departmentOptions += `<option value="${dept.id}" ${isSelected ? 'selected' : ''}>${escapeHtml(dept.name)}</option>`;
            }
        });

        row.innerHTML = `
            <div style="flex: 2;">
                <label style="display: block; margin-bottom: 4px; font-size: 0.85rem; color: #666;">Departamento</label>
                <select name="departments[${rowIndex}][department_id]" class="modern-input" required style="width: 100%;">
                    ${departmentOptions}
                </select>
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 4px; font-size: 0.85rem; color: #666;">Costo</label>
                <input type="number" name="departments[${rowIndex}][cost]" class="modern-input" step="0.01" min="0" required style="width: 100%;" value="${data.cost || ''}">
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 4px; font-size: 0.85rem; color: #666;">Tipo</label>
                <select name="departments[${rowIndex}][cost_type]" class="modern-input" required style="width: 100%;">
                    <option value="hour" ${data.cost_type === 'hour' || !data.is_roster ? 'selected' : ''}>Por hora</option>
                    <option value="day" ${data.cost_type === 'day' || data.is_roster ? 'selected' : ''}>Por día</option>
                </select>
            </div>
            <div style="flex: 0 0 auto;">
                <button type="button" class="btn-remove-dept" style="padding: 8px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        `;

        row.querySelector('.btn-remove-dept').addEventListener('click', function() {
            row.remove();
            updateAvailableDepartments();
        });

        row.querySelector('select[name*="department_id"]').addEventListener('change', updateAvailableDepartments);

        rowIndex++;
        return row;
    }

    function updateAvailableDepartments() {
        const usedIds = Array.from(container.querySelectorAll('select[name*="department_id"]'))
            .map(select => select.value)
            .filter(val => val);

        container.querySelectorAll('select[name*="department_id"]').forEach(select => {
            const currentValue = select.value;
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                if (option.value && option.value !== currentValue) {
                    option.style.display = usedIds.includes(option.value) ? 'none' : '';
                }
            });
        });

        addBtn.disabled = usedIds.length >= departments.length;
    }

    addBtn.addEventListener('click', function() {
        container.appendChild(createDepartmentRow());
        updateAvailableDepartments();
    });

    if (existingDepartments.length > 0) {
        existingDepartments.forEach(dept => {
            container.appendChild(createDepartmentRow({
                department_id: dept.department_id,
                cost: dept.cost,
                is_roster: dept.is_roster
            }));
        });
    }

    updateAvailableDepartments();
});
