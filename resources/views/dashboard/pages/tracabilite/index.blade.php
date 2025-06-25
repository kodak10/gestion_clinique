@extends('dashboard.layouts.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <h2 class="page-title">Journal des activités</h2>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="log-table">
                    <thead>
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
                                    <span class="badge bg-{{ $activity->event == 'updated' ? 'warning' : ($activity->event == 'created' ? 'success' : 'danger') }}">
                                        {{ ucfirst($activity->event) }}
                                    </span>
                                </td>
                                <td>{{ class_basename($activity->subject_type) }}</td>
                                <td>{{ $activity->subject_id }}</td>
                                <td>
                                    {{ optional($activity->causer)->name ?? 'Système' }}
                                    <br>
                                    <small class="text-muted">{{ optional($activity->causer)->pseudo }}</small>
                                </td>
                                <td>
                                    @if($activity->event == 'updated' && $activity->properties->has('old'))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($activity->properties['old'] as $key => $value)
                                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <em>N/A</em>
                                    @endif
                                </td>
                                <td>
                                    @if($activity->properties->has('attributes'))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($activity->properties['attributes'] as $key => $value)
                                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <em>N/A</em>
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
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#log-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json'
        }
    });
});
</script>
@endpush
