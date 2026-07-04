<?php
/*
Template Name: Home Custom Page v5
*/

// ۱. بررسی دسترسی بارگذاری رسانه‌های وردپرس در فرانت‌اند
if (is_user_logged_in()) {
    add_action('wp_enqueue_scripts', function() {
        wp_enqueue_media(); // فراخوانی کتابخانه رسانه وردپرس برای پاپ‌آپ تصاویر
    });
}

// ۲. پردازش فرم ورود اطلاعات پژوهشی به همراه تصویر شاخص و برچسب‌ها
if (isset($_POST['submit_research']) && is_user_logged_in()) {
    if (!empty($_POST['research_title']) && !empty($_POST['research_content'])) {
        
        // ساخت آرایه اصلی نوشته
        $new_post = array(
            'post_title'    => sanitize_text_field($_POST['research_title']),
            'post_content'  => wp_kses_post($_POST['research_content']),
            'post_status'   => sanitize_text_field($_POST['research_status']), // انتشار یا پیش‌نویس
            'post_type'     => 'post',
            'post_category' => array(intval($_POST['research_cat'])),
            'tags_input'    => sanitize_text_field($_POST['research_tags']) // ثبت برچسب‌ها
        );

        // درج نوشته در دیتابیس
        $post_id = wp_insert_post($new_post);

        if ($post_id && !is_wp_error($post_id)) {
            // اتصال تصویر شاخص انتخاب شده به نوشته
            $thumbnail_id = intval($_POST['page_thumbnail_id']);
            if ($thumbnail_id > 0) {
                set_post_thumbnail($post_id, $thumbnail_id);
            }
            $message = "اطلاعات پژوهشی با موفقیت در بانک داده پایدار شد.";
        } else {
            $error = "خطا در ثبت اطلاعات در دیتابیس.";
        }
    } else {
        $error = "لطفاً عنوان و متن اصلی را وارد کنید.";
    }
}

// ۳. پردازش فرم لاگین اختصاصی درون‌برنامه‌ای
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
    <title>سامانه اطلاعاتی سحاب</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?> 
</head>
<body>

<div class="layout">
    
    <div class="main-content">
        
        <div class="card app-header">
            <h1>بانک اطلاعات پژوهشی سحاب</h1>
            <?php if (is_user_logged_in()) : ?>
                <span class="status-badge">حساب پژوهشگر فعال</span>
            <?php else : ?>
                <span class="status-badge offline">حالت نمایش عمومی</span>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-title">🔍 کاوش و سرچ هوشمند مستندات</div>
            <form role="search" method="get" class="search-box" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" class="form-control" placeholder="نام شخص، موسسه، موضوع یا کلمه کلیدی..." value="<?php echo get_search_query(); ?>" name="s" />
                <button type="submit" class="btn">جستجو</button>
            </form>
        </div>

        <div class="card">
            <?php if ( is_user_logged_in() ) : ?>
                <div class="card-title">📝 ثبت فیش پژوهشی جدید</div>
                
                <?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                
                <form method="post" action="" class="research-form">
                    
                    <div class="form-grid">
                        <input type="text" name="research_title" class="form-control" placeholder="عنوان فیش یا موضوع سناریو..." required>
                        <select name="research_cat" class="form-control">
                            <option value="1">روحانیون شاخص</option>
                            <option value="2">موسسات</option>
                            <option value="3">روحانیون سیاسی</option>
                            <option value="4">بین الملل</option>
                            <option value="5">تحجر</option>
                        </select>
                    </div>

                    <div class="form-grid" style="grid-template-columns: 2fr 1fr; margin-top: 10px;">
                        <input type="text" name="research_tags" class="form-control" placeholder="برچسب‌ها (با کاما یا ویرگول جدا کنید: قم, نجف, زید)">
                        <select name="research_status" class="form-control">
                            <option value="publish">انتشار مستقیم</option>
                            <option value="draft">ذخیره به عنوان پیش‌نویس</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <label style="display:block; margin-bottom:10px; font-weight:700; font-size:13px; color:#475569;">تصویر یا سند شاخص:</label>
                        <div style="display:flex; gap:15px; align-items:center;">
                            <button type="button" id="manage_image_btn" class="btn" style="background:#64748b; padding:10px 20px;">مدیریت و انتخاب تصویر (آرشیو / آپلود)</button>
                            <div id="image_preview_container" style="display:none; align-items:center; gap:10px;">
                                <img id="uploaded_image_preview" src="" style="max-height:50px; border-radius:6px; border:1px solid #cbd5e1;">
                                <button type="button" id="remove_image_btn" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:12px; font-weight:700;">حذف تصویر</button>
                            </div>
                            <input type="hidden" name="page_thumbnail_id" id="page_thumbnail_id" value="">
                        </div>
                    </div>

                    <div class="editor-container">
                        <?php wp_editor('', 'research_content', array('media_buttons' => false, 'textarea_rows' => 12, 'quicktags' => false)); ?>
                    </div>

                    <button type="submit" name="submit_research" class="btn btn-success">ثبت و پایدارسازی داده</button>
                </form>

            <?php else : ?>
                <div class="card-title">🔒 قفل امنیتی دیتابیس / ورود به سیستم</div>
                <?php if(isset($login_error)) echo "<div class='alert alert-danger'>$login_error</div>"; ?>
                
                <form method="post" action="" class="login-inside-form">
                    <input type="text" name="log" class="form-control" placeholder="نام کاربری" required>
                    <input type="password" name="pwd" class="form-control" placeholder="کلمه عبور" required>
                    <button type="submit" name="login_submit" class="btn">تایید هویت</button>
                </form>
            <?php endif; ?>
        </div>

    </div>

    <div class="sidebar">
        <div class="card">
            <div class="card-title">📊 آخرین اسناد پایدار شده</div>
            <ul class="post-list">
                <?php
                $recent_posts = new WP_Query(array('posts_per_page' => 6, 'post_status' => array('publish', 'draft')));
                if ($recent_posts->have_posts()) :
                    while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                        <li class="post-item">
                            <a href="<?php the_permalink(); ?>" class="post-link"><?php the_title(); ?></a>
                            <div class="post-meta">
                                <span><?php echo get_the_date(); ?></span>
                                <span class="cat-label">
                                    <?php 
                                    echo get_the_category()[0]->name; 
                                    if(get_post_status() == 'draft') echo ' <span style="color:#ef4444;">(پیش‌نویس)</span>';
                                    ?>
                                </span>
                            </div>
                        </li>
                    <?php endwhile; wp_reset_postdata();
                else : ?>
                    <li class="post-item" style="color: #64748b; text-align: center;">هنوز سندی ثبت نشده است.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

</div>

<?php wp_footer(); ?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/custom-home.js"></script>
</body>
</html>