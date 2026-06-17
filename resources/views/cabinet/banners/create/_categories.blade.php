@foreach($categories as $category)
    @if($category->children->isEmpty())
        <a href="{{ route('cabinet.banners.create.region', $category) }}"
           class="d-flex align-items-center justify-content-between text-decoration-none"
           style="padding:12px 14px;border-radius:10px;margin-bottom:4px;color:#111827;{{ ($depth ?? 0) > 0 ? 'margin-left:'.(($depth)*16).'px;' : '' }}"
           onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
            <span style="font-size:0.9rem;font-weight:500;">{{ $category->name }}</span>
            <svg width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
    @else
        <div style="{{ ($depth ?? 0) > 0 ? 'margin-left:'.(($depth)*16).'px;' : '' }}margin-bottom:4px;">
            <div class="d-flex align-items-center gap-2" style="padding:12px 14px;border-radius:10px;cursor:pointer;color:#374151;"
                 onclick="toggleBannerCat(this)"
                 onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                <svg class="toggle-icon" width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="2.5" viewBox="0 0 24 24" style="transition:transform 0.2s;"><path d="M9 18l6-6-6-6"/></svg>
                <span style="font-size:0.9rem;font-weight:600;color:#111827;">{{ $category->name }}</span>
                <span style="font-size:0.75rem;color:#9ca3af;">{{ $category->children->count() }}</span>
            </div>
            <div class="category-children" style="display:none;">
                @include('cabinet.banners.create._categories', ['categories' => $category->children, 'depth' => ($depth ?? 0) + 1])
            </div>
        </div>
    @endif
@endforeach

@once
<script>
function toggleBannerCat(el){
    const c = el.nextElementSibling, i = el.querySelector('.toggle-icon');
    const open = c.style.display !== 'none';
    c.style.display = open ? 'none' : 'block';
    i.style.transform = open ? 'rotate(0deg)' : 'rotate(90deg)';
}
</script>
@endonce
