
<?php
global $satellite_init_ok;
if (!empty($slides)) :

    $style = $this->get_option('styles');
    $imagesbox = $this->get_option('imagesbox');
    $textloc = $this->get_option('textlocation');
    $align = $this->get_option('align');
    if (!$frompost) {
        $this->Gallery->loadData($slides[0]->section);
        $sidetext = $this -> Gallery -> capLocation($this->Gallery->data->capposition,$slides[0]->section);
    }
    ?>


    <?php if ($frompost) : ?>

        <!-- =======================================
        THE ORBIT SLIDER CONTENT 
        ======================================= -->
        <div class="orbit-default
                <?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' default-thumbs' : ''; ?>
                <?php echo($align) ? ' satl-align-' . $align : ''; ?>
             ">
            <div id="featured<?php echo $satellite_init_ok; ?>"> 
                <?php foreach ($slides as $slider) : ?>  
                    <?php $full_image_href = wp_get_attachment_image_src($slider->ID, 'full', false); ?>
                    <?php $thumbnail_link = wp_get_attachment_image_src($slider->ID, 'thumbnail', false); ?>
                    <?php $attachment_link = get_attachment_link($slider->ID); ?>
                    <?php
                    if ($this->get_option('abscenter') == "Y") {
                        echo "<div class='sorbit-wide absoluteCenter' data-caption='#post-{$slider->ID}' data-thumb='{$thumbnail_link[0]}'>";
                    } else {
                        echo "<div class='sorbit-basic' data-caption='#post-{$slider->ID}' data-thumb='{$thumbnail_link[0]}'>";
                    }
                    ?>
                            <?PHP if ($this->get_option('wpattach') == 'Y') { ?>
                        <a href="<?php echo $attachment_link; ?>" rel="" title="<?php echo $slider->post_title; ?>">
            <?PHP } elseif ($imagesbox != "N" && $this->get_option('nolinker') != 'Y') { ?>
                            <a class="thickbox sorbit-link" href="<?php echo $full_image_href[0]; ?>" rel="" title="<?php echo $slider->post_title; ?>">
                            <?PHP } ?>
                            <img <?php echo ($this->get_option('abscenter') == "Y") ? "class='absoluteCenter'" : "" ?> src="<?php echo $full_image_href[0]; ?>" 
                                                                                                                     alt="<?php echo $slider->post_title; ?>" />
            <?PHP if ($imagesbox != "N" && $this->get_option('nolinker') != 'Y') { ?></a><?PHP } ?>
                </div>

                <span class="orbit-caption<?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' thumb-on' : ''; ?>" id="post-<?php echo $slider->ID; ?>">
                    <h5 class="orbit-title<?php echo($style['infotitle']) ?>"><?php echo $slider->post_title; ?></h5>
                    <p><?php echo $slider->post_content; ?></p>
                </span>
        <?php endforeach; ?>
        </div> <!-- end featured -->

        </div>
        <?php $this -> render('jsinit', array('gallery'=>false,'frompost' => true), true, 'orbit');?>

        <!--  CUSTOM GALLERY -->
    <?php else : ?>  
        <div class="orbit-default
        <?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' default-thumbs' : ''; ?>
                <?php echo($sidetext) ? ' text-' . $sidetext : ''; ?>
                <?php echo($align) ? ' satl-align-' . $align : ''; ?>
             ">
            <div id="featured<?php echo $satellite_init_ok; ?>"> 
                <?php $i = 0; ?>
                <?php foreach ($slides as $slider) : ?>     
                    <?php
                    if ($this->get_option('abscenter') == "Y") {
                        echo "<div id='satl-custom-{$this->Gallery->data->id}{$slider->id}' class='sorbit-wide absoluteCenter' 
                            data-caption='#custom{$satellite_init_ok}-$i'
                            data-thumb='{$this->Html->image_url($this->Html->thumbname($slider->image))}'>";
                    } else {
                        echo "<div id='satl-custom-{$this->Gallery->data->id}{$slider->id}' class='sorbit-basic' 
                            data-caption='#custom{$satellite_init_ok}-$i'
                            data-thumb='{$this->Html->image_url($this->Html->thumbname($slider->image))}'>";
                    }
                    ?>					
                            <?php if ($slider->uselink == "Y" && !empty($slider->link)) : ?>
                        <a href="<?php echo $slider->link; ?>" title="<?php echo $slider->title; ?>" target="<?php echo ($this->get_option('pagelink') == "S") ? "_self" : "_blank" ?>">
            <?PHP elseif ($imagesbox != "N" && $this->get_option('nolinker') != 'Y') : ?>
                            <a class="thickbox sorbit-link" href="<?php echo $this->Html->image_url($slider->image); ?>" rel="" title="<?php echo $slider->title; ?>">
            <?PHP endif; ?>

                            <img <?php echo ($this->get_option('abscenter') == "Y") ? "class='absoluteCenter'" : "" ?> 
                                src="<?php echo $this->Html->image_url($slider->image); ?>" 
                                alt="<?php echo $slider->title; ?>"                       
                                />

                <?PHP if ($imagesbox != "N" || $slider->uselink == "Y") : ?></a><?PHP endif; ?>
                </div>
            <?php
            if ($sidetext != ( "Disabled" )) :
                if ($slider->textlocation != "N") :
                    ?>
                        <span class="orbit-caption<?php echo ($slider->textlocation == 'BR' || $slider->textlocation == 'TR') ? ' sattext sattext' . $slider->textlocation : '' ?><?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' thumb-on' : ''; ?>" id='custom<?php echo ($satellite_init_ok . '-' . $i); ?>'>
                            <h5 class="orbit-title<?php echo($style['infotitle']) ?>"><?php echo $slider->title; ?></h5>
                            <p><?php echo $slider->description; ?> </p>
                    <?php if ($slider->uselink == "Y" && !empty($slider->link) && $slider->more) : ?>
                                <div class="more-img">
                                    <a href="<?php echo $slider->link; ?>" title="<?php echo $slider->title; ?>" target="<?php echo ($this->get_option('pagelink') == "S") ? "_self" : "_blank" ?>">
                                        <img src="<?php echo $this->Html->image_id($slider->more); ?>" />
                                    </a>
                                </div>
                        <?php endif; ?>
                        </span>   
                    <?php else : ?>
                        <span class="sattext-none" id='custom<?php echo ($satellite_init_ok . '-' . $i); ?>'>
                        </span>
                <?php
                endif;
            endif;
            $i = $i + 1;
        endforeach;
        ?>
        </div>

        </div>
        <?php $this -> render('jsinit', array('gallery'=>$slides[0]->section,'frompost' => false), true, 'orbit');?>
    <?php
    endif;
    /*     * ****** PRO ONLY ************* */
    if (SATL_PRO && $this->get_option('keyboard') == 'Y') {
        require SATL_PLUGIN_DIR . '/pro/keyboard.html';
    }

endif;
?>