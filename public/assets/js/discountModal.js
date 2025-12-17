var myModal;
document.addEventListener('DOMContentLoaded', () => {
    editModal = new bootstrap.Modal(document.getElementById('editModal'), {
        keyboard: false,
        backdrop: 'static'
    });

    var openModalButtons = document.querySelectorAll(".openModalButton");

    openModalButtons.forEach(function(btn) {
        btn.addEventListener("click", function(event) {
            event.preventDefault();
            var courseName = btn.getAttribute('data-user-name');
            var courseId = btn.getAttribute('data-user-id');
            document.getElementById('modalTextarea').value = courseName;
            document.getElementById('saveChangesButton').setAttribute('data-user-id', courseId);
            editModal.show();
        });
    });

    confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
        keyboard: false,
        backdrop: 'static'
    });

    var openModalButtons = document.querySelectorAll(".askButton");

    openModalButtons.forEach(function(btn) {
        btn.addEventListener("click", function(event) {
            event.preventDefault();
            var reportId = btn.getAttribute('data-report-id');
            document.getElementById('modalConfirmButton').setAttribute('data-report-id', reportId);
            confirmModal.show();
        });
    });

    document.getElementById('saveChangesButton').addEventListener('click', function() {
        var courseName = document.getElementById('modalTextarea').value;
        var courseId = this.getAttribute('data-user-id');
        var xhr = new XMLHttpRequest();

        xhr.open("POST", "../../msrvs/users/updateUser.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                editModal.hide();
                window.location.reload();
            }
        }
        xhr.send("userId=" + encodeURIComponent(courseId) + "&userName=" + encodeURIComponent(courseName));
    });

    document.getElementById('modalConfirmButton').addEventListener('click', function() {
        var userId = this.getAttribute('data-report-id');
        var xhr = new XMLHttpRequest();

        xhr.open("POST", "../../msrvs/users/deleteUser.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                deleteModal.hide();
                window.location.reload();
            }
        }
        xhr.send("userId=" + encodeURIComponent(userId));
    });
});
