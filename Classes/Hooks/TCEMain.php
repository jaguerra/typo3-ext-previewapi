<?php

namespace Icti\Previewapi\Hooks;

/**
 *
 */
class TCEMain {


		/**
		 * Generate a different preview link     *
		 * @param string $status status
		 * @param string $table table name
		 * @param integer $recordUid id of the record
		 * @param array $fields fieldArray
		 * @param t3lib_TCEmain $parentObject parent Object
		 * @return void
		 */
		public function processDatamap_afterDatabaseOperations($status, $table, $recordUid, array $fields, \t3lib_TCEmain $parentObject) {

				if ($this->isTableAllowed($table)) {

						if (!is_numeric($recordUid)) {
								$recordUid = $parentObject->substNEWwithIDs[$recordUid];
						}

						$tableConf = $this->getTableConfig($table);
						$previewPid = (int)$tableConf['previewPid'];
						$wsFallbackPreviewPid = (int)$tableConf['wsFallbackPreviewPid'];
						if (isset($GLOBALS['_POST']['_savedokview_x']) && !$GLOBALS['BE_USER']->workspace) {
								if ($previewPid) {
										$record = \t3lib_BEfunc::getRecord($table, $recordUid);
										$parameters = $this->getRecordPreviewParameters($table, $record);

										$GLOBALS['_POST']['popViewId_addParams'] = \t3lib_div::implodeArrayForUrl('', $parameters, '', FALSE, TRUE);
										$GLOBALS['_POST']['popViewId'] = $previewPid;
								}
						} else if ($wsFallbackPreviewPid > 0 && $GLOBALS['BE_USER']->workspace) {
								/*
 								 * On TYPO3 6.1 the WS preview does not pass the "addParams" parameters
 								 * so preview does not work.
 								 * We may add a fallback id of a list view because that way inside a WS
 								 * elements can be shown. 
 								 * Note: hidden elements on WS preview will not work.
 								 */
								$GLOBALS['_POST']['popViewId'] = $wsFallbackPreviewPid;
						}
				}
		}


		/**
 		 *
 		 */
		private function getCurrentPageId() {
				return $GLOBALS['_POST']['popViewId'];
		}

		/**
 		 *
 		 */
		private function getConfig() {
				$pagesTsConfig = \t3lib_BEfunc::getPagesTSconfig($this->getCurrentPageId());
				return $pagesTsConfig['tx_previewapi.'];
		}

		/**
 		 *
 		 */
		private function getTableConfig($table) {
				$conf = $this->getConfig();
				return $conf[$table . '.'];
		}

		/**
 		 *
 		 */
		private function isTableAllowed($table) {
				$conf = $this->getConfig();
				return isset($conf[$table]);
		}

		/**
 		 *
 		 */
		private function getRecordPreviewParameters($table, $record) {
				$conf = $this->getTableConfig($table);
				$paramsTemplate = $conf['params.'];
				$uid = $record['uid'];

				if ($record['sys_language_uid'] > 0) {
						if ($record['l10n_parent'] > 0) {
								$uid = $record['l10n_parent'];
						}
						$paramsTemplate['L'] = $record['sys_language_uid'];
				}

				$params = $this->arrayMapRecursive(
						function ($v) use ($uid){
								return sprintf($v, $uid);
						},
								$paramsTemplate
						);

				return $params;
		}

		/**
 		 *
 		 */
		private function arrayMapRecursive($callback, $array) {
				foreach ($array as $key => $value) {
	
						$trimKey = trim($key, '.');
						if ($trimKey != $key) {
								$array[$trimKey] = $array[$key];
								unset($array[$key]);
								$key = $trimKey;
						}

						if (is_array($array[$key])) {
								$array[$key] = $this->arrayMapRecursive($callback, $array[$key]);
						}
						else {
								$array[$key] = call_user_func($callback, $array[$key]);
						}
				}
				return $array;
		}


}

?>