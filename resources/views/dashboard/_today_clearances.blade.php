<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th class="ps-4">#</th>
                <th>Applicant</th>
                <th>Clearance Type</th>
                <th>Status</th>
                <th class="pe-4 text-end">Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $clearance)
                <tr>
                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $clearance->applicant?->first_name ? trim($clearance->applicant->first_name . ' ' . $clearance->applicant->last_name) : 'N/A' }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill">{{ $clearance->clearance_type ? strtoupper($clearance->clearance_type) : 'N/A' }}</span></td>
                    <td><span class="badge rounded-pill {{ $clearance->approvalStatusClass() }}">{{ $clearance->approvalStatusLabel() }}</span></td>
                    <td class="pe-4 text-end text-nowrap">{{ $clearance->created_at?->format('h:i A') ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-person-badge d-block mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                        No clearances created today.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>