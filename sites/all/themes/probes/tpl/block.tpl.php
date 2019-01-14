<?php

$out = '';

if($block->region == 'section' || $block->region == 'page_section'){
	$out .= '<div id="'.$block_html_id.'" class="section-lg '.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h2>'.$block->subject.'</h2>';
	endif;
	$out .= $content;
	$out .= '</div>';

} elseif($block->region == 'section_one') {
	if(strpos($attributes, 'data-title')){
		$out .= '<section id="'.$block_html_id.'" class="'.$classes.'" '.$attributes.'>';
		$out .= '<div class="section-lg" >';
	} else $out .= '<div id="'.$block_html_id.'" class="section-lg '.$classes.'" '.$attributes.'>';
	
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h2>'.$block->subject.'</h2>';
	endif;
	$out .= $content;
	if(strpos($attributes, 'data-title')){
		$out .= '</div></section>';
	} else $out .= '</div>';

} else if($block->region == 'home_header'){

	$out .= '<header id="'.$block_html_id.'" class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	$out .= $content;
	$out .= '</header>';

} else if($block->region == 'slider'){

	$out .= '<section id="'.$block_html_id.'" class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	$out .= $content;
	$out .= '</section>';

} else if($block->region == 'right_header5'){

	$out .= '<div id="'.$block_html_id.'" class="col-md-6 '.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h2>'.$block->subject.'</h2>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'shopping_cart'){

	$out .= '<div id="'.$block_html_id.'" class="shopping-cart hide '.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h2>'.$block->subject.'</h2>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'main_menu'){

	$out .= '<nav class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h2>'.$block->subject.'</h2>';
	endif;
	$out .= $content;
	$out .= '</nav>';

} else if($block->region == 'shop_sidebar'){

	$out .= '<div class="widget '.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h4 class="widget-title">'.$block->subject.'</h4>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'sidebar'){

	$out .= '<div id="'.$block_html_id.'" class="widget '.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<div class="cat-title white font-bold uppercase">'.$block->title.'</div>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'footer'){
	$out .= '<div class="col-md-3 col-sm-12 m-bottom3 '.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h4 class="font20 font-thin">'.$block->subject.'</h4>';
		$out .= '<div class="title-line"></div>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'footer2'){
	$out .= '<div class="col-md-4 '.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h4 class="font20 font-thin">'.$block->subject.'</h4>';
		$out .= '<div class="title-line"></div>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'footer3') {
	$out .= '<div class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h4 class="white font25 m-bottom3">'.$block->subject.'</h4>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'footer4'){
	$out .= '<div class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h4 class="font20 font-thin">'.$block->subject.'</h4>';
		$out .= '<div class="title-line color"></div>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'footer5') {
	$out .= '<div class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h2 class="white font25 m-bottom3">'.$block->subject.'</h2>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else if($block->region == 'footer_copyright'){
	$out .= '<div class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	if ($block->subject):
		$out .= '<h2 class="white font20">'.$block->subject.'</h2>';
	endif;
	$out .= $content;
	$out .= '</div>';

} else {
	$out .= '<div id="'.$block_html_id.'" class="'.$classes.'" '.$attributes.'>';
	$out .= render($title_suffix);
	 if ($block->subject)
		$out .= '<h3>'.$block->subject.'</h3>';
	$out .= $content;
	$out .= '</div>';
}
	print $out;
?>