<header class="modern-header">
    <div class="header-container">
        <div class="header-left">
            <div class="user-welcome">
                <span class="welcome-text">Bienvenid@,</span>
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">
                    @if(Auth::user()->crew_id !=1){{ Auth::user()->crew->name }} - @endif{{ Auth::user()->role->name }}
                </span>
            </div>
        </div>

        <div class="header-right">
            <nav class="header-nav">
                <a href="{{route('dashboard')}}" class="nav-item" title="Inicio">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 10L10 3L17 10V17C17 17.2652 16.8946 17.5196 16.7071 17.7071C16.5196 17.8946 16.2652 18 16 18H13V13H7V18H4C3.73478 18 3.48043 17.8946 3.29289 17.7071C3.10536 17.5196 3 17.2652 3 17V10Z" fill="currentColor"/>
                    </svg>
                    <span>Inicio</span>
                </a>
                <a href="{{ route('password.change') }}" class="nav-item" title="Cambiar contraseña">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="9" width="14" height="9" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path d="M6 9V5C6 2.79086 7.79086 1 10 1V1C12.2091 1 14 2.79086 14 5V9" stroke="currentColor" stroke-width="2"/>
                        <circle cx="10" cy="13.5" r="1.5" fill="currentColor"/>
                    </svg>
                    <span>Contraseña</span>
                </a>
                <a href="{{ route('logout') }}" class="nav-item logout-btn" title="Cerrar sesión">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 3H16C16.2652 3 16.5196 3.10536 16.7071 3.29289C16.8946 3.48043 17 3.73478 17 4V16C17 16.2652 16.8946 16.5196 16.7071 16.7071C16.5196 16.8946 16.2652 17 16 17H13M8 14L12 10M12 10L8 6M12 10H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Salir</span>
                </a>
            </nav>
        </div>
    </div>
</header>
