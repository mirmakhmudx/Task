<div data-banner
     data-format="{{ $format ?? '240x400' }}"
     data-region="{{ $region->id ?? '' }}"
     data-category="{{ $category->id ?? '' }}"></div>

@once
<script>
document.querySelectorAll('[data-banner]').forEach(function (el) {
    var p = new URLSearchParams({
        format:   el.dataset.format || '',
        region:   el.dataset.region || '',
        category: el.dataset.category || ''
    });
    fetch('/banner/get?' + p.toString())
        .then(function (r) { return r.text(); })
        .then(function (html) { if (html.trim()) { el.innerHTML = html; } });
});
</script>
@endonce
