<?php
global $post, $post_ID;
$post_ID = 1;
wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);

//$slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($single)), null, array('order', "ASC"));

$shortname = "satl";
$ptypes1 = get_post_types(array('public' => true),'names','and');
$ptypes = array_push($ptypes1, 'resume');

$slideOptions = $this -> Config -> displayOption('slide', $this -> Slide);

$useLinkOptions = array (
array(  "name"      => "Link To",
        "desc"      => "link/URL to go to when a user clicks the slide eg. http://www.domain.com/mypage/",
        "id"        => "link",
        "type"      => "text",
        "value"     => $this -> Slide -> data -> link,
        "std"       => "http://"),
array(  "name"      => "More Image",
        "desc"      => "From here you can select an image if you have a Gallery with the title 'More'",
        "id"        => "more",
        "type"      => "select",
        "value"     => $this -> Slide -> data -> more,    
        "std"       => "Select an Image",
        "options"   => $this -> Slide -> getAllMoreImages())
);	
?>  <div class="wrap">
	<h2><?php _e('Save a Slide', SATL_PLUGIN_NAME); ?></h2>
	
	<form action="<?php echo $this -> url; ?>&amp;method=save" method="post" enctype="multipart/form-data">
		<input type="hidden" name="Slide[id]" value="<?php echo $this -> Slide -> data -> id; ?>" />
		<input type="hidden" name="Slide[slide_order]" value="<?php echo $this -> Slide -> data -> slide_order; ?>" />
		<table class="form-table">
                    <tbody>
                        <?php $this -> Form -> display($slideOptions, 'Slide'); ?>
                        
                <tr>
                	<th><label for="Slide.type.file"><?php _e('Image Type', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                    	<label><input onclick="jQuery('#typediv_file').show(); jQuery('#typediv_url').hide();" <?php echo (empty($this -> Slide -> data -> type) || $this -> Slide -> data -> type == "file") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[type]" value="file" id="Slide.type.file" /> <?php _e('Upload File (recommended)', SATL_PLUGIN_NAME); ?></label>
                        <label><input onclick="jQuery('#typediv_url').show(); jQuery('#typediv_file').hide();" <?php echo ($this -> Slide -> data -> type == "url") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[type]" value="url" id="Slide.type.url" /> <?php _e('Specify URL', SATL_PLUGIN_NAME); ?></label>
                        <?php echo (!empty($this -> Slide -> errors['type'])) ? '<div style="color:red;">' . $this -> Slide -> errors['type'] . '</div>' : ''; ?>
                        <span class="howto"><?php _e('do you want to upload an image or specify a local/remote image URL?', SATL_PLUGIN_NAME); ?></span>
                    </td>
                </tr>
				
            </tbody>
        </table>
        
        <div id="typediv_file" style="display:<?php echo (empty($this -> Slide -> data -> type) || $this -> Slide -> data -> type == "file") ? 'block' : 'none'; ?>;">
        	<table class="form-table">
            	<tbody>
                	<tr>
                    	<th><label for="Slide.image_file"><?php _e('Choose Image', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                        	<input type="file" name="image_file" value="" id="Slide.image_file" />
                            <span class="howto"><?php _e('choose your image file from your computer. JPG, PNG, GIF, SWF are supported.', SATL_PLUGIN_NAME); ?></span>
                            <?php echo (!empty($this -> Slide -> errors['image_file'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_file'] . '</div>' : ''; ?>

                                <input type="hidden" name="Slide[image_oldfile]" value="<?php echo esc_attr(stripslashes($this -> Slide -> data -> image)); ?>" />

                                <?php 
                                if (!empty($this -> Slide -> data -> image)) {
                                    $image = $this -> Slide -> data -> image;
                                    
                                    ?>                                    
                                    <p><small><?php _e('Current thumbnail. Leave the field above blank to keep this image.', SATL_PLUGIN_NAME); ?></small></p>
                                   	<a href="<?php echo $this -> Html -> image_url($image); ?>" class="thickbox">
                                                <img src="<?php echo $this -> Html -> image_url($this -> Html -> thumbname($image, "thumb")); ?>" />
                                                        <br />
                                                        <?php	echo ("Filename: " . $this -> Slide -> data -> image); ?>
                                                <br />
                                        </a>
                                    <?php	
                                 }
                            //}
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div id="typediv_url" style="display:<?php echo ($this -> Slide -> data -> type == "url") ? 'block' : 'none'; ?>;">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="Slide.image_url"><?php _e('Image URL', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                            <input class="widefat" type="text" name="Slide[image_url]" value="<?php echo esc_attr($this -> Slide -> data -> image_url); ?>" id="Slide.image_url" />
                            <span class="howto"><?php _e('Local or remote image location eg. http://domain.com/path/to/image.jpg', SATL_PLUGIN_NAME); ?></span>
                            <?php echo (!empty($this -> Slide -> errors['image_url'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_url'] . '</div>' : ''; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
                
        <table class="form-table">
        <tbody>
                    <tr>
                        <th><label for="Slide_userlink_N"><?php _e('Use Link', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                                <label><input onclick="jQuery('#Slide_uselink_div').show();" <?php echo ($this -> Slide -> data -> uselink == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="Y" id="Slide_uselink_Y" /> <?php _e('Yes', SATL_PLUGIN_NAME); ?></label>
                                <label><input onclick="jQuery('#Slide_uselink_div').hide();" <?php echo (empty($this -> Slide -> data -> uselink) || $this -> Slide -> data -> uselink == "N") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="N" id="Slide_uselink_N" /> <?php _e('No', SATL_PLUGIN_NAME); ?></label>
            <span class="howto"><?php _e('set this to Yes to link this slide to a link/URL of your choice.', SATL_PLUGIN_NAME); ?></span>
                        </td>
                    </tr>
                </tbody>
        </table>
		
        <div id="Slide_uselink_div" style="display:<?php echo ($this -> Slide -> data -> uselink == "Y") ? 'block' : 'none'; ?>;">
            <table class="form-table">
                    <tbody>
                <?php $this -> Form -> display($useLinkOptions, 'Slide'); ?>
                    </tbody>
            </table>
        </div>
		
        <p class="submit">
                <input class="button-primary" type="submit" name="submit" value="<?php _e('Save Slide', SATL_PLUGIN_NAME); ?>" />
        </p>
	</form>
</div>