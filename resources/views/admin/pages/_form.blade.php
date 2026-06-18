<div class="mb-3">
    <label class="form-label fw-semibold">Title</label>
    <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" class="form-control">
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Menu Title</label>
    <input type="text" name="menu_title" value="{{ old('menu_title', $page->menu_title ?? '') }}" class="form-control">
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Slug</label>
    <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" class="form-control">
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Parent</label>
    <select name="parent" class="form-select">
        <option value="">— Root —</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}" {{ (string) old('parent', $page->parent_id ?? '') === (string) $parent->id ? 'selected' : '' }}>
                {{ str_repeat('— ', $parent->depth) }}{{ $parent->menu_title }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Content</label>
    <textarea name="content" id="content-editor" rows="6" class="form-control">{{ old('content', $page->content ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $page->description ?? '') }}</textarea>
</div>
