<?php
/**
 * json template
 */
$output = array();
while (have_posts()) {
  the_post();

  switch (get_post_type()) {
    case "aside":
    case "status":
    case "quote":
    case "note":
      $post_type = "note";
      break;
    default:
      $post_type = "article";
      break;
  }

  $temp = array('published' => get_post_modified_time('Y-m-d\TH:i:s\Z', true),
                'verb' => 'post',
                'target' => array('id' => get_feed_link('json'),
                                  'url' => get_feed_link('json'),
                                  'objectType' => 'blog',
                                  'displayName' => get_bloginfo('name')
                                 ),
                'object' => array('id' => get_the_guid(),
                                  'displayName' => get_the_title(),
                                  'objectType' => $post_type,
                                  'summary' => get_the_excerpt(),
                                  'url' => get_permalink()
                                ),
                'actor' => array('id' => get_author_posts_url(get_the_author_meta('id'), get_the_author_meta('nicename')),
                                 'displayName' => get_the_author(),
                                 'objectType' => 'person',
                                 'url' => get_author_posts_url(get_the_author_meta('id'), get_the_author_meta('nicename')),
                                 'image' => array('width' => 80,
                                                  'height' => 80,
                                                  'url' => 'http://www.gravatar.com/avatar/'.md5( get_the_author_meta('email') ).'.jpg')
                                                  )
                                );

  if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
    $image = wp_get_attachment_image_src(get_post_thumbnail_id());
    $temp['object']['image']['url'] = $image[0];
    $temp['object']['image']['width'] = $image[1];
    $temp['object']['image']['height'] = $image[2];
  }

  $output['items'][] = $temp;
}

// add your own data
$output = apply_filters('activitystream_json', $output);

header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);

// check callback param
if ($callback = get_query_var('callback')) {
  echo $callback.'('.json_encode($output).');';
} else {
  echo json_encode($output);
}
?>