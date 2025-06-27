@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Assurances</h2>
            </div>
            <div class="col">
                <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-report">Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach($assurances as $assurance)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ $assurance->image_url }})"></span>
                            <h3 class="m-0 mb-1"><a href="#">{{ $assurance->name }}</a></h3>
                            <div class="text-secondary">Taux: {{ $assurance->taux }}%</div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $assurance->id }}">
                                <i class="fas fa-edit me-1 text-primary"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modal d'édition -->
                <div class="modal modal-blur fade" id="edit-modal-{{ $assurance->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier l'assurance</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('assurances.update', $assurance->id) }}" class="form-loader" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
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
                                                <label class="form-label">Nom d'Assurance</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $assurance->name }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Taux</label>
                                                <input type="number" class="form-control @error('taux') is-invalid @enderror" name="taux" value="{{ $assurance->taux }}" required>
                                                @error('taux')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Numéro de téléphone</label>
                                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ $assurance->phone_number }}" required>
                                                @error('phone_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $assurance->email }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Siège</label>
                                                <input type="text" class="form-control @error('siege') is-invalid @enderror" name="siege" value="{{ $assurance->siege }}">
                                                @error('siege')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Image</label>
                                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary btn-5 ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <path d="M17 21v-8H7v8"></path>
                                            <path d="M7 3v5h8"></path>
                                        </svg>
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de création -->
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Création d'assurance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('assurances.store') }}" class="form-loader" enctype="multipart/form-data">
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
                                <label class="form-label">Nom d'Assurance</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nom de l'assurance" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Taux</label>
                                <input type="number" class="form-control @error('taux') is-invalid @enderror" name="taux" placeholder="Taux" value="{{ old('taux') }}" required>
                                @error('taux')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro de téléphone</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Siège</label>
                                <input type="text" class="form-control @error('siege') is-invalid @enderror" name="siege" value="{{ old('siege') }}">
                                @error('siege')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</a>
                <button type="submit" class="btn btn-primary btn-5 ms-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
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

@endsection