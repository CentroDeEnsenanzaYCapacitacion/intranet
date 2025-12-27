function saveFormDataAndRedirect() {
    const formData = {
        birthdate: document.querySelector('input[name="birthdate"]').value,
        curp: document.querySelector('input[name="curp"]').value,
        address: document.querySelector('input[name="address"]').value,
        colony: document.querySelector('input[name="colony"]').value,
        municipality: document.querySelector('input[name="municipality"]').value,
        pc: document.querySelector('input[name="pc"]').value,
        phone: document.querySelector('input[name="phone"]').value,
        cel_phone: document.querySelector('input[name="cel_phone"]').value,
        email: document.querySelector('input[name="email"]').value,
        gen_month: document.querySelector('select[name="gen_month"]').value,
        gen_year: document.querySelector('select[name="gen_year"]').value,
        payment_periodicity_id: document.querySelector('select[name="payment_periodicity_id"]').value,
        schedule_id: document.querySelector('select[name="schedule_id"]').value,
        sabbatine: document.querySelector('input[name="sabbatine"]:checked').value,
        tuition: document.querySelector('input[name="tuition"]').value,
        modality_id: document.querySelector('select[name="modality_id"]').value,
        start: document.querySelector('input[name="start"]').value,
        tutor_name: document.querySelector('input[name="tutor_name"]').value,
        tutor_surnames: document.querySelector('input[name="tutor_surnames"]').value,
        tutor_phone: document.querySelector('input[name="tutor_phone"]').value,
        tutor_cel_phone: document.querySelector('input[name="tutor_cel_phone"]').value,
        relationship: document.querySelector('input[name="relationship"]').value
    };

    const studentId = window.studentId;
    const saveUrl = window.saveFormDataUrl;
    const redirectUrl = window.profileImageUrl;

    fetch(saveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    }).then(() => {
        window.location.href = redirectUrl;
    });
}
