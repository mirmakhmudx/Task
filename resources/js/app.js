import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Region dinamik selector
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('[data-region-selector]');
    if (!container) return;

    const ajaxUrl = container.dataset.source;
    const rootSelect = document.getElementById('region-root');
    const childrenContainer = document.getElementById('region-children');

    if (!rootSelect || !childrenContainer) return;

    function buildSelect(parentId) {
        fetch(ajaxUrl + '?parent=' + parentId)
            .then(r => r.json())
            .then(regions => {
                if (!regions.length) return;

                const wrapper = document.createElement('div');
                wrapper.style.marginTop = '8px';

                const select = document.createElement('select');
                select.name = 'region_id';
                select.className = 'form-select';
                select.style.cssText = 'border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;';

                const emptyOpt = document.createElement('option');
                emptyOpt.value = parentId;
                emptyOpt.textContent = '— Hammasi —';
                select.appendChild(emptyOpt);

                regions.forEach(region => {
                    const opt = document.createElement('option');
                    opt.value = region.id;
                    opt.textContent = region.name;
                    select.appendChild(opt);
                });

                wrapper.appendChild(select);
                childrenContainer.appendChild(wrapper);

                select.addEventListener('change', function () {
                    // Bu selectdan keyingi barcha selectlarni o'chirish
                    const allWrappers = childrenContainer.querySelectorAll('div');
                    let found = false;
                    allWrappers.forEach(w => {
                        if (found) w.remove();
                        if (w.contains(select)) found = true;
                    });

                    if (this.value) buildSelect(this.value);
                });
            });
    }

    rootSelect.addEventListener('change', function () {
        childrenContainer.innerHTML = '';
        // Root selectdagi name ni olib tashlaymiz — child select name oladi
        if (this.value) {
            rootSelect.removeAttribute('name');
            buildSelect(this.value);
        } else {
            rootSelect.name = 'region_id';
        }
    });
});
