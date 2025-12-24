let loaderTimer;
const loaderDelayMs = 150;

function getLoaderContainer() {
    return document.getElementById('loader-container');
}

function showLoader(visible){
    const loader = getLoaderContainer();
    if (!loader) {
        return;
    }

    if(visible){
        loader.classList.add('is-visible');
    }else{
        loader.classList.remove('is-visible');
    }
}

function scheduleLoader() {
    clearTimeout(loaderTimer);
    loaderTimer = setTimeout(() => {
        showLoader(true);
    }, loaderDelayMs);
}

window.addEventListener('pageshow', () => {
    clearTimeout(loaderTimer);
    showLoader(false);
});

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!form || form.dataset?.noLoader === 'true') {
        return;
    }

    scheduleLoader();
});

document.addEventListener('click', (event) => {
    const link = event.target.closest('a');
    if (!link) {
        return;
    }

    if (link.dataset?.noLoader === 'true') {
        return;
    }

    const href = link.getAttribute('href');
    if (!href || href.startsWith('#')) {
        return;
    }

    if (href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
        return;
    }

    if (link.hasAttribute('data-toggle') || link.hasAttribute('data-bs-toggle')) {
        return;
    }

    if (link.target === '_blank' || link.hasAttribute('download')) {
        return;
    }

    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
    }

    scheduleLoader();
});

window.onload = function() {
    setTimeout(function() {
        const alerta = document.getElementById('success');
        if (alerta) {
          alerta.style.opacity = '0';
          setTimeout(function() {
            alerta.style.display = 'none';
          }, 500);
        }
      }, 5000);
  };
