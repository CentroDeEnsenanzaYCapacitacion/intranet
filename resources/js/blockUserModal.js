var myModal;
document.addEventListener('DOMContentLoaded', () => {
    blockModal = new bootstrap.Modal(document.getElementById('blockUserModal'), {
        keyboard: false,
        backdrop: 'static'
    });

    var openModalButtons = document.querySelectorAll(".blockButton");

    openModalButtons.forEach(function(btn) {
        btn.addEventListener("click", function(event) {
            event.preventDefault();
            var userId = btn.getAttribute('data-user-id'); 
            document.getElementById('confirmBlockButton').setAttribute('data-user-id', userId);
            blockModal.show();
        });
    });
    
    document.getElementById('confirmBlockButton').addEventListener('click', function() {
        var button = this;
        button.innerHTML = 'Bloqueando... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        button.disabled = true;

        var userId = this.getAttribute('data-user-id') || null;
        var xhr = new XMLHttpRequest();

        xhr.open("POST", "../../msrvs/users/blockUser.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE) {
                if (this.status === 200) {
                    blockModal.hide(); 
                    window.location.reload();
                } else {
                    button.innerHTML = 'Error. Intentar de nuevo';
                    button.disabled = false;
                }
            }
        }
        xhr.send("userId=" + encodeURIComponent(userId));
    });
});





