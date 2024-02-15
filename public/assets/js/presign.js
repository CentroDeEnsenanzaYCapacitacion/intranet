document.getElementById('discount0').addEventListener('click', function() {
    document.getElementById('txtReason').style.display = 'none';
    document.getElementById('card').style.display = 'block';
    document.getElementById('discountText').style.display = 'none';
});

document.getElementById('discount30').addEventListener('click', function() {
    document.getElementById('txtReason').style.display = 'block';
    document.getElementById('card').style.display = 'none';
    document.getElementById('discountText').style.display = 'block';
});

document.getElementById('discount50').addEventListener('click', function() {
    document.getElementById('txtReason').style.display = 'block';
    document.getElementById('card').style.display = 'none';
    document.getElementById('discountText').style.display = 'block';
});

document.getElementById('discount100').addEventListener('click', function() {
    document.getElementById('txtReason').style.display = 'block';
    document.getElementById('card').style.display = 'none';
    document.getElementById('discountText').style.display = 'block';
});
window.addEventListener('load', (event) => {
    document.getElementById('discountText').style.display = 'none';
});