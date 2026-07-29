<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th class="ps-4">#</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th class="pe-4 text-end">Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $applicant)
                <tr>
                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ trim($applicant->first_name . ' ' . $applicant->last_name) }}</td>
                    <td>{{ $applicant->contact_no ?: 'N/A' }}</td>
                    <td>{{ trim(collect([$applicant->address_line, $applicant->barangay, $applicant->city])->filter()->implode(', ')) ?: 'N/A' }}</td>
                    <td class="pe-4 text-end text-nowrap">{{ $applicant->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-people d-block mb-2" style="font-size: 2rem; opacity: 0.4;"></i>
                        No applicants found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>