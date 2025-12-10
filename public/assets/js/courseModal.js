var myModal;
document.addEventListener('DOMContentLoaded', () => {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
        keyboard: false,
        backdrop: 'static'
    });

    var openModalButtons = document.querySelectorAll(".deleteButton");

    openModalButtons.forEach(function(btn) {
        btn.addEventListener("click", function(event) {
            event.preventDefault();
            var courseId = btn.getAttribute('data-course-id');
            document.getElementById('confirmDeleteButton').setAttribute('data-course-id', courseId);
            deleteModal.show();
        });
    });

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        var button = this;
        button.innerHTML = 'Borrando... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        button.disabled = true;

        var courseId = this.getAttribute('data-course-id');
        var xhr = new XMLHttpRequest();

        xhr.open("POST", "../../msrvs/courses/deleteCourse.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE) {
                if (this.status === 200) {
                    deleteModal.hide();
                    window.location.reload();
                } else {
                    button.innerHTML = 'Error. Intentar de nuevo';
                    button.disabled = false;
                }
            }
        }
        xhr.send("id=" + encodeURIComponent(courseId));
    });
});

