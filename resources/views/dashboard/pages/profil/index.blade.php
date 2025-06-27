@extends('dashboard.layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <h2 class="page-title">Mon profil</h2>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <form method="POST" action="{{ route('profile.update') }}" class="form-loader">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <h3 class="card-title">Mes informations</h3>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nom complet</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pseudo</label>
                                <input type="text" name="pseudo" class="form-control" value="{{ old('pseudo', $user->pseudo) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Numéro de téléphone</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                            </div>
                        </div>

                        <h3 class="card-title mt-4">Changer le mot de passe</h3>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="new_password" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Confirmation</label>
                                <input type="password" name="new_password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
