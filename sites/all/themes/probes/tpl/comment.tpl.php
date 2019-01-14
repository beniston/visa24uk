<!-- Comment Item -->
<?php
	$user = user_load($comment->uid);
	$display_name = field_get_items('user', $user, 'field_display_name');
    if($user->picture) {
        $pic_uri = $user->picture->uri;
    } else $pic_uri = '';   
    if(!empty($pic_uri)) {
        $pic = image_style_url('image_74x74', $pic_uri);
    } elseif(variable_get('user_picture_default', '')) {
        $pic = variable_get('user_picture_default', '');
        $pic = image_style_url('image_74x74', $pic);
    } 
?>
<div class="comment-list">
    <div class="media">
        <div class="media-left">
            <a href="#"><img src="<?php print $pic; ?>" alt="<?php if(!empty($display_name)) {print $display_name[0]['value']; } else print $name;?>" class="img-circle"> </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading"> <a class="c-font-bold" href="#"><?php if(!empty($display_name)) {print $display_name[0]['value']; } else print theme('username', array('account' => $content['comment_body']['#object'], 'attributes' => array('class' => 'url'))); ?></a> <span class="small"><?php print t('on'); ?> <?php print format_date($comment->created, 'custom', 'd M Y, g:iA') ?></span> </h4>
            <?php $a = str_replace('<a', '<a class="reply"', strip_tags(render($content['links']),'<a>')) ?>
            <?php print  str_replace('reply</a>', '<i class="fa fa-reply"></i> <span class="txt-over">Reply</span></a>', $a); ?>
            <?php hide($content['links']); print render($content); ?>
        </div>
    </div>
</div>
