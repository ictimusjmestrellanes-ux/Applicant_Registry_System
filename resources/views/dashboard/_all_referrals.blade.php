<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th class="ps-4">#</th>
                <th>Applicant</th>
                <th>Referral Type</th>
                <th>Status</th>
                <th class="pe-4 text-end">Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $referral)
                <tr>
                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $referral->applicant?->first_name ? trim($referral->applicant->first_name . ' ' . $referral->applicant->last_name) : 'N/A' }}</td>
                    <td><span class="badge bg-warning bg-opacity-10 text-dark rounded-pill">{{ $referral->referral_type ? str_replace('_', ' ', ucwords($referral->referral_type)) : 'N/A' }}</span></td>
                    <td><span class="badge rounded-pill {{ $referral->approvalStatusClass() }}">{{ $referral->approvalStatusLabel() }}</span></td>
                    <td class="pe-4 text-end text-nowrap">{{ $referral->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-folder2-open d-block mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                        No referrals found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>