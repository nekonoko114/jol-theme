<?php
// カスタムメニューの追加
function jol_menu_init()
{
  register_nav_menus(
    array(
      'header' => 'ヘッダーメニュー',
      'drawer' => 'ドロワーメニュー',
      'footer' => 'フッターメニュー'
    )
  );
}
add_action('init', 'jol_menu_init');

// カスタム投稿タイプのアーカイブリンクをメニューに追加するための設定
function jol_add_custom_post_types_to_nav_menus()
{
  add_post_type_support('liver', 'nav-menus');
  add_post_type_support('interview', 'nav-menus');
}
add_action('init', 'jol_add_custom_post_types_to_nav_menus');

// メニュー管理画面にカスタム投稿タイプのアーカイブを強制的に追加
function jol_add_cpt_archives_to_menus()
{
  global $wp_post_types;
  if (isset($wp_post_types['liver'])) {
    $wp_post_types['liver']->show_in_nav_menus = true;
  }
  if (isset($wp_post_types['interview'])) {
    $wp_post_types['interview']->show_in_nav_menus = true;
  }
}
add_action('init', 'jol_add_cpt_archives_to_menus', 20);

// メニュー編集画面にカスタム投稿タイプを強制的に表示する追加方法
function jol_custom_post_type_nav_menu_metabox()
{
  add_meta_box(
    'liver-nav-menu',
    'ライバー',
    'jol_liver_nav_menu_metabox_content',
    'nav-menus',
    'side',
    'default'
  );

  add_meta_box(
    'interview-nav-menu',
    'インタビュー',
    'jol_interview_nav_menu_metabox_content',
    'nav-menus',
    'side',
    'default'
  );
}
add_action('admin_head-nav-menus.php', 'jol_custom_post_type_nav_menu_metabox');

function jol_liver_nav_menu_metabox_content()
{
  $posts = get_posts(array(
    'post_type' => 'liver',
    'numberposts' => 10,
    'post_status' => 'publish'
  ));
?>
  <div id="posttype-liver" class="posttypediv">
    <ul id="liver-checklist" class="categorychecklist form-no-clear">
      <li>
        <label class="menu-item-title">
          <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1">
          ライバー一覧（アーカイブ）
        </label>
        <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
        <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="ライバー一覧">
        <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="<?php echo get_post_type_archive_link('liver'); ?>">
      </li>
      <?php foreach ($posts as $post): ?>
        <li>
          <label class="menu-item-title">
            <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $post->ID; ?>][menu-item-object-id]" value="<?php echo $post->ID; ?>">
            <?php echo esc_html($post->post_title); ?>
          </label>
          <input type="hidden" class="menu-item-type" name="menu-item[<?php echo $post->ID; ?>][menu-item-type]" value="post_type">
          <input type="hidden" class="menu-item-object" name="menu-item[<?php echo $post->ID; ?>][menu-item-object]" value="liver">
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit" class="button-secondary submit-add-to-menu right" value="メニューに追加" name="add-post-type-menu-item" id="submit-posttype-liver">
      </span>
    </p>
  </div>
<?php
}

function jol_interview_nav_menu_metabox_content()
{
  $posts = get_posts(array(
    'post_type' => 'interview',
    'numberposts' => 10,
    'post_status' => 'publish'
  ));
?>
  <div id="posttype-interview" class="posttypediv">
    <ul id="interview-checklist" class="categorychecklist form-no-clear">
      <li>
        <label class="menu-item-title">
          <input type="checkbox" class="menu-item-checkbox" name="menu-item[-2][menu-item-object-id]" value="-2">
          インタビュー一覧（アーカイブ）
        </label>
        <input type="hidden" class="menu-item-type" name="menu-item[-2][menu-item-type]" value="custom">
        <input type="hidden" class="menu-item-title" name="menu-item[-2][menu-item-title]" value="インタビュー一覧">
        <input type="hidden" class="menu-item-url" name="menu-item[-2][menu-item-url]" value="<?php echo get_post_type_archive_link('interview'); ?>">
      </li>
      <?php foreach ($posts as $post): ?>
        <li>
          <label class="menu-item-title">
            <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $post->ID; ?>][menu-item-object-id]" value="<?php echo $post->ID; ?>">
            <?php echo esc_html($post->post_title); ?>
          </label>
          <input type="hidden" class="menu-item-type" name="menu-item[<?php echo $post->ID; ?>][menu-item-type]" value="post_type">
          <input type="hidden" class="menu-item-object" name="menu-item[<?php echo $post->ID; ?>][menu-item-object]" value="interview">
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="button-controls">
      <span class="add-to-menu">
        <input type="submit" class="button-secondary submit-add-to-menu right" value="メニューに追加" name="add-post-type-menu-item" id="submit-posttype-interview">
      </span>
    </p>
  </div>
<?php
}

// パーマリンクとメニューの設定を強制更新
function jol_force_cpt_menu_update()
{
  flush_rewrite_rules();
  if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
  }
}
add_action('admin_init', 'jol_force_cpt_menu_update');

// 投稿のデフォルトスタイルを変更する (li クラスの追加)
function jol_nav_menu_add_li_class($classes, $item, $args, $depth)
{
  if (isset($args->add_li_class)) {
    $classes[] = $args->add_li_class;
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'jol_nav_menu_add_li_class', 10, 4);

// カスタム投稿タイプページでメニューにアクティブクラスを追加
function jol_add_active_class_to_custom_post_type_menu($classes, $item, $args)
{
  if ($args->theme_location !== 'header') {
    return $classes;
  }
  if (is_singular('liver') || is_post_type_archive('liver')) {
    if ($item->url === get_post_type_archive_link('liver')) {
      $classes[] = 'current-menu-item';
    }
  }
  if (is_singular('interview') || is_post_type_archive('interview')) {
    if ($item->url === get_post_type_archive_link('interview')) {
      $classes[] = 'current-menu-item';
    }
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'jol_add_active_class_to_custom_post_type_menu', 10, 3);

// メニュー項目のアクティブ状態をより詳細に制御
function jol_custom_nav_menu_active_class($classes, $item, $args)
{
  if ($args->theme_location !== 'header') {
    return $classes;
  }
  global $post;
  $current_url = home_url($_SERVER['REQUEST_URI']);
  $menu_url = $item->url;
  if ($current_url === $menu_url) {
    $classes[] = 'current-menu-item';
  }
  if (is_single()) {
    $post_type = get_post_type();
    if ($post_type === 'liver' && $menu_url === get_post_type_archive_link('liver')) {
      $classes[] = 'current-post-type';
    }
    if ($post_type === 'interview' && $menu_url === get_post_type_archive_link('interview')) {
      $classes[] = 'current-post-type';
    }
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'jol_custom_nav_menu_active_class', 20, 3);
