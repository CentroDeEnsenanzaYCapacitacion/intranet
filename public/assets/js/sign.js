document.getElementById('discount0').addEventListener('change', function() {
    document.getElementById('txtReason').style.display = 'none';
    document.getElementById('card').style.display = 'block';
    document.getElementById('discountText').style.display = 'none';
    document.getElementById('sign').innerText = 'Inscribir';
});

document.getElementById('discount30').addEventListener('change', function() {
    document.getElementById('txtReason').style.display = 'block';
    document.getElementById('card').style.display = 'none';
    document.getElementById('discountText').style.display = 'block';
    document.getElementById('sign').innerText = 'Enviar solicitud';
});

document.getElementById('discount50').addEventListener('change', function() {
    document.getElementById('txtReason').style.display = 'block';
    document.getElementById('card').style.display = 'none';
    document.getElementById('discountText').style.display = 'block';
    document.getElementById('sign').innerText = 'Enviar solicitud';
});

document.getElementById('discount100').addEventListener('change', function() {
    document.getElementById('txtReason').style.display = 'block';
    document.getElementById('card').style.display = 'none';
    document.getElementById('discountText').style.display = 'block';
    document.getElementById('sign').innerText = 'Enviar solicitud';
});
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="discount"]:checked').forEach(function(radio) {
        radio.dispatchEvent(new Event('change'));
    });
});

window.addEventListener('load', (event) => {
    document.getElementById('discountText').style.display = 'none';
});