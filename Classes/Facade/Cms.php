<?php
namespace Icti\Previewapi\Facade;

class Cms {

		/**
	 	 * Returns a be_user record as lowerCamelCase indexed array if a BE user is
	 	 * currently logged in.
	 	 *
	 	 * @return array
	 	 */
		protected function getCurrentBackendUser() {
				return $GLOBALS['BE_USER']->user;
		}

		/**
	 	 * Returns TRUE only if a backend user is currently logged in.
	 	 *
	 	 * @return boolean
	 	 * @api
	 	 */
		public function isBackendUserLoggedIn() {
				return is_array($this->getCurrentBackendUser());
		}

		/**
 		 *
 		 */
		public function isInVersioningPreview() {
				return $GLOBALS['TSFE']->sys_page->versioningPreview;
		}

}

?>