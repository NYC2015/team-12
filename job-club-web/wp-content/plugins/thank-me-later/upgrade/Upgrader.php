<?php

/**
 * 
 */
class Bbpp_ThankMeLater_Upgrader {
	/**
	 * the string that precedes transformed version number (e.g. [$upgrader_class_prefix]2_1 for upgrader to version 2.1)
	 *
	 * @var string
	 */
	private $upgrader_class_prefix = "Bbpp_ThankMeLater_Upgrade";
	
	/**
	 *
	 * @var string 
	 */
	private $upgrader_file_prefix = "Upgrade";
	
	/**
	 * array of version numbers and transformed versions. must be in order with latest version at end of array
	 * @var array
	 */
	private $upgraders = array(
		array("version" => "0", "transformed" => "0"),
		array("version" => "1.3.1", "transformed" => "1_3_1"),
		array("version" => "1.4.1", "transformed" => "1_4_1"),
		array("version" => "1.5.3.1", "transformed" => "1_5_3_1"),
		array("version" => "2.0.0.2", "transformed" => "2_0_0_2"),
		array("version" => "2.1", "transformed" => "2_1"),
		array("version" => "3.0", "transformed" => "3_0"),
		array("version" => "3.0.1", "transformed" => "3_0_1"),
		array("version" => "3.0.2", "transformed" => "3_0_2"),
		array("version" => "3.0.3", "transformed" => "3_0_3"),
		array("version" => "3.0.4", "transformed" => "3_0_4"),
		array("version" => "3.0.5", "transformed" => "3_0_5"),
		array("version" => "3.0.6", "transformed" => "3_0_6"),
		array("version" => "3.0.7", "transformed" => "3_0_7"),
		array("version" => "3.1", "transformed" => "3_1"),
		array("version" => "3.3", "transformed" => "3_3")
	);
	
	/**
	 * Get the version number of the version of plugin currently installed.
	 * 
	 */
	public function getCurrentVersion() {
		foreach (array_slice(array_reverse($this->upgraders), 0, -1) as $upgrader) {
			$file_name = $this->upgrader_file_prefix . $upgrader["transformed"] . ".php";
			$class_name = $this->upgrader_class_prefix . $upgrader["transformed"];

			// get this upgrader class:
			if (!class_exists($class_name)) {
				require_once BBPP_THANKMELATER_PLUGIN_PATH . "upgrade/" . $file_name;
			}
			
			$inst = new $class_name();
			if ($inst->ishere()) {
				return $upgrader["version"];
			}
		}
		
		return "0";
	}
	
	/**
	 * Get the $upgraders index for version $version.
	 * 
	 * @param type $version
	 */
	private function getVersionIndex($version) {
		foreach ($this->upgraders as $index => $upgrader) {
			if ($upgrader["version"] == $version) {
				return $index;
			}
		}
		
		return NULL;
	}
	
	/**
	 * Upgrade the plugin to $to_version
	 * 
	 * @param string $to_version version to upgrade/downgrade to
	 */
	public function upgrade($to_version) {
		// upgrade already running, don't run two at once!
		if (get_option("Bbpp_ThankMeLater_Upgrader") > time() - 7200) {
			return FALSE;
		}		
		update_option("Bbpp_ThankMeLater_Upgrader", time());
		
		$from_version = $this->getCurrentVersion();
		$from_index = $this->getVersionIndex($from_version);
		$to_index = $this->getVersionIndex($to_version);
		
		// if we can't upgrade, do a fresh install
		if (is_null($from_index)) {
			$from_index = 0;
		}
		
		$is_upgrade = ($to_index > $from_index);

		$phases = array();
		
		// calculate the indexes of the classes we start and end up at (we go in reverse)		
		if ($is_upgrade) {
			// upgrading
			$origin_ind = $to_index;
			$terminal_ind = $from_index + 1;
		} else {
			// downgrading
			$origin_ind = $from_index;
			$terminal_ind = $to_index + 1;
		}
		
		$ind = $origin_ind;
		
		while ($ind != $terminal_ind-1) {
			$ind_transformed = $this->upgraders[$ind]["transformed"];
			$file_name = $this->upgrader_file_prefix . $ind_transformed . ".php";
			$class_name = $this->upgrader_class_prefix . $ind_transformed;
			
			// get this upgrader class:
			if (!class_exists($class_name)) {
				require_once BBPP_THANKMELATER_PLUGIN_PATH . "upgrade/" . $file_name;
			}

			// try to find the biggest shortcut possible
			$sind = $terminal_ind - 1;
			while ($sind != $ind) {
				$sind_transformed = $this->upgraders[$sind]["transformed"];
				
				if (abs($sind - $ind) < 1.01) {
					// moving one change
					$method_name = $is_upgrade ? "up" : "down";
				} else {
					// skipping changes
					if ($is_upgrade) {
						$method_name = "up_from_{$sind_transformed}";
					} else {
						$method_name = "down_to_{$sind_transformed}";
					}
				}
				
				// if we can make this jump, do so, and then move our pointer
				if (method_exists($class_name, $method_name)) {
					$phases[] = array($ind, $class_name, $method_name);
					$ind = $sind;
					break;
				}
				
				$sind++;
			}
		}
		
		if ($is_upgrade) {
			$phases = array_reverse($phases);
		}
		
		// we are going to upgrade:
		if (count($phases)) {
			// include upgrade functions from wordpress:
			require_once ABSPATH . "wp-admin/includes/upgrade.php";
			
			// execute the instructions
			foreach ($phases as $phase) {
				$class_name = $phase[1];
				$method_name = $phase[2];

				$upgrader = new $class_name();
				$upgrader->$method_name();
			}
		}
		
		// installed!
		delete_option("Bbpp_ThankMeLater_Upgrader");
		
		return $this->getCurrentVersion();
	}
}