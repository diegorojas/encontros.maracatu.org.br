<?php
    global $satellite_init_ok;
    $style = $this->get_option('styles');
    if (!$frompost) {
        $this->Gallery->loadData($gallery);
    }    
    //if ($this->get_option('autoslide') == "Y") {
        $autospeed = $this->get_option('autospeed');
        $autospeed2 = $this->get_option('autospeed2');
    if ($this->get_option('othumbs') != 'B') { // if thumbs on bullcenter = false
        $this->update_option('bullcenter', 'false');
    }    
    $navOpacity = ($style['nav_opacity']) ? $style['nav_opacity'] : 30;
    $navOpacity = $navOpacity / 100;
    $thumbwidth = (int) $style['thumbheight'] + $style['thumbspacing'] + $style['thumbspacing'];
    $transition = $this->Config->getTransitionType();
    if ($fullthumb) { $bullets = true; }
    elseif ($this->get_option('thumbnails_temp') == "Y") { $bullets = true; }
    else { $bullets = false; }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#featured<?php echo $satellite_init_ok; ?>').orbit({
                animation: '<?PHP echo ($transition) ? $transition : $this->get_option('transition'); ?>',                  // fade, horizontal-slide, vertical-slide, horizontal-push
                animationSpeed: <?php echo($this->get_option('duration')); ?>,                // how fast animations are
                timer: <?PHP echo ($this->get_option("autoslide_temp") == "Y" ) ? 'true' : 'false'; ?>, 	 // true or false to have the timer
                advanceSpeed: <?PHP echo ($autospeed2); ?>, 		 // if timer is enabled, time between transitions 
                pauseOnHover: <?php echo ($this->Gallery->data->pausehover) ? 'true' : 'false'; ?>, 		 // if you hover pauses the slider
                startClockOnMouseOut: <?php echo ($this->Gallery->data->pausehover) ? 'true' : 'false'; ?>, 	 // if clock should start on MouseOut
                startClockOnMouseOutAfter: 1000, 	 // how long after MouseOut should the timer start again
                directionalNav: true, 		 // manual advancing directional navs
                captions: <?php echo($this->get_option('information_temp') == 'Y') ? 'true' : 'false'; ?>,	 // do you want captions?
                captionAnimation: <?php echo ($this->Gallery->data->capanimation) ? '\'' . $this->Gallery->data->capanimation . '\'' : '\'slideOpen\''; ?>, // fade, slideOpen, none
                captionHover: <?php echo ($this->Gallery->data->caphover) ? 'true' : 'false'; ?>, // true means only show caption on mousehover
                captionAnimationSpeed: 800, 	 // if so how quickly should they animate in
                bullets: <?php echo($bullets) ? 'true' : 'false'; ?>,	// true or false to activate the bullet navigation
                bulletThumbs: true,		 // thumbnails for the bullets
                bulletThumbLocation: '',	 // location from this file where thumbs will be
                afterSlideChange: function(){},    // empty function 
                centerBullets: <?php echo $this->get_option('bullcenter'); ?>,
                navOpacity: <?php echo ($navOpacity); ?>,
                thumbWidth: <?php echo $thumbwidth; ?>,
                alwaysPlayBtn: <?php echo ($style['playshow'] == "A") ? 'true' : 'false'; ?>
            });				
        });
    </script> 
