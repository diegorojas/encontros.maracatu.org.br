<?php

class SatelliteConfigHelper extends SatellitePlugin {

    function __construct() {

    }
    
    /**
     *
     * @param type $option ie 'slide' or 'gallery'
     * @param type $model ie $this -> Slide
     * @return array 
     */
    
    function displayOption($option, $model) {
        
        switch ($option) {
            
            case 'gallery':
                
                $optionsArray = array (
                array(  "name" => "The Gallery Editor",
                        "type" => "title"),

                array(  "type" => "open"),

                array(  "name"      => "Gallery Name",
                        "id"        => "title",
                        "type"      => "text",
                        "value"     => $model -> data -> title,
                        "std"       => "New Gallery"),

                array(  "name"      => "Gallery Type",
                        "desc"      => "What kind of slideshow is this?",
                        "id"        => "type",
                        "type"      => "select",
                        "value"     => $model -> data -> type,
                        "std"       => "custom slides",
                        "options"   => array(
                                           array('id' => 'custom slides', 'title' => 'Custom Slides'))),

                array(  "name"      => "Description",
                        "desc"      => "This will be used in future slideshow versions to describe the slideshow before someone selects to view it.",
                        "id"        => "description",
                        "value"     => $model -> data -> description,
                        "type"      => "textarea"),

                array(  "name"      => "Upload Images",
                        "desc"      => "Select multiple images using the uploader, then drag the thumbs to order them right here before saving the gallery",
                        "id"        => "slides",
                        "type"      => "upload"),

                array(  "name"      => "Caption Position",
                        "desc"      => "Where would you like to display the caption?",
                        "id"        => "capposition",
                        "value"     => $model -> data -> capposition,
                        "type"      => "select",
                        "std"       => "Overlayed",
                        "options"   => array(
                                        array('id' => 'Overlayed', 'title' => 'Overlayed'), 
                                        array('id' => 'On Right', 'title' => 'On Right'),
                                        array('id' => 'Disabled', 'title' => 'Disabled'))),
                    
                array(  "name"      => "Caption Animation",
                        "desc"      => "How will the animation transition occur?",
                        "id"        => "capanimation",
                        "value"     => $model -> data -> capanimation,
                        "type"      => "select",
                        "std"       => "slideOpen",
                        "options"   => array(
                                        array("id" => "fade", "title" => "Fade"),
                                        array("id" => "slideOpen", "title" => "Slide Open"),
                                        array("id" => "none", "title" => "None"))),

                array(  "name"      => "Clean Start",
                        "desc"      => "Caption and Navigation Arrows display on mouse hover",
                        "id"        => "caphover",
                        "type"      => "checkbox",
                        "value"     => $model -> data -> caphover),

                array(  "name"      => "Pause on Hover",
                        "desc"      => "Pause the advancement of the slideshow on hover? Only works with auto being on",
                        "id"        => "pausehover",
                        "type"      => "checkbox",
                        "value"     => $model -> data -> pausehover),

                array(  "type"      => "close")

                );	
                
                break;  
            
            case 'slide':
                
                $Gallery = new SatelliteGallery();
                
                $optionsArray = array(
                array(  "name"      => "Title",
                        "desc"      => "title/name of your slide as it will be displayed to your users.",
                        "id"        => "title",
                        "type"      => "text",
                        "value"     => $model -> data -> title,
                        "std"       => "New Slide"),
                array(  "name"      => "Description",
                        "desc"      => "description of your slide as it will be displayed in the caption.",
                        "id"        => "description",
                        "type"      => "textarea",
                        "value"     => $model -> data -> description,
                        "std"       => ""),
                array(  "name"      => "Gallery",
                        "desc"      => "The gallery this slide belongs to",
                        "id"        => "section",
                        "type"      => "select",
                        "value"     => $model -> data -> section,
                        "std"       => "Select a Gallery",
                        "options"   => $Gallery -> getGalleries()),
                array(  "name"      => "Caption Location",
                        "desc"      => "Default is the bottom caption bar",
                        "id"        => "textlocation",
                        "type"      => "select",
                        "value"     => $model -> data -> textlocation,
                        "std"       => "D",
                        "options"   => array(
                                array('id'=>'N', 'title'=>'None'),
                                array('id'=>'D', 'title'=>'Default'),
                                array('id'=>'BR', 'title'=>'Bottom Right'),
                                array('id'=>'TR', 'title'=>'Top Right')))

                );                
                break;
        }
        return $optionsArray;
    }
    /**
     *
     * @return string 
     */
    function getTransitionType() {
        
        if ($this->get_option('transition_temp') == "FB") {
            $transition = "fade-blend";
        } elseif ($this->get_option('transition_temp') == "FE") {
            $transition = "fade-empty";
        } elseif ($this->get_option('transition_temp') == "OHS") {
            $transition = "horizontal-slide";
        } elseif ($this->get_option('transition_temp') == "OVS") {
            $transition = "vertical-slide";
        } elseif ($this->get_option('transition_temp') == "OHP") {
            $transition = "horizontal-push"; }
        else {
            $transition = "fade-blend";
        }
        
        return $transition;
    }

}
?>
