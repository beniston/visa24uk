<?php
/**
 * @file
 * Default theme implementation to display a node.
 */
	global $base_root, $base_url;

  	if(isset($content['product:field_images'])) {
    	$ni = count($content['product:field_images']['#items']);
    	$img_uri  = $content['product:field_images']['#items'][0]['uri'];
	    $full_img = file_create_url($img_uri);
	    $img_crop = image_style_url('image_270x330', $img_uri);
  	} else{
  		$imageone = '';
  		$ni = 0;
  	}

    if(!$page){ ?>
    <div class="col-md-3 col-sm-6 m-bottom4">
        <div class="product clearfix">
            <div class="pro-imgage">
                <div class="product-overlay list-products">
                <?php
                    hide($content['links']);
                    hide($content['comments']);
                    hide($content['field_additional_information']);
                    hide($content['product:field_images']);
                    hide($content['product:commerce_price']);
                    hide($content['field_radioactivity']);
                    hide($content['field_featured']);
                    hide($content['product:sku']);
                    hide($content['field_product_categories']);
                    hide($content['body']);
                    print render($content);
                ?> <a class="add-to-cart" href="<?php print $node_url; ?>"><i class="fa fa-search-plus"></i><span> <?php print t('Quick View'); ?></span></a> </div>
                <div class="imgbox">
                    <a href="<?php print $node_url; ?>"><img alt="<?php print $title; ?>" src="<?php print $img_crop;?>"></a>
                </div>
            </div>
            <div class="product-desc text-center">
                <div class="product-title"> 
                    <h3><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h3>
                </div>
                <div class="product-price blue font-bold font25 m-bottom1"><?php print  render($content['product:commerce_price']); ?></div>
                <div class="product-rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-full"></i> </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="row">
        <div class="col-md-6">
            <div id="js-grid-slider-thumbnail" class="cbp">
            <?php foreach ($content['product:field_images']['#items'] as $key => $value) {
                $img_uri  = $content['product:field_images']['#items'][$key]['uri'];
                $full_img = image_style_url('image_700x600', $img_uri);
            ?>
                <div class="cbp-item">
                    <div class="cbp-caption">
                        <div class="cbp-caption-defaultWrap">
                            <img alt="<?php print $content['product:field_images']['#items'][$key]['alt']; ?>" src="<?php print $full_img; ?>">
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
            <div id="js-pagination-slider">
            <?php foreach ($content['product:field_images']['#items'] as $key => $value) {
                $img_uri  = $content['product:field_images']['#items'][$key]['uri'];
                $crop_img = image_style_url('image_100x86', $img_uri);
            ?>
                <div class="cbp-pagination-item <?php if($key == 0) print 'cbp-pagination-active'; ?>">
                    <img alt="<?php print $title; ?>" src="<?php print $crop_img; ?>">
                </div>
            <?php } ?>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="font-black font30 uppercase m-bottom1"><?php print $title; ?></h2>
            <div class="title-line wide50 color m-top2"></div>
            <div class="product-rating m-bottom2"> <?php print rate_embed($node, 'product_rating', RATE_COMPACT); ?> | <a href="#">Write a review</a> </div>
            <div class="product-price red font-bold font25 m-bottom1"><?php print  render($content['product:commerce_price']); ?></div>
            <p><?php print $node->body['und'][0]['summary']; ?></p>
            <div class="m-bottom3">
                <?php
                  hide($content['links']);
                  hide($content['comments']);
                  hide($content['field_additional_information']);
                  hide($content['product:field_images']);
                  hide($content['product:commerce_price']);
                  hide($content['field_radioactivity']);
                  hide($content['field_featured']);
                  hide($content['product:sku']);
                  hide($content['field_product_categories']);
                  hide($content['body']);
                  print render($content);
                ?>
            </div>
        </div>
    </div>
    <div class="m-top6">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tabs-style2" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><?php print t('Description'); ?></a></li>
            <li role="presentation" class=""><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><?php print t('Additional Information'); ?></a></li>
            <li role="presentation" class=""><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><?php print t('Reviews'); ?> (<?php print $comment_count; ?>)</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content tab-style3">
            <div role="tabpanel" class="tab-pane active" id="home">
                <?php
                    hide($content['links']);
                    hide($content['comments']);
                    hide($content['field_additional_information']);
                    hide($content['product:field_images']);
                    hide($content['product:commerce_price']);
                    hide($content['field_radioactivity']);
                    hide($content['field_featured']);
                    hide($content['product:sku']);
                    hide($content['field_product_categories']);
                    print render($content['body']);
                ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
                <?php print render($content['field_additional_information']); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="messages">
                <?php print render($content['comments']); ?>
            </div>
        </div>
    </div>
    <?php } ?>