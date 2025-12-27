document.addEventListener("DOMContentLoaded", function() {
    const roleSelect = document.getElementById("role_id");
    const plantelSection = document.getElementById("plantel-section");
    const crewSelect = document.getElementById("crew_id");
    const crewHidden = document.getElementById("crew_id_hidden");
    const roleTitle = document.getElementById("role-title");

    if (!roleSelect || !plantelSection || !crewSelect || !crewHidden) {
        return;
    }

    const togglePlantel = () => {
        const v = roleSelect.value;
        const mustForceCrew1 = (v === "1" || v === "5" || v === "6");

        if (mustForceCrew1) {
            plantelSection.style.display = "none";
            crewSelect.disabled = true;
            crewHidden.disabled = false;
            crewHidden.value = "1";
            if (roleTitle) {
                roleTitle.textContent = roleTitle.dataset.short || "Rol";
            }
        } else {
            plantelSection.style.display = "block";
            crewSelect.disabled = false;
            crewHidden.disabled = true;
            if (roleTitle) {
                roleTitle.textContent = roleTitle.dataset.full || "Rol y plantel";
            }
        }
    };

    togglePlantel();
    roleSelect.addEventListener("change", togglePlantel);
});
