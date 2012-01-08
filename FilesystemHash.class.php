<?php

	/**
	 * FilesystemHash - Class
	 *
	 * @author Marcel Ernst
	 * @version 1.0.0.0
	 */
	class FilesystemHash extends FilesystemCrawler {
		private $counter_files   = 0;
		private $counter_ignored = 0;
		private $counter_folders = 0;
		private $counter_else    = 0;

		private $hashes = array();

		private $hashesResultDir  = './crawlerResults/';
		private $hashesMasterFile = './crawlerResults/master.php';

		/**
		 * Constructor
		 * @param string root-directory (default: './')
		 */
		public function __construct($rootdir = './')
		{
			$cb = array();
			$cb['file']      = array($this, 'trigger_file');
			$cb['directory'] = array($this, 'trigger_directory');
			$cb['ignore']    = array($this, 'trigger_ignore');
			$cb['else']      = array($this, 'trigger_else');
			parent::__construct($rootdir, $cb);
		}


		/**
		 * start the crawler
		 * @param bool generate Master-File
		 * @return array ['tree']     => crawler-tree
		 *               ['profiler'] => Array (
		 *                   ['crawlingtime'], 
		 *                   ['memory_get_peak_usage'],
		 *                   ['memory_get_usage']
		 *                 )
		 */
		public function run($saveResult = TRUE, $generateMaster = FALSE)
		{
			$this->hashes = array();

			$start = microtime(TRUE);
			
			$result['tree'] = parent::run();
			$result['profiler']['crawlingtime']          = microtime(TRUE) - $start;
			$result['profiler']['memory_get_peak_usage'] = memory_get_peak_usage();
			$result['profiler']['memory_get_usage']      = memory_get_usage();


			if($generateMaster) {
				$data = serialize($this->hashes);
				file_put_contents($this->hashesMasterFile, $data);
			}

			$compareResult = $this->compare();

			ob_start();
				require_once 'hashCrawlerResultTpl.php';
			$result['protocol'] = ob_get_clean();
			
			$result['valid'] = $compareResult['valid'];

			if($saveResult === TRUE) {
				$this->saveResult($result['protocol']);
			}
			return $result;
		}


		/**
		 * save the crawling- and compare-result to filesystem
		 * @param string the html-protocol
		 */
		private function saveResult($protocol)
		{
			$fn = $this->hashesResultDir . date('Y-m-d--H-i-s') . '.html';
			file_put_contents($fn, $protocol);
		}


		/**
		 * compare crawler-result with master
		 * @return array ['protocol'] => (string) HTML-Protocol, 
		 *               ['valid']    => (bool)   filesystem equal master
		 */
		private function compare() {
			$master = file_get_contents($this->hashesMasterFile);
			$master = unserialize($master);

			$valid = TRUE;

			ob_start();
				require_once 'compareProtocolTpl.php';
			$obC = ob_get_clean();

			return array(
				'protocol' => $obC,
				'valid'    => $valid
			);
		}


		/**
		 *
		 */
		public function setHashesResultDir($value)
		{
			$this->hashesResultDir = $value;
		}


		/**
		 * set the path of master-file
		 * @param string Masterfile path
		 */
		public function setHashesMasterFile($value)
		{
			$this->hashesMasterFile = $value;
		}

		/**
		 * @param string path of triggered file
		 * @param string filename
		 */
		protected function trigger_file($path, $name)
		{
			$this->counter_files++;

			$this->hashes[$path . $name] = sha1_file($path . $name);
		}


		/**
		 * @param string path of triggered directory
		 * @param string directoryname
		 */
		protected function trigger_directory($path, $name)
		{
			$this->counter_folders++;
		}


		/**
		 * @param string path of triggered file
		 * @param string filename
		 */
		protected function trigger_ignore($path, $name)
		{
			$this->counter_ignored++;
		}


		/**
		 * @param string path of triggered file
		 * @param string filename
		 */
		protected function trigger_else($path, $name)
		{
			$this->counter_else++;
		}

	}
