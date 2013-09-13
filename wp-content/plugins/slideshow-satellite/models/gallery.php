<?php
class SatelliteGallery extends SatelliteDbHelper {
	var $table;
	var $model = 'Gallery';
	var $controller = "galleries";
	var $plugin_name = SATL_PLUGIN_NAME;
	var $data = array();
	var $errors = array();
	var $fields = array(
		'id'			=>	"INT(11) NOT NULL AUTO_INCREMENT",
		'title'			=>	"VARCHAR(150) CHARACTER SET utf8 NOT NULL DEFAULT ''",
		'description'		=>	"TEXT CHARACTER SET utf8",
		'image'			=>	"VARCHAR(75) NOT NULL DEFAULT ''",
		'type'			=>	"VARCHAR(40) NOT NULL DEFAULT ''",
                'capposition'           =>      "VARCHAR(40) NOT NULL DEFAULT ''",
                'caphover'              =>      "BOOLEAN NOT NULL DEFAULT 0",
                'pausehover'            =>      "BOOLEAN NOT NULL DEFAULT 0",
                'capanimation'          =>      "VARCHAR(40) NOT NULL DEFAULT ''",
		'gal_order'		=>	"INT(11) NOT NULL DEFAULT '0'",
		'created'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'modified'		=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
		'key'			=>	"PRIMARY KEY  (`id`)",
	);
	function SatelliteGallery($data = array()) {
		global $wpdb;
                
		//$this -> table = $wpdb -> prefix . strtolower($this -> pre) . "_" . $this -> controller;
                $this -> table = $wpdb -> prefix . "satl_" . $this -> controller;
		$this -> check_table($this -> model);
		if (!empty($data)) {
			foreach ($data as $dkey => $dval) {
				$this -> {$dkey} = $dval;
			}
		}
	
		return true;
	}
	function defaults() {
		$defaults = array(
                    'gal_order'		=>	0,
                    'created'		=>	SatelliteHtmlHelper::gen_date(),
                    'modified'          =>	SatelliteHtmlHelper::gen_date(),
		);
		
		return $defaults;
	}
	function validate($data = null) {
		$this -> errors = array();
	
		if (!empty($data)) {
			$data = (empty($data[$this -> model])) ? $data : $data[$this -> model];
			
			foreach ($data as $dkey => $dval) {
				$this -> data -> {$dkey} = stripslashes($dval);
			}
			
			extract($data, EXTR_SKIP);
			
			if (empty($title)) { $this -> errors['title'] = __('Please enter a title', SATL_PLUGIN_NAME); }
			if (empty($type)) { $this -> errors['type'] = __('Please select a gallery type', SATL_PLUGIN_NAME); }
			
			elseif ($type == "customslides") {
			} 
                        elseif ($type == "wordpressimages") {
			}
		} else {
			$this -> errors[] = __('No data was posted', SATL_PLUGIN_NAME);
		}
		return $this -> errors;
	}
        
        /**
         *
         * @global type $wpdb
         * @return type int
         */
        function latestSection() {
            global $wpdb;
            $this -> table = $wpdb -> prefix . "satl_galleries";
            $latest = $this -> find(null,'id');
            return $latest -> id;
        }
        
        /**
         *
         * @param type $gallery @integrer
         * @return type @string
         */
        public function capLocation($position,$gallery) {
            if ($position == "On Right") :
                $briefLocation = "right";
            else:
                $briefLocation = $location -> capposition;
            endif;
            return $briefLocation;
        }
        
        public function loadData($gallery) {
            return $this -> find(array('id'=>$gallery),'caphover, 
                                                        pausehover, 
                                                        capposition, 
                                                        capanimation, 
                                                        title, 
                                                        description, 
                                                        type, 
                                                        id');
            //return $this -> find_all(array('id'=>$gallery));
            //return $animation;
        }
        /** Returns the More Gallery ID **/
        public function getMoreGallery() {
            $more = $this -> find(array('title'=>"More"), 'title,id');
            if ($more->id)
                return $more->id;
            else
                return null;
        }
        public function getGalleries() {
            $galleries = $this -> find_all('','title,id');
            foreach ($galleries as $gallery )
                $galArray[] = array('title'=>$gallery -> title, 'id' => $gallery -> id);
            if ($galArray) {
                return $galArray;
            }
            
            return null;
            
        }
        
}
?>