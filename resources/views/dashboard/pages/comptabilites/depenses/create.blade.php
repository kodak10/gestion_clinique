<div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Dépenses</h2>
            </div>
            <div class="col">

                <a href="#" class="btn btn-2 float-end" data-bs-toggle="modal" data-bs-target="#modal-category">Ajouter Une Catégorie</a>
            </div>
        </div>

<form action="{{ route('depenses.store') }}" method="POST">
                @csrf
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
                                <label class="form-label">Catégorie</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->nom }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Libellé</label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror" name="libelle" value="{{ old('libelle') }}" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                             <div class="mb-3">
                                <label class="form-label">Montant</label>
                                <input type="text" step="0.01" class="form-control @error('montant') is-invalid @enderror" name="montant" value="{{ old('montant') }}" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Date d'ecriture</label>
                                <input type="date" class="form-control @error('date_ecriture') is-invalid @enderror" name="date_ecriture" value="{{ old('date_ecriture') }}" required>
                                @error('date_ecriture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto">Ajouter</button>
                </div>
            </form>