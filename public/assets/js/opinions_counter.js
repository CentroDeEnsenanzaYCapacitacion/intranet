document.addEventListener('DOMContentLoaded', function () {
    var fields = document.querySelectorAll('textarea[data-counter-target][data-counter-max]');
    fields.forEach(function (field) {
        var counterId = field.getAttribute('data-counter-target');
        var max = Number(field.getAttribute('data-counter-max')) || 0;
        var counter = document.getElementById(counterId);

        var update = function () {
            var currentLength = Array.from(field.value || '').length;
            if (currentLength > max) {
                field.value = Array.from(field.value).slice(0, max).join('');
                currentLength = max;
            }
            if (counter) {
                counter.value = Math.max(0, max - currentLength);
            }
        };

        field.addEventListener('input', update);
        update();
    });
});
