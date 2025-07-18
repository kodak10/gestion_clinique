@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Journal des activités</h2>
            </div>
            <div class="col-auto">
                <div class="btn-list">
                    <a href="#" class="btn btn-primary d-none d-sm-inline-block" onclick="window.print()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                            <rect x="7" y="13" width="10" height="8" rx="2" />
                        </svg>
                        Imprimer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="log-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Modèle</th>
                                <th>ID</th>
                                <th>Par</th>
                                <th>Ancien</th>
                                <th>Nouveau</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $index => $activity)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                   
                                    <td>
                                        @php
                                            $event = $activity->event;
                                            $status = [
                                                'created' => 'Création',
                                                'updated' => 'Modification',
                                                'deleted' => 'Suppression',
                                                'restored' => 'Restauration',
                                            ];
                                            $badgeClass = match($event) {
                                                'created' => 'bg-success',
                                                'updated' => 'bg-warning',
                                                'deleted' => 'bg-danger',
                                                'restored' => 'bg-info',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">
                                            {{ $status[$event] ?? ucfirst($event) }}
                                        </span>
                                    </td>

                                    <td>{{ class_basename($activity->subject_type) }}</td>
                                    <td>{{ $activity->subject_id }}</td>
                                    <td>
                                        {{ optional($activity->causer)->name ?? 'Système' }}
                                        @if($activity->causer && $activity->causer->pseudo)
                                            <br>
                                            <small class="text-muted">{{ $activity->causer->pseudo }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->event == 'updated' && $activity->properties->has('old'))
                                            <ul class="list-unstyled mb-0">
                                                @foreach($activity->properties['old'] as $key => $value)
                                                    <li>
                                                        <strong>{{ $key }}:</strong>
                                                        @if(is_array($value) || is_object($value))
                                                            <pre class="mb-0">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            {{ $value ?? 'NULL' }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @elseif($activity->event == 'deleted')
                                            <div class="alert alert-danger p-2 mb-0">
                                                @if($activity->properties->has('old'))
                                                    <ul class="mb-0">
                                                        @foreach($activity->properties['old'] as $key => $value)
                                                            <li>
                                                                <strong>{{ $key }}:</strong>
                                                                @if(is_array($value) || is_object($value))
                                                                    <pre class="mb-0">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                                @else
                                                                    {{ $value ?? 'NULL' }}
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <em>Données non disponibles</em>
                                                @endif
                                            </div>
                                        @else
                                            <em class="text-muted">N/A</em>
                                        @endif
                                    </td>

                                    <td>
                                        @if($activity->properties->has('attributes'))
                                            <ul class="list-unstyled mb-0">
                                                @foreach($activity->properties['attributes'] as $key => $value)
                                                    <li>
                                                        <strong>{{ $key }}:</strong>
                                                        @if(is_array($value) || is_object($value))
                                                            <pre class="mb-0">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            {{ $value ?? 'NULL' }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @elseif($activity->event == 'deleted')
                                            <em class="text-muted">Supprimé</em>
                                        @else
                                            <em class="text-muted">N/A</em>
                                        @endif
                                    </td>
                                    <td>{{ $activity->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<style>
    @media print {
        .no-print, .no-print * {
            display: none !important;
        }
        body {
            font-size: 12px;
            padding: 0;
        }
        .container-xl {
            width: 100%;
            max-width: 100%;
            padding: 0;
        }
        table {
            width: 100% !important;
            font-size: 10px;
        }
    }
    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        background: #f8f9fa;
        padding: 5px;
        border-radius: 3px;
        margin-bottom: 5px;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    $('#log-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json'
        },
        dom: '<"top"Bf>rt<"bottom"lip><"clear">',
        buttons: [
            {
                extend: 'print',
                text: 'Imprimer',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        responsive: true,
        order: [[7, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush