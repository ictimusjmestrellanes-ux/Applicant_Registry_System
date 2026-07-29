<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th class="ps-4">#</th>
                <th>Applicant</th>
                <th>Peso ID</th>
                <th>O.R No.</th>
                <th>Status</th>
                <th class="pe-4 text-end">Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $permit)
                <tr>
                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $permit->applicant?->first_name ? trim($permit->applicant->first_name . ' ' . $permit->applicant->last_name) : 'N/A' }}</td>
                    <td class="text-nowrap">{{ $permit->peso_id_no ? 'OP' . strtoupper($permit->peso_id_no) : 'N/A' }}</td>
                    <td>{{ $permit->permit_or_no ?: 'N/A' }}</td>
                    <td><span class="badge rounded-pill {{ $permit->approvalStatusClass() }}">{{ $permit->approvalStatusLabel() }}</span></td>
                    <td class="pe-4 text-end text-nowrap">{{ $permit->created_at?->format('h:i A') ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-patch-check d-block mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                        No permits created today.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>