@foreach($categories as $category)
    @if($category->children->isEmpty())
        {{-- Oxirgi kategoriya — bosish mumkin --}}
        <a href="{{ route('cabinet.adverts.create.region', $category) }}"
           class="d-flex align-items-center justify-content-between text-decoration-none"
           style="padding:12px 14px;border-radius:10px;margin-bottom:4px;transition:background 0.15s;color:#111827;{{ ($depth ?? 0) > 0 ? 'margin-left:' . (($depth) * 16) . 'px;' : '' }}"
           onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
            <div class="d-flex align-items-center gap-2">
                <span style="width:7px;height:7px;border-radius:50%;background:#d1d5db;display:inline-block;"></span>
                <span style="font-size:0.9rem;font-weight:500;">{{ $category->name }}</span>
            </div>
            <svg width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </a>
    @else
        {{-- Ota kategoriya — expand/collapse --}}
        <div style="{{ ($depth ?? 0) > 0 ? 'margin-left:' . (($depth) * 16) . 'px;' : '' }}margin-bottom:4px;">
            <div class="d-flex align-items-center gap-2"
                 style="padding:12px 14px;border-radius:10px;cursor:pointer;user-select:none;color:#374151;"
                 onclick="toggleCategory(this)"
                 onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                <svg class="toggle-icon" width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="2.5"
                     viewBox="0 0 24 24" style="transition:transform 0.2s;transform:rotate(0deg);">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
                <span style="font-size:0.9rem;font-weight:600;color:#111827;">{{ $category->name }}</span>
                <span style="font-size:0.75rem;color:#9ca3af;font-weight:500;">{{ $category->children->count() }}</span>
            </div>
            <div class="category-children" style="display:none;">
                @include('cabinet.adverts.create._categories', [
                    'categories' => $category->children,
                    'depth' => ($depth ?? 0) + 1
                ])
            </div>
        </div>
    @endif
@endforeach

@once
    <script>
        function toggleCategory(el) {
            const children = el.nextElementSibling;
            const icon = el.querySelector('.toggle-icon');
            const isOpen = children.style.display !== 'none';
            children.style.display = isOpen ? 'none' : 'block';
            icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(90deg)';
        }
    </script>
@endonce
