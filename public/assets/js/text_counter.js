function textCounter(field, field2, maxlimit) {
    var countfield = document.getElementById(field2);
    var currentLength = Array.from(field.value || '').length;
    if (currentLength > maxlimit) {
        field.value = Array.from(field.value).slice(0, maxlimit).join('');
        currentLength = maxlimit;
        return false;
    } else {
        countfield.value = maxlimit - currentLength;
    }
}
