<?php
/*
Template Name: Home Custom Page v8
*/

if (is_user_logged_in()) {
    add_action('wp_enqueue_scripts', function() {
        wp_enqueue_media();
    });
}

// وضعیت اولیه استروک فرم (قرمز پیش‌فرض برای حالت ذخیره نشده)
$form_status_class = 'status-unsaved';

// پردازش فرم ورود یافته‌های رصدی
if (isset($_POST['submit_osint']) && is_user_logged_in()) {
    if (!empty($_POST['osint_title']) && !empty($_POST['osint_content'])) {
        
        $new_case = array(
            'post_title'    => sanitize_text_field($_POST['osint_title']),
            'post_content'  => wp_kses_post($_POST['osint_content']),
            'post_status'   => sanitize_text_field($_POST['osint_status']),
            'post_type'     => 'post',
            'post_category' => array(intval($_POST['osint_cat'])),
            'tags_input'    => sanitize_text_field($_POST['osint_tags'])
        );

        $post_id = wp_insert_post($new_case);

        if ($post_id && !is_wp_error($post_id)) {
            $thumbnail_id = intval($_POST['page_thumbnail_id']);
            if ($thumbnail_id > 0) {
                set_post_thumbnail($post_id, $thumbnail_id);
            }
            $message = "گزارش جدید با موفقیت در سیستم ثبت شد.";
            $form_status_class = 'status-saved'; // تغییر استروک به سبز
        } else {
            $error = "خطا در ذخیره گزارش.";
        }
    } else {
        $error = "لطفاً عنوان و متن گزارش را وارد کنید.";
    }
}

if (isset($_POST['login_submit'])) {
    $login_data = array(
        'user_login'    => sanitize_text_field($_POST['log']),
        'user_password' => $_POST['pwd'],
        'remember'      => true
    );
    $user_signon = wp_signon($login_data, false);
    if (is_wp_error($user_signon)) {
        $login_error = "نام کاربری یا کلمه عبور اشتباه است.";
    } else {
        wp_redirect(get_permalink());
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه رصد اطلاعاتی سحاب</title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?> 
</head>
<body class="light-theme"> <div class="sahab-container">
    
    <header class="card app-header">
        <div class="header-brand">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/sahab-logo.png" alt="سحاب" class="app-logo">
            <div class="header-titles">
                <h1>سامانه رصد اطلاعاتی سحاب</h1>
                <p>میز کار مرکزی ثبت یافته‌های رصدی و اوسینت</p>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" id="open_search_btn" class="header-action-btn" title="جستجو در سیستم">🔍</button>
            <button type="button" id="theme_toggle_btn" class="header-action-btn" title="تغییر تم شب/روز">🌙</button>
            
            <?php if (is_user_logged_in()) : ?>
                <span class="status-badge active"><span class="pulse-dot"></span>تحلیل‌گر فعال</span>
            <?php else : ?>
                <span class="status-badge offline">🔒 مدار بسته</span>
            <?php endif; ?>
        </div>
    </header>

    <div class="layout">
        
        <main class="main-content">
            
            <div class="card form-card-container <?php echo $form_status_class; ?>">
                <?php if ( is_user_logged_in() ) : ?>
                    <div class="card-title">📝 ثبت گزارش رصدی جدید</div>
                    
                    <?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
                    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    
                    <form method="post" action="" class="research-form">
                        
                        <div class="form-row grid-3-1">
                            <div class="form-group">
                                <label class="field-label">عنوان گزارش / سناریو</label>
                                <input type="text" name="osint_title" class="form-control" placeholder="موضوع را وارد کنید..." required>
                            </div>
                            <div class="form-group">
                                <label class="field-label">حوزه رصد</label>
                                <select name="osint_cat" class="form-control font-bold">
                                    <option value="1">روحانیون شاخص</option>
                                    <option value="2">موسسات</option>
                                    <option value="3">روحانیون سیاسی</option>
                                    <option value="4">بین الملل</option>
                                    <option value="5">تحجر</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row grid-2-1">
                            <div class="form-group">
                                <label class="field-label">برچسب‌ها (با کاما جدا کنید)</label>
                                <input type="text" name="osint_tags" class="form-control" placeholder="مثال: پاکستان، نجف، اشخاص_کلیدی">
                            </div>
                            <div class="form-group">
                                <label class="field-label">وضعیت ذخیره‌سازی</label>
                                <select name="osint_status" class="form-control font-bold">
                                    <option value="publish">🚀 ثبت و انتشار قطعی</option>
                                    <option value="draft">📁 ذخیره به عنوان پیش‌نویس ناتمام</option>
                                </select>
                            </div>
                        </div>

                        <div class="media-uploader-box">
                            <label class="field-label font-black">ضمیمه مستندات تصویری یا ادله رصدی:</label>
                            <div class="media-actions">
                                <button type="button" id="manage_image_btn" class="btn btn-secondary">🖼️ انتخاب تصویر (آرشیو / آپلود لوکال)</button>
                                
                                <div id="image_preview_container" style="display:none;" class="preview-badge">
                                    <img id="uploaded_image_preview" src="" alt="Preview" class="uploaded_image_preview">
                                    <button type="button" id="remove_image_btn" class="btn-remove" title="حذف فایل">🗑️</button>
                                </div>
                                <input type="hidden" name="page_thumbnail_id" id="page_thumbnail_id" value="">
                            </div>
                        </div>

                        <div class="editor-container">
                            <?php wp_editor('', 'research_content', array('media_buttons' => false, 'textarea_rows' => 12, 'quicktags' => false)); ?>
                        </div>

                        <button type="submit" name="submit_osint" class="btn btn-success">ذخیره و ثبت نهایی گزارش در دیتابیس</button>
                    </form>

                <?php else : ?>
                    <div class="login-wrapper">
                        <div class="login-icon">🔒</div>
                        <div class="card-title text-center">ورود به سیستم امنیتی</div>
                        <?php if(isset($login_error)) echo "<div class='alert alert-danger'>$login_error</div>"; ?>
                        
                        <form method="post" action="" class="login-inside-form">
                            <div class="form-group">
                                <label class="field-label">شناسه کاربری</label>
                                <input type="text" name="log" class="form-control" placeholder="username" required>
                            </div>
                            <div class="form-group">
                                <label class="field-label">کلمه عبور</label>
                                <input type="password" name="pwd" class="form-control" placeholder="password" required>
                            </div>
                            <button type="submit" name="login_submit" class="btn btn-primary w-full">تایید هویت</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

        </main>

        <aside class="sidebar">
            <div class="card">
                <div class="card-title">📊 آخرین گزارش‌های ثبت شده</div>
                <ul class="post-list">
                    <?php
                    $recent_posts = new WP_Query(array('posts_per_page' => 6, 'post_status' => array('publish', 'draft')));
                    if ($recent_posts->have_posts()) :
                        while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                            <li class="post-item">
                                <div class="post-header">
                                    <a href="<?php the_permalink(); ?>" class="post-link"><?php the_title(); ?></a>
                                    <span class="chevron-arrow">⭪</span>
                                </div>
                                <div class="post-meta">
                                    <span class="meta-date">📅 <?php echo get_the_date(); ?></span>
                                    <div class="meta-badges">
                                        <?php if(get_post_status() == 'draft') : ?>
                                            <span class="badge-draft">ناتمام</span>
                                        <?php endif; ?>
                                        <span class="badge-cat"><?php echo get_the_category()[0]->name; ?></span>
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <li class="no-posts">هنوز هیچ گزارشی ثبت نشده است.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </aside>

    </div>

    <div id="search_modal" class="search-modal-overlay">
        <div class="search-modal-card card">
            <div class="modal-close-header">
                <h3>🔍 جستجوی پیشرفته در کل سامانه</h3>
                <button type="button" id="close_search_btn" class="close-modal-btn">❌</button>
            </div>
            <form role="search" method="get" class="search-box-modal" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" id="modal_search_input" class="form-control search-large-input" placeholder="عبارت مورد نظر، نام تارگت، هشتگ یا موضوع را تایپ کنید..." value="<?php echo get_search_query(); ?>" name="s" />
                <button type="submit" class="btn btn-primary btn-large">جستجو و کاوش داده</button>
            </form>
        </div>
    </div>

    <footer class="card technical-footer">
        <div class="tech-footer-content">
            <div class="tech-item">
                <span class="tech-label">پایگاه داده محلی:</span>
                <span class="tech-value status-online"><span class="dot"></span> متصل و پایدار (Local Engine)</span>
            </div>
            <div class="tech-item">
                <span class="tech-label">ایستگاه کاری:</span>
                <span class="tech-value font-mono">OSINT-System v6.5</span>
            </div>
            <div class="tech-item">
                <span class="tech-label">محیط سرور:</span>
                <span class="tech-value font-mono">Laragon Server</span>
            </div>
        </div>
    </footer>
</div>

<?php wp_footer(); ?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/custom-home.js"></script>
</body>
</html>