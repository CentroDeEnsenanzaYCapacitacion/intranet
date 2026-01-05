let calendar;
let selectedCrewId = userCrewId;

document.addEventListener("DOMContentLoaded", function () {
    if (userCrewId === 1) {
        const crewSelect = document.getElementById('crewSelect');
        selectedCrewId = crewSelect.value;
        crewSelect.addEventListener('change', () => {
            selectedCrewId = crewSelect.value;
            calendar.refetchEvents();
        });
    }

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
        events: {
            url: routes.events,
            method: 'GET',
            extraParams: () => ({ crew_id: selectedCrewId }),
            failure: () => alert('Error al cargar los eventos')
        },
        eventContent: function (arg) {
            const props = arg.event.extendedProps;
            const container = document.createElement("div");

            const strong = document.createElement("strong");
            strong.textContent = props.staff_name;
            container.appendChild(strong);
            container.appendChild(document.createElement("br"));

            const span = document.createElement("span");
            span.textContent = props.subject_name;
            container.appendChild(span);
            container.appendChild(document.createElement("br"));

            const small = document.createElement("small");
            small.textContent = `${props.start_time.slice(0, 5)} - ${props.end_time.slice(0, 5)}`;
            container.appendChild(small);

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
        eventDrop: function(info) {
            const event = info.event;
            const newDate = info.event.startStr.split("T")[0];

            fetch(routes.update(event.id), {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    date: newDate
                }),
            })
            .then(res => res.json())
            .then(() => {
                calendar.refetchEvents();
            })
            .catch(err => {
                console.error(err);
                alert("Error al mover el evento.");
                info.revert();
            });
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

    const errorBox = document.getElementById("assignError");
    errorBox.classList.add("d-none");
    errorBox.textContent = "";

    if (!staffId || !subjectId || !startTime || !endTime || !date) {
        errorBox.textContent = "Completa todos los campos.";
        errorBox.classList.remove("d-none");
        return;
    }

    const start = new Date(`2000-01-01T${startTime}:00`);
    const end = new Date(`2000-01-01T${endTime}:00`);
    const hours = (end - start) / (1000 * 60 * 60);

    if (hours <= 0) {
        errorBox.textContent = "La hora de fin debe ser posterior a la de inicio.";
        errorBox.classList.remove("d-none");
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
            crew_id: selectedCrewId
        }),
    })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => { throw data; });
            }
            return res.json();
        })
        .then(() => {
            calendar.refetchEvents();
            closeModal("assignModal");
        })
        .catch((err) => {
            if (err.error) {
                errorBox.textContent = err.error;
                errorBox.classList.remove("d-none");
            } else {
                errorBox.textContent = "Error al asignar horas.";
                errorBox.classList.remove("d-none");
            }
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

