<a href="{{ route('banner.click', $banner) }}" target="_blank" rel="noopener">
    <img src="{{ Storage::url($banner->file) }}" alt="{{ $banner->name }}" style="display:block;border:0;">
</a>
