<?php

/**
 * Upgrade to version 1.5.3.1 (also version 1.5, 1.5.1, 1.5.2, 1.5.3)
 */
class Bbpp_ThankMeLater_Upgrade1_5_3_1 {
	public function ishere() {
		if (get_option("_tml_installed") == "1.5") {
			return TRUE;
		}
		
		if (get_option("_tml_installed") == "1.5.1") {
			return TRUE;
		}
		
		if (get_option("_tml_installed") == "1.5.2") {
			return TRUE;
		}
		
		if (get_option("_tml_installed") == "1.5.3") {
			return TRUE;
		}
		
		if (get_option("_tml_installed") == "1.5.3.1") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 1.4.1
	 */
	public function up() {		
		// add `restrict_tagsType`, `restrict_tagsArr`, `restrict_categoriesType`, `restrict_categoriesArr` to options
		$_TML_options = get_option("_tml_options");
		if (!is_array($_TML_options)) {
			$_TML_options = unserialize($_TML_options);
		}
		$_TML_options["restrict_tagsType"] = "0";
		$_TML_options["restrict_tagsArr"] = array();
		$_TML_options["restrict_categoriesType"] = "0";
		$_TML_options["restrict_categoriesArr"] = array();
		$_TML_options["use_html"] = "false";
		delete_option('_tml_options');
		add_option('_tml_options', serialize($_TML_options), NULL, 'no');
		
		// indicate we are at version 1.5.3.1
		update_option("_tml_installed", "1.5.3.1");
	}

	/**
	 * Downgrade to 1.4.1
	 */
	public function down() {
		
	}
}