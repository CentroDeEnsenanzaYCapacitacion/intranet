<footer class="modern-footer">
    <div class="footer-container">
        <div class="footer-bottom">
            <div class="footer-brand">
                    <img src="{{ asset('assets/img/IC.png') }}" alt="IntraCEC" width="48" height="48" class="logo-white">
                    <div class="footer-brand-text">
                        <h4>IntraCEC</h4>
                        <p>Sistema de Gestión Académica</p>
                    </div>
                </div>
            <div class="footer-copyright">
                <p>&copy; {{ date('Y') }} Todos los derechos reservados.</p>
            </div>
            <div class="footer-version">
                <p>Versión 2.0 | Última actualización: {{ config('app.deploy_date') ?: 'Sin registro' }}</p>
            </div>
            <div class="footer-github">
                <a href="https://github.com/AjRoBSeYeR" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                    <img src="{{ asset('assets/img/as.png') }}" alt="GitHub" width="40" height="40">
                </a>
            </div>
        </div>
    </div>
</footer>
