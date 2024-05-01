document.addEventListener("DOMContentLoaded", function () {
    var typeSelectElement = document.getElementById("receipt_type_id");
    var typeEvent = new Event("change");
    typeSelectElement.dispatchEvent(typeEvent);
});

document.getElementById("form").addEventListener("submit", function (event) {
    var conceptDiv = document.getElementById("conceptDiv").textContent;
    var amountDiv = document.getElementById("amountDiv").textContent;
    document.getElementById("conceptHidden").value = conceptDiv;
    document.getElementById("amountHidden").value = amountDiv;
});

document
    .getElementById("receipt_type_id")
    .addEventListener("change", function () {
        establish_elements("type");
    });

document.getElementById("attr_id").addEventListener("change", function () {
    establish_elements("attr");
});

document.getElementById("cardCheck").addEventListener("change", function () {
    showBoucherInput();
});

var selections = [];

function showBoucherInput() {
    var boucher = document.getElementById("boucher");
    var checkbox = document.getElementById("cardCheck");

    if (checkbox.checked) {
        boucher.style.display = "block";
    } else {
        boucher.style.display = "none";
    }
}

function refresh_layout() {
    var concept = setConcept();
    var amount = setAmount();
    showBoucherInput();
    document.getElementById("conceptDiv").textContent = concept;
    document.getElementById("amountDiv").textContent = amount;
}

function establish_elements(element) {
    selections = retrieveSelectedItems();
    var attrSelect = document.getElementById("attr_id");
    var attr = document.getElementById("attr_group");
    var amountText = document.getElementById("amountDiv");
    var amountInput = document.getElementById("receipt_amount");

    if (element == "type") {
        const array = Object.values(receipt_attributes).map((item) => ({
            id: item.id,
            name: item.name,
        }));

        const attrArray = [];

        attrSelect.innerHTML = "";

        for (let i = 1; i <= array.length - 1; i++) {
            attrArray.push(array.slice(0, i));
        }

        var contenIndex = 0;

        switch (selections[0].selectedIndex) {
            case 1:
                attr.style.display = "block";
                contenIndex = 2;
                break;
            case 6:
            case 7:
                attr.style.display = "block";
                contenIndex = 3;
                break;
            default:
                attr.style.display = "none";
                break;
        }

        attrArray[contenIndex].forEach(function (option) {
            var opt = document.createElement("option");
            opt.value = option.id;
            opt.textContent = option.name;
            attrSelect.appendChild(opt);
        });

        amountText.style.display = "block";
        amountInput.style.display = "none";
    } else {
        switch (selections[1].selectedIndex) {
            case 1:
                amountText.style.display = "none";
                amountInput.style.display = "block";
                break;
            default:
                amountText.style.display = "block";
                amountInput.style.display = "none";
                break;
        }
    }
    refresh_layout();
}

function calculateTuitionNumber() {}

function retrieveSelectedItems() {
    var selects = document.querySelectorAll("select");
    var results = Array.from(selects).map((select, index) => {
        var selectedIndex = select.selectedIndex;
        var text = select.options[selectedIndex].text;
        if (index === 1 && selectedIndex === 0) {
            text = "";
        }
        return { selectedIndex, text };
    });
    return results;
}

function setAmount() {
    var amountText = 0;
    var formattedAmount = amountText.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    return formattedAmount;
}

function setConcept() {
    selections = retrieveSelectedItems();
    return (
        selections[1].text.trim() +
        " " +
        selections[0].text.trim() +
        " " +
        course.trim()
    );
}
