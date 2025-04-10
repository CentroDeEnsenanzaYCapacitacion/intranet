let calendar;

document.addEventListener("DOMContentLoaded", function () {
    calendar = new FullCalendar.Calendar(document.getElementById("calendar"), {
        locale: "es",
        buttonText: {
            today: "Hoy",
            month: "Mes",
            week: "Semana",
            day: "Día",
            list: "Lista",
        },
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        editable: true,
        eventDurationEditable: false,
        events: routes.events,
        eventContent: function (arg) {
            const props = arg.event.extendedProps;
            const container = document.createElement("div");
            container.innerHTML = `
                <strong>${props.staff_name}</strong><br>
                <span>${props.subject_name}</span><br>
                <small>${props.start_time.slice(0, 5)} - ${props.end_time.slice(0, 5)}</small>
            `;
            return { domNodes: [container] };
        },

        dateClick: function (info) {
            document.getElementById("selectedDate").value = info.dateStr;
            document.getElementById("staffSelect").value = "";
            document.getElementById("subjectSelect").value = "";
            document.getElementById("startTime").value = "";
            document.getElementById("endTime").value = "";

            openModal("assignModal");
        },

        eventClick: function (info) {
            const event = info.event;
            const props = event.extendedProps;
            const formatTime = (t) =>
                t && t.length >= 5 ? t.substring(0, 5) : "";

            document.getElementById("editAssignmentId").value = event.id;
            document.getElementById("editStaffSelect").value = props.staff_id;
            document.getElementById("editSubjectSelect").value = props.subject_id;
            document.getElementById("editStartTime").value = formatTime(props.start_time);
            document.getElementById("editEndTime").value = formatTime(props.end_time);

            openModal("editModal");
        },
    });

    calendar.render();
});

document.getElementById("assignForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const staffId = document.getElementById("staffSelect").value;
    const subjectId = document.getElementById("subjectSelect").value;
    const startTime = document.getElementById("startTime").value;
    const endTime = document.getElementById("endTime").value;
    const date = document.getElementById("selectedDate").value;

    if (!staffId || !subjectId || !startTime || !endTime || !date) {
        alert("Completa todos los campos.");
        return;
    }

    fetch(routes.store, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            staff_id: staffId,
            subject_id: subjectId,
            date: date,
            start_time: startTime,
            end_time: endTime,
        }),
    })
        .then((res) => res.json())
        .then(() => {
            calendar.refetchEvents();
            closeModal("assignModal");
        })
        .catch((err) => {
            console.error(err);
            alert("Error al asignar horas.");
        });
});

document.getElementById("editForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const id = document.getElementById("editAssignmentId").value;
    const staffId = document.getElementById("editStaffSelect").value;
    const subjectId = document.getElementById("editSubjectSelect").value;
    const startTime = document.getElementById("editStartTime").value;
    const endTime = document.getElementById("editEndTime").value;

    fetch(routes.update(id), {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            staff_id: staffId,
            subject_id: subjectId,
            start_time: startTime,
            end_time: endTime,
        }),
    })
        .then((res) => res.json())
        .then(() => {
            calendar.refetchEvents();
            closeModal("editModal");
        })
        .catch((err) => {
            console.error(err);
            alert("Error al actualizar.");
        });
});

document.getElementById("deleteAssignmentBtn").addEventListener("click", function () {
    const id = document.getElementById("editAssignmentId").value;
    if (!confirm("¿Eliminar esta asignación?")) return;

    fetch(routes.delete(id), {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    })
        .then((res) => res.json())
        .then(() => {
            calendar.refetchEvents();
            // CERRAMOS TU MODAL PERSONALIZADO
            closeModal("editModal");
        })
        .catch((err) => {
            console.error(err);
            alert("Error al eliminar.");
        });
});

function closeOnOverlay(e, modalId) {
    if (e.target.id === modalId) {
        closeModal(modalId);
    }
}

