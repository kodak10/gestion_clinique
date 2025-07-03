@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Utilisateurs</h2>
            </div>
            <div class="col">
                <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-create"> Ajouter </a>
            </div>
        </div>
    </div>
</div>

<!-- Messages globaux -->
@if(session('success'))
<div class="container-xl">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container-xl">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach($users as $user)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ Storage::url($user->image ?? 'avatars/default-avatar.png') }})"></span>
                            <h3 class="m-0 mb-1"><a href="#">{{ $user->name }}</a></h3>
                            <div class="mt-3">
                                @foreach($user->roles as $role)
                                    <span class="badge bg-purple-lt">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $user->id }}">
                                <i class="fas fa-edit me-1 text-primary"></i> Modifier
                            </a>
                            <a href="#" class="card-btn" 
                                title="{{ $user->status == 'Actif' ? 'Désactiver l\'utilisateur' : 'Activer l\'utilisateur' }}"
                                onclick="event.preventDefault(); document.getElementById('toggle-status-form-{{ $user->id }}').submit();">
                                @if($user->status == 'Actif')
                                    <i class="fas fa-toggle-on me-1 text-success"></i> Désactiver
                                @else
                                    <i class="fas fa-toggle-off me-1 text-muted"></i> Activer
                                @endif
                            </a>
                            
                            <form id="toggle-status-form-{{ $user->id }}" 
                                method="POST" 
                                action="{{ route('utilisateurs.toggleStatus', $user->id) }}" 
                                class="d-none form-loader">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal d'édition -->
                <div class="modal modal-blur fade" id="modal-edit-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier l'utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('utilisateurs.update', $user->id) }}" class="form-loader">
                                    @csrf
                                    @method('PUT')
                                    
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Rôles</label>
                                                <select class="form-select roles-select" name="roles[]" multiple required>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('roles')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom Complet</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Pseudo</label>
                                                <input type="text" class="form-control @error('pseudo') is-invalid @enderror" name="pseudo" value="{{ old('pseudo', $user->pseudo) }}" required disabled>
                                                @error('pseudo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required disabled>
                                                @error('phone_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</a>
                                        <button type="submit" class="btn btn-primary btn-5 ms-auto">
                                            Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de création -->
<div class="modal modal-blur fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Création d'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('utilisateurs.store') }}" class="form-loader">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Rôles</label>
                                <select class="form-select roles-select @error('roles') is-invalid @enderror" name="roles[]" multiple required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Nom Complet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nom de l'utilisateur" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Pseudo</label>
                                <input type="text" class="form-control @error('pseudo') is-invalid @enderror" name="pseudo" value="{{ old('pseudo') }}" required>
                                @error('pseudo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal"> Annuler </a>
                        <button type="submit" class="btn btn-primary btn-5 ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .ts-dropdown {
        z-index: 9999 !important;
    }
    .tom-select .item {
        background: #e9ecef !important;
        border-radius: 4px;
        padding: 2px 6px;
        margin-right: 4px;
    }
    .modal .ts-control {
        min-height: 42px;
    }
    .invalid-feedback.d-block {
        display: block !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialisation pour la modal de création
    const createModal = document.getElementById('modal-create');
    createModal.addEventListener('shown.bs.modal', function() {
        const select = this.querySelector('.roles-select');
        if (select && !select.tomselect) {
            new TomSelect(select, {
                plugins: ['remove_button'],
                dropdownParent: 'body',
                render: {
                    item: function(data, escape) {
                        return '<div class="badge bg-primary me-1">' + escape(data.text) + '</div>';
                    },
                    option: function(data, escape) {
                        return '<div>' + escape(data.text) + '</div>';
                    }
                }
            });
        }
    });

    // Initialisation pour les modals d'édition
    document.querySelectorAll('[id^="modal-edit-"]').forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const select = this.querySelector('.roles-select');
            if (select && !select.tomselect) {
                new TomSelect(select, {
                    plugins: ['remove_button'],
                    dropdownParent: 'body',
                    render: {
                        item: function(data, escape) {
                            return '<div class="badge bg-primary me-1">' + escape(data.text) + '</div>';
                        },
                        option: function(data, escape) {
                            return '<div>' + escape(data.text) + '</div>';
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush