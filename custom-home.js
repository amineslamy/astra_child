document.addEventListener('DOMContentLoaded', function() {
    console.log('موتور فرانت‌آند سحاب فعال شد.');

    // --- بخش اول: کنترل سوئیچ تم لایت / دارک با حفظ وضعیت در مرورگر ---
    const themeToggleBtn = document.getElementById('theme_toggle_btn');
    const bodyEl = document.body;

    // بررسی اینکه آیا کاربر قبلاً تم را انتخاب کرده است یا خیر
    const savedTheme = localStorage.getItem('sahab_theme');
    if (savedTheme) {
        bodyEl.className = savedTheme;
        if(savedTheme === 'dark-theme' && themeToggleBtn) {
            themeToggleBtn.innerText = '☀️';
        }
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            if (bodyEl.classList.contains('light-theme')) {
                bodyEl.classList.remove('light-theme');
                bodyEl.classList.add('dark-theme');
                themeToggleBtn.innerText = '☀️';
                localStorage.setItem('sahab_theme', 'dark-theme');
            } else {
                bodyEl.classList.remove('dark-theme');
                bodyEl.classList.add('light-theme');
                themeToggleBtn.innerText = '🌙';
                localStorage.setItem('sahab_theme', 'light-theme');
            }
        });
    }

    // --- بخش دوم: باز و بسته شدن پاپ‌آپ آجاکسی بزرگ جستجو ---
    const openSearchBtn = document.getElementById('open_search_btn');
    const closeSearchBtn = document.getElementById('close_search_btn');
    const searchModal = document.getElementById('search_modal');
    const modalSearchInput = document.getElementById('modal_search_input');

    if (openSearchBtn && searchModal) {
        openSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchModal.style.display = 'flex';
            if (modalSearchInput) modalSearchInput.focus();
        });
    }

    if (closeSearchBtn && searchModal) {
        closeSearchBtn.addEventListener('click', function() {
            searchModal.style.display = 'none';
        });
    }

    // بستن مدال با کلیک روی فضای خالی بیرون کادر
    if (searchModal) {
        searchModal.addEventListener('click', function(e) {
            if (e.target === searchModal) {
                searchModal.style.display = 'none';
            }
        });
    }

    // --- بخش سوم: مدیریت رسانه و تصاویر شاخص وردپرس ---
    const manageImageBtn = document.getElementById('manage_image_btn');
    const removeImageBtn = document.getElementById('remove_image_btn');
    const imagePreviewContainer = document.getElementById('image_preview_container');
    const imagePreviewImg = document.getElementById('uploaded_image_preview');
    const thumbnailIdInput = document.getElementById('page_thumbnail_id');
    
    let mediaUploader;

    if (manageImageBtn) {
        manageImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'انتخاب تصویر مستند رصدی',
                button: { text: 'ضمیمه کردن به گزارش' },
                multiple: false
            });
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                thumbnailIdInput.value = attachment.id;
                imagePreviewImg.src = attachment.url;
                imagePreviewContainer.style.display = 'flex';
            });
            mediaUploader.open();
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            thumbnailIdInput.value = '';
            imagePreviewImg.src = '';
            imagePreviewContainer.style.display = 'none';
        });
    }

    // --- بخش چهارم: پیشنهاد خودکار عنوان بر اساس اولین خط متن ---
    const titleInput = document.querySelector('input[name="osint_title"]');
    setTimeout(() => {
        const editorIframe = document.getElementById('research_content_ifr');
        if (editorIframe) {
            const editorBody = editorIframe.contentWindow.document.body;
            editorBody.addEventListener('input', function() {
                if (titleInput && titleInput.value.trim() === "") {
                    let text = editorBody.innerText || editorBody.textContent;
                    text = text.trim().split('\n')[0];
                    if (text.length > 0) {
                        titleInput.value = text.substring(0, 45) + "...";
                    }
                }
            });
        }
    }, 2000);
});