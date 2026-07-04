document.addEventListener('DOMContentLoaded', function() {
    console.log('سیستم رسانه و تگ سحاب فعال شد.');

    // بازوهای مربوط به مدیریت تصویر شاخص
    const manageImageBtn = document.getElementById('manage_image_btn');
    const removeImageBtn = document.getElementById('remove_image_btn');
    const imagePreviewContainer = document.getElementById('image_preview_container');
    const imagePreviewImg = document.getElementById('uploaded_image_preview');
    const thumbnailIdInput = document.getElementById('page_thumbnail_id');
    
    let mediaUploader;

    if (manageImageBtn) {
        manageImageBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // اگر پاپ‌آپ از قبل ساخته شده، دوباره بازش کن
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // ساخت پاپ‌آپ نیتیو مدیریت رسانه وردپرس
            mediaUploader = wp.media({
                title: 'انتخاب یا بارگذاری تصویر شاخص سند',
                button: { text: 'تایید و انتخاب تصویر' },
                multiple: false // فقط یک عکس مجاز است
            });

            // وقتی کاربر عکس را انتخاب کرد و دکمه تایید را زد
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // قرار دادن آیدی عکس در اینپوت مخفی برای ارسال به دیتابیس
                thumbnailIdInput.value = attachment.id;
                
                // نمایش پیش‌نمایش عکس در صفحه
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

    // هوشمندسازی انتخاب عنوان بر اساس متن ویرایشگر
    const titleInput = document.querySelector('input[name="research_title"]');
    setTimeout(() => {
        const editorIframe = document.getElementById('research_content_ifr');
        if (editorIframe) {
            const editorBody = editorIframe.contentWindow.document.body;
            editorBody.addEventListener('input', function() {
                if (titleInput && titleInput.value.trim() === "") {
                    let text = editorBody.innerText || editorBody.textContent;
                    text = text.trim().split('\n')[0];
                    if (text.length > 0) {
                        titleInput.value = text.substring(0, 40) + "...";
                    }
                }
            });
        }
    }, 2000);
});