function showLoader(visible){
    if(visible){
        document.getElementById('loader-container').style.display = 'block';
    }else{
        document.getElementById('loader-container').style.display = 'none';
    }
}

window.addEventListener('pageshow', (event) => {
    showLoader(false);
});

