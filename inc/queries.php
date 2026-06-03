<?php
// ライバーアーカイブページの表示順をランダムにする
function jol_randomize_liver_archive_query($query) {
  // 管理画面やメインクエリでない場合は何もしない
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  // ライバーアーカイブページの場合のみランダム表示
  if (is_post_type_archive('liver')) {
    $query->set('orderby', 'rand');
  }
}
add_action('pre_get_posts', 'jol_randomize_liver_archive_query');
