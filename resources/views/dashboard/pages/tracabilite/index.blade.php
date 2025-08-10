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
                            <!-- Les données seront chargées via AJAX -->
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
        processing: true,
        serverSide: true,
        ajax: "{{ route('tracabilite.data') }}",
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
                },
                customize: function (win) {
                    $(win.document.body).find('table').addClass('print-table');
                    $(win.document.body).find('pre').css({
                        'white-space': 'pre-wrap',
                        'word-wrap': 'break-word',
                        'background': '#f8f9fa',
                        'padding': '5px',
                        'border-radius': '3px',
                        'margin-bottom': '5px'
                    });
                }
            }
        ],
        responsive: true,
        order: [[7, 'desc']],
        pageLength: 25,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'action', name: 'event' },
            { data: 'model', name: 'subject_type' },
            { data: 'subject_id', name: 'subject_id' },
            { data: 'user', name: 'causer.name' },
            { data: 'old', name: 'properties', orderable: false, searchable: false },
            { data: 'new', name: 'properties', orderable: false, searchable: false },
            { data: 'date', name: 'created_at' }
        ],
        columnDefs: [
            { width: '5%', targets: 0 },
            { width: '10%', targets: 1 },
            { width: '10%', targets: 2 },
            { width: '5%', targets: 3 },
            { width: '10%', targets: 4 },
            { width: '25%', targets: 5 },
            { width: '25%', targets: 6 },
            { width: '10%', targets: 7 }
        ]
    });
});
</script>
@endpush