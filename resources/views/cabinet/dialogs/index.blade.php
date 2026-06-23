@extends('layouts.app')

@section('content')
    <div class="mb-4">@include('cabinet.dialogs._nav')</div>

    @php $me = auth()->id(); @endphp

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                    <tr>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Advert</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">With</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Role</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Updated</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Unread</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dialogs as $dialog)
                        @php
                            $isOwner = $me === $dialog->user_id;
                            $other   = $isOwner ? $dialog->client : $dialog->owner;
                            $unread  = $isOwner ? $dialog->user_new_messages : $dialog->client_new_messages;
                        @endphp
                        <tr style="border-bottom:1px solid #f0f1f5;">
                            <td style="padding:12px 16px;">
                                <a href="{{ route('cabinet.dialogs.show', $dialog) }}"
                                   style="color:#2563eb;text-decoration:none;font-weight:500;">
                                    {{ $dialog->advert?->title ?? 'Advert #' . $dialog->advert_id }}
                                </a>
                            </td>
                            <td style="padding:12px 16px;color:#374151;">{{ $other?->name ?? '—' }}</td>
                            <td style="padding:12px 16px;color:#6b7280;">{{ $isOwner ? 'Seller' : 'Buyer' }}</td>
                            <td style="padding:12px 16px;color:#6b7280;">{{ $dialog->updated_at->format('Y-m-d H:i') }}</td>
                            <td style="padding:12px 16px;">
                                @if($unread > 0)
                                    <span style="background:#dc2626;color:#fff;padding:2px 9px;border-radius:20px;font-size:0.78rem;font-weight:600;">{{ $unread }}</span>
                                @else
                                    <span style="color:#9ca3af;">0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4" style="color:#9ca3af;">No dialogs yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $dialogs->links() }}</div>
@endsection
