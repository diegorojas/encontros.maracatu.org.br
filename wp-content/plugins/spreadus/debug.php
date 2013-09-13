<?php
/**
 * Spread.us debug class
 */
if(!class_exists('SpreadusDebug'))
{
	class SpreadusDebug
	{
		/**
		 * Print a list of functions that hook something
		 */
		function list_hooked_functions($tag=false)
		{
			global $wp_filter;
			if($tag)
			{
				$hook[$tag]=$wp_filter[$tag];
				if(!is_array($hook[$tag]))
				{
					error_log(__FILE__.' : '. __LINE__."\n", 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
					error_log("Nothing found for '$tag' hook\n", 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log');
					return;
				}
			}
			else
			{
				$hook=$wp_filter;
				ksort($hook);
			}
			$buffer = '';
			foreach($hook as $tag => $priorities)
			{
				$buffer .= "\n>>>>>>\t".$tag."\n";
				ksort($priorities);
				foreach($priorities as $priority => $functions)
				{
					$buffer .= $priority;
					foreach($functions as $name => $properties)
					{
						$buffer .= "\t".$name."\n";
					}
				}
			}
			
			error_log(__FILE__.' : '. __LINE__."\n", 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log' );
			error_log($buffer, 3, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'debug.log');
			return $buffer;
		}
	}
}

/**
 * Initialize debug class
 */
$spreadus_debug = new SpreadusDebug;