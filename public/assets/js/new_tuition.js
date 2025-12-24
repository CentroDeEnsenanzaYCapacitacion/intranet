document.addEventListener("DOMContentLoaded", function () {
    var typeSelectElement = document.getElementById("receipt_type_id");
    var typeEvent = new Event("change");
    typeSelectElement.dispatchEvent(typeEvent);
});

document
    .getElementById("newTuitionForm")
    .addEventListener("submit", function (event) {
        var conceptDiv = document.getElementById("conceptDiv").textContent;
    var amountDiv = document.getElementById("amountDiv").value;
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
    showVoucherInput();
});

document.getElementById("surchargeCheck").addEventListener("change", function () {
    showSurchargeOptions();
    updateAmountModifiers();
});

document.getElementById("surchargePercentage").addEventListener("change", function () {
    updateAmountModifiers();
});

document.getElementById("earlyDiscountCheck").addEventListener("change", function () {
    showEarlyDiscountOptions();
    updateAmountModifiers();
});

document.getElementById("earlyDiscountPercentage").addEventListener("input", function () {

    var value = parseInt(this.value);
    if (value < 1) this.value = 1;
    if (value > 10) this.value = 10;
    updateAmountModifiers();
});

var selections = [];
var baseAmountNumeric = 0;
var tuition_results = { number: 1, isAdvance: false };

function showVoucherInput() {
    var voucher = document.getElementById("voucher");
    var checkbox = document.getElementById("cardCheck");

    if (checkbox.checked) {
        voucher.style.display = "block";
    } else {
        voucher.style.display = "none";
    }
}

function showSurchargeOptions() {
    var surchargeOptions = document.getElementById("surchargeOptions");
    var checkbox = document.getElementById("surchargeCheck");

    if (checkbox.checked) {
        surchargeOptions.style.display = "block";

        document.getElementById("earlyDiscountCheck").checked = false;
        showEarlyDiscountOptions();
    } else {
        surchargeOptions.style.display = "none";
    }
}

function showEarlyDiscountOptions() {
    var earlyDiscountOptions = document.getElementById("earlyDiscountOptions");
    var checkbox = document.getElementById("earlyDiscountCheck");

    if (checkbox.checked) {
        earlyDiscountOptions.style.display = "block";

        document.getElementById("surchargeCheck").checked = false;
        showSurchargeOptions();
    } else {
        earlyDiscountOptions.style.display = "none";
    }
}

function updateAmountModifiers() {
    var surchargeCheckbox = document.getElementById("surchargeCheck");
    var earlyDiscountCheckbox = document.getElementById("earlyDiscountCheck");
    var conceptDiv = document.getElementById("conceptDiv");
    var amountDiv = document.getElementById("amountDiv");

    var baseConcept = conceptDiv.textContent.trim();
    baseConcept = baseConcept.replace(/\s*con recargo(\s*\d+%?)?$/i, '').trim();
    baseConcept = baseConcept.replace(/\s*con descuento por pronto pago(\s*\d+%?)?$/i, '').trim();

    var baseAmount = typeof baseAmountNumeric === 'number' && !isNaN(baseAmountNumeric)
        ? baseAmountNumeric
        : parseFloat(amountDiv.value.replace(/[^0-9.-]+/g, '')) || 0;

    var finalAmount = baseAmount;
    var conceptSuffix = '';

    if (surchargeCheckbox && surchargeCheckbox.checked) {
        var surchargePercentage = parseFloat(document.getElementById("surchargePercentage").value || '0');
        var surchargeAmount = (baseAmount * surchargePercentage) / 100;
        finalAmount = baseAmount + surchargeAmount;
        conceptSuffix = " con recargo";
    } else if (earlyDiscountCheckbox && earlyDiscountCheckbox.checked) {
        var discountPercentage = parseFloat(document.getElementById("earlyDiscountPercentage").value || '0');
        var discountAmount = (baseAmount * discountPercentage) / 100;
        finalAmount = baseAmount - discountAmount;
        conceptSuffix = " con descuento por pronto pago";
    }

    conceptDiv.textContent = baseConcept + conceptSuffix;
    amountDiv.value = finalAmount.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function refresh_layout(tuitionNumber, isAdvance) {
    var concept = setConcept(tuitionNumber, isAdvance);
    var amount = setAmount(isAdvance);
    showVoucherInput();
    showColegiaturaOptions();
    document.getElementById("conceptDiv").textContent = concept;
    document.getElementById("amountDiv").value = amount;
}

function showColegiaturaOptions() {
    var earlyDiscountContainer = document.getElementById("earlyDiscountContainer");
    var surchargeContainer = document.getElementById("surchargeContainer");
    var receiptTypeSelect = document.getElementById("receipt_type_id");
    var selectedValue = parseInt(receiptTypeSelect.value);

    if (selectedValue === 2) {
        earlyDiscountContainer.style.display = "block";
        surchargeContainer.style.display = "block";
    } else {
        earlyDiscountContainer.style.display = "none";
        surchargeContainer.style.display = "none";

        var earlyDiscountCheck = document.getElementById("earlyDiscountCheck");
        var surchargeCheck = document.getElementById("surchargeCheck");
        if (earlyDiscountCheck.checked) {
            earlyDiscountCheck.checked = false;
            showEarlyDiscountOptions();
        }
        if (surchargeCheck.checked) {
            surchargeCheck.checked = false;
            showSurchargeOptions();
        }
    }
}

function establish_elements(element) {
    selections = retrieveSelectedItems();
    var attrSelect = document.getElementById("attr_id");
    var attr = document.getElementById("attr_group");
    var amountText = document.getElementById("amountDiv");
    var amountInput = document.getElementById("receipt_amount");
    var tuitionNumber = "";

    if (element == "type") {
        tuition_results = calculateTuitionNumber();

        if (selections[0].selectedIndex == 1) {
            tuitionNumber = tuition_results.number;
        } else {
            tuition_results.isAdvance = false;
        }

        const array = Object.values(receipt_attributes).map((item) => ({
            id: item.id,
            name: item.name,
        }));

        const attrArray = [];

        attrSelect.innerHTML = "";

        for (let i = 1; i <= array.length - 1; i++) {
            attrArray.push(array.slice(0, i));
        }

        var contentIndex = 0;

        switch (selections[0].selectedIndex) {
            case 3:
            case 6:
            case 7:
                attr.style.display = "block";
                contentIndex = 3;
                break;
            case 10:
                attr.style.display = "none";
                break;
            default:
                attr.style.display = "block";
                contentIndex = 2;
                break;
        }

        if (tuition_results.isAdvance) {
            attrArray[contentIndex].shift();
        }

        attrArray[contentIndex].forEach(function (option) {
            var opt = document.createElement("option");
            opt.value = option.id;
            opt.textContent = option.name;
            attrSelect.appendChild(opt);
        });

        if (tuition_results.isAdvance) {
            amountText.style.display = "none";
            amountInput.style.display = "block";
        } else {
            amountText.style.display = "block";
            amountInput.style.display = "none";
        }
    } else {
        if (selections[0].selectedIndex == 1) {
            tuitionNumber = tuition_results.number;
        }

        if (tuition_results.isAdvance) {
            switch (selections[1].selectedIndex) {
                case 0:
                    amountText.style.display = "none";
                    amountInput.style.display = "block";
                    break;
                default:
                    amountText.style.display = "block";
                    amountInput.style.display = "none";
                    break;
            }
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
    }

    refresh_layout(tuitionNumber, tuition_results.isAdvance);

    updateAmountModifiers();
}

function calculateTuitionNumber() {
    if (student_tuition_receipts.length == 0) {
        return { number: 1, isAdvance: false };
    }

    const tuitionGroups = {};

    for (const receipt of student_tuition_receipts) {
        const conceptParts = receipt.concept.split("#");
        if (conceptParts.length > 1) {
            const numberPart = conceptParts[1].trim().split(" ")[0];
            const tuitionNumber = Number(numberPart);

            if (!isNaN(tuitionNumber) && tuitionNumber > 0) {
                if (!tuitionGroups[tuitionNumber]) {
                    tuitionGroups[tuitionNumber] = [];
                }
                tuitionGroups[tuitionNumber].push(receipt);
            }
        }
    }

    const tuitionNumbers = Object.keys(tuitionGroups).map(Number).sort((a, b) => b - a);
    const maxTuitionNumber = tuitionNumbers[0] || 0;

    if (maxTuitionNumber > 0) {
        const lastTuitionReceipts = tuitionGroups[maxTuitionNumber];

        const allAreAdvances = lastTuitionReceipts.every(r => r.receipt_attribute_id == 1);

        if (allAreAdvances) {
            return {
                number: maxTuitionNumber,
                isAdvance: true,
            };
        }
    }

    return {
        number: maxTuitionNumber + 1,
        isAdvance: false,
    };
}

function retrieveSelectedItems(isAdvance = false) {
    var selects = document.querySelectorAll("select");
    var results = Array.from(selects).map((select, index) => {
        var selectedIndex = select.selectedIndex;
        var text = select.options[selectedIndex].text;
        if (!isAdvance) {
            if (index === 1 && selectedIndex === 0) {
                text = "";
            }
        }

        return { selectedIndex, text };
    });
    return results;
}

function setAmount(isAdvance) {
    selections = retrieveSelectedItems();
    var amount = 0;
    var amountValue= 0;

    if (selections[0].selectedIndex + 1 == 2) {
        if (isAdvance) {
            summatory = 0;
            for (const receipt of student_tuition_receipts){
                if(receipt.receipt_attribute_id==1){
                    summatory += parseInt(receipt.amount);
                    amountValue = parseFloat(student.tuition) - summatory;
                }else{
                    break;
                }
            }
        } else {
            amountValue = parseFloat(student.tuition);
        }
    } else {

        amount = amounts.filter(function (item) {
            return item.receipt_type_id == selections[0].selectedIndex + 1;
        });
        amountValue = parseFloat(amount[0].amount);
    }

    baseAmountNumeric = amountValue;

    var formattedAmount = amountValue.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    return formattedAmount;
}

function setConcept(tuitionNumber, isAdvance = false) {
    selections = retrieveSelectedItems(isAdvance);

    return (
        selections[1].text.trim() +
        " " +
        selections[0].text.trim() +
        " " +
        course.trim() +
        (tuitionNumber !== "" &&
        tuitionNumber !== null &&
        tuitionNumber !== undefined
            ? " # " + tuitionNumber
            : "")
    );
}
