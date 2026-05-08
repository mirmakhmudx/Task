@extends('layouts.app')

@section('content')
    @include('admin.adverts.categories._nav')

    <a href="{{ route('admin.adverts.categories.index') }}"
       class="d-inline-flex align-items-center gap-1 mb-3 text-decoration-none"
       style="font-size:0.85rem;color:#6b7280;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Orqaga
    </a>

    {{-- Category info --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-semibold mb-0" style="color:#1a1a2e;">{{ $category->name }}</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.adverts.categories.edit', $category) }}"
                       class="d-inline-flex align-items-center gap-1"
                       style="padding:5px 14px;font-size:0.82rem;border-radius:8px;background:#eff6ff;color:#3b82f6;border:1px solid #dbeafe;text-decoration:none;font-weight:500;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.adverts.categories.destroy', $category) }}"
                          onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="d-inline-flex align-items-center gap-1"
                                style="padding:5px 14px;font-size:0.82rem;border-radius:8px;background:#fff5f5;color:#ef4444;border:1px solid #fecaca;font-weight:500;cursor:pointer;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <table class="table mb-0" style="font-size:0.9rem;">
                <tbody>
                    <tr style="background:#f8f9fb;">
                        <td style="width:25%;font-weight:600;color:#6b7280;border:none;padding:11px 16px;">ID</td>
                        <td style="color:#1a1a2e;border:none;padding:11px 16px;">{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Name</td>
                        <td style="color:#1a1a2e;border:none;padding:11px 16px;">{{ $category->name }}</td>
                    </tr>
                    <tr style="background:#f8f9fb;">
                        <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Slug</td>
                        <td style="border:none;padding:11px 16px;font-family:monospace;font-size:0.85rem;">{{ $category->slug }}</td>
                    </tr>
                    @if($category->parent)
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Parent</td>
                        <td style="border:none;padding:11px 16px;">
                            <a href="{{ route('admin.adverts.categories.show', $category->parent) }}"
                               style="color:#3b82f6;text-decoration:none;">
                                {{ $category->parent->name }}
                            </a>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Attributes --}}
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center justify-content-between"
             style="padding:1.25rem 1.75rem;">
            <h6 class="fw-semibold mb-0" style="color:#1a1a2e;font-size:0.9rem;">
                Atributlar
            </h6>
            <a href="{{ route('admin.adverts.categories.attributes.create', $category) }}"
               class="btn btn-primary btn-sm" style="border-radius:8px;font-size:0.82rem;">
                + Add Attribute
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.875rem;">
                <thead style="background:#f8f9fb;border-top:1px solid #e8eaf0;">
                    <tr>
                        <th style="padding:10px 16px;font-weight:600;color:#6b7280;width:70px;">Sort</th>
                        <th style="padding:10px 16px;font-weight:600;color:#6b7280;">Name</th>
                        <th style="padding:10px 16px;font-weight:600;color:#6b7280;">Type</th>
                        <th style="padding:10px 16px;font-weight:600;color:#6b7280;">Required</th>
                        <th style="padding:10px 16px;width:80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Parent attributes --}}
                    @if($parentAttributes->count())
                    <tr>
                        <td colspan="5" style="padding:8px 16px;background:#f8f9fb;font-size:0.78rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e8eaf0;">
                            Parent attributes
                        </td>
                    </tr>
                    @foreach($parentAttributes as $attribute)
                    <tr style="border-bottom:1px solid #f0f1f5;opacity:0.7;">
                        <td style="padding:10px 16px;color:#9ca3af;">{{ $attribute->sort }}</td>
                        <td style="padding:10px 16px;color:#6b7280;">
                            {{ $attribute->name }}
                            @if($attribute->isSelect())
                                <span style="background:#ede9fe;color:#7c3aed;font-size:0.72rem;padding:2px 7px;border-radius:10px;margin-left:4px;">select</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;">
                            <span style="background:#f0f1f5;color:#374151;font-size:0.78rem;padding:2px 8px;border-radius:10px;font-family:monospace;">
                                {{ $attribute->type }}
                            </span>
                        </td>
                        <td style="padding:10px 16px;">
                            @if($attribute->required)
                                <span style="background:#dcfce7;color:#16a34a;font-size:0.78rem;padding:2px 8px;border-radius:10px;">Yes</span>
                            @else
                                <span style="color:#9ca3af;font-size:0.82rem;">No</span>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    @endforeach
                    @endif

                    {{-- Own attributes --}}
                    <tr>
                        <td colspan="5" style="padding:8px 16px;background:#f8f9fb;font-size:0.78rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e8eaf0;">
                            Own attributes
                        </td>
                    </tr>
                    @forelse($attributes as $attribute)
                    <tr style="border-bottom:1px solid #f0f1f5;">
                        <td style="padding:10px 16px;color:#9ca3af;">{{ $attribute->sort }}</td>
                        <td style="padding:10px 16px;font-weight:500;color:#1a1a2e;">
                            {{ $attribute->name }}
                            @if($attribute->isSelect())
                                <span style="background:#ede9fe;color:#7c3aed;font-size:0.72rem;padding:2px 7px;border-radius:10px;margin-left:4px;">select</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;">
                            <span style="background:#f0f1f5;color:#374151;font-size:0.78rem;padding:2px 8px;border-radius:10px;font-family:monospace;">
                                {{ $attribute->type }}
                            </span>
                        </td>
                        <td style="padding:10px 16px;">
                            @if($attribute->required)
                                <span style="background:#dcfce7;color:#16a34a;font-size:0.78rem;padding:2px 8px;border-radius:10px;">Yes</span>
                            @else
                                <span style="color:#9ca3af;font-size:0.82rem;">No</span>
                            @endif
                        </td>
                        <td style="padding:8px 16px;">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.adverts.categories.attributes.edit', [$category, $attribute]) }}"
                                   title="Edit"
                                   style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;background:#eff6ff;border:1px solid #dbeafe;color:#3b82f6;text-decoration:none;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.adverts.categories.attributes.destroy', [$category, $attribute]) }}"
                                      onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Delete"
                                            style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;background:#fff5f5;border:1px solid #fecaca;color:#ef4444;cursor:pointer;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:12px 16px;color:#9ca3af;font-size:0.85rem;">
                            None
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Children --}}
    @if($children->count())
    <div class="card">
        <div class="card-body" style="padding:1.25rem 1.75rem 0.75rem;">
            <h6 class="fw-semibold mb-3" style="color:#1a1a2e;font-size:0.9rem;">
                Ichki kategoriyalar
                <span style="background:#f0f1f5;color:#6b7280;padding:2px 8px;border-radius:20px;font-size:0.75rem;font-weight:400;margin-left:6px;">{{ $children->count() }}</span>
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.9rem;">
                <tbody>
                    @foreach($children as $child)
                    <tr style="border-bottom:1px solid #f0f1f5;">
                        <td style="padding:10px 16px;font-weight:500;color:#1a1a2e;">
                            @for($i = 0; $i < $child->depth; $i++)
                                <span style="color:#d1d5db;">—</span>
                            @endfor
                            <a href="{{ route('admin.adverts.categories.show', $child) }}"
                               style="color:#1a1a2e;text-decoration:none;">
                                {{ $child->name }}
                            </a>
                        </td>
                        <td style="padding:10px 16px;color:#6b7280;font-family:monospace;font-size:0.82rem;">{{ $child->slug }}</td>
                        <td style="padding:8px 16px;width:70px;">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.adverts.categories.edit', $child) }}"
                                   style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;border-radius:7px;background:#eff6ff;border:1px solid #dbeafe;color:#3b82f6;text-decoration:none;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection
