@extends('layout.mainLayout')
@section('title', 'Percepciones y Deducciones')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Percepciones y Deducciones</h1>
        <p class="dashboard-subtitle">Gestiona las definiciones para nómina del personal</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2>Percepciones</h2>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <form action="{{ route('admin.catalogues.perceptions.store') }}" method="POST" style="margin-bottom: 24px;" data-password-confirm>
                        @csrf
                        <input type="hidden" name="type" value="perception">
                        <div style="display: flex; gap: 12px;">
                            <input type="text" name="name" class="form-control" placeholder="Nueva percepción" required style="flex: 1;">
                            <button type="submit" class="btn-modern btn-primary" style="white-space: nowrap;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Agregar
                            </button>
                        </div>
                    </form>

                    <div style="max-height: 500px; overflow-y: auto;">
                        @foreach ($perceptions as $perception)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #f9fafb; border-radius: 8px; margin-bottom: 8px;">
                                <span style="font-size: 14px; color: #1a1a1a; font-weight: 500;">{{ $perception->name }}</span>
                                <form action="{{ route('admin.catalogues.perceptions.destroy', $perception->id) }}" method="POST" class="d-inline" data-password-confirm>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-delete" title="Eliminar percepción" style="padding: 8px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="header-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 21V5C16 4.46957 15.7893 3.96086 15.4142 3.58579C15.0391 3.21071 14.5304 3 14 3H10C9.46957 3 8.96086 3.21071 8.58579 3.58579C8.21071 3.96086 8 4.46957 8 5V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 7H18C18.5304 7 19.0391 7.21071 19.4142 7.58579C19.7893 7.96086 20 8.46957 20 9V19C20 19.5304 19.7893 20.0391 19.4142 20.4142C19.0391 20.7893 18.5304 21 18 21H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 7H6C5.46957 7 4.96086 7.21071 4.58579 7.58579C4.21071 7.96086 4 8.46957 4 9V19C4 19.5304 4.21071 20.0391 4.58579 20.4142C4.96086 20.7893 5.46957 21 6 21H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2>Deducciones</h2>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <form action="{{ route('admin.catalogues.perceptions.store') }}" method="POST" style="margin-bottom: 24px;" data-password-confirm>
                        @csrf
                        <input type="hidden" name="type" value="deduction">
                        <div style="display: flex; gap: 12px;">
                            <input type="text" name="name" class="form-control" placeholder="Nueva deducción" required style="flex: 1;">
                            <button type="submit" class="btn-modern btn-primary" style="white-space: nowrap;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Agregar
                            </button>
                        </div>
                    </form>

                    <div style="max-height: 500px; overflow-y: auto;">
                        @foreach ($deductions as $deduction)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #f9fafb; border-radius: 8px; margin-bottom: 8px;">
                                <span style="font-size: 14px; color: #1a1a1a; font-weight: 500;">{{ $deduction->name }}</span>
                                <form action="{{ route('admin.catalogues.perceptions.destroy', $deduction->id) }}" method="POST" class="d-inline" data-password-confirm>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-delete" title="Eliminar deducción" style="padding: 8px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('includes.password-confirm-modal')
@endsection
