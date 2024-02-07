function showLoader(visible){
    if(visible){
        document.getElementById('loader-container').style.display = 'block';
    }else{
        document.getElementById('loader-container').style.display = 'none';
    }
}

function goBack() {
    window.history.back();
}

window.addEventListener('load', (event) => {
    showLoader(false);
});

