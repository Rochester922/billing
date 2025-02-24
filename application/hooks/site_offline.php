<?php
 
if (!defined('BASEPATH'))
exit('No direct script access allowed');
 
/**
* Description of site_offline
*
* @author admin
*/
class Site_Offline {
 
	function __construct() {
	}
	 
	public function is_offline() {
		if (file_exists(APPPATH . 'config/config.php')) {
			include(APPPATH . 'config/config.php');
			if (isset($config['is_offline']) && $config['is_offline'] === TRUE) {
				$this->show_site_offline();
				exit;
			}
		}
	}
	 
	private function show_site_offline() {
		//echo '<html><body><span style="color:red;"><strong>The site is offline due to maintenance. We will be back soon. Please check back in a few minutes.</strong></span></body></html> ';
		echo '<!DOCTYPE html><html><body style="background-color: #032E37;"><center>';
		echo '<h1><span style="font-family: Courier, Arial, Helvetica, sans-serif; color: White;">SITE  UNDER  MAINTENANCE</span></h1>';
		echo '<img src="http://';
		//echo $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'];
		echo $_SERVER['HTTP_HOST'];
		echo '/assets/images/um.png" alt="">';
		echo '<h2><span style="font-family: Courier, Arial, Helvetica, sans-serif; color: White;">Please try again later, we will be back shortly.</span></h2>';
		echo '</center></body></html>';
	}
 
}
 
/* End of file site_offline.php */
/* Location: ./application/hooks/site_offline.php */