<?php

/**
 * Upgrade to version 1.3.1 (also version 1.1, 1.2, 1.3)
 */
class Bbpp_ThankMeLater_Upgrade1_3_1 {
	public function ishere() {
		if (get_option("_tml_installed") == "true") {
			return TRUE;
		}
	}
	
	public function up() {
		/* fresh install to 1.3.1 */
	}

	public function down() {
		
	}
}