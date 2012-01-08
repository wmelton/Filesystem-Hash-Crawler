<?php

	/**
	 * FilesystemCrawler - Class
	 *
	 * @author Marcel Ernst
	 * @version 1.0.0.0
	 */
	class FilesystemCrawler {
		
		protected $rootdir = '';
		private $ignore  = Array('.', '..');
		private $trigger = Array();

		/**
		 * Constructor
		 * @param string crawler root directory
		 * @param array  trigger-callbacks
		 */
		public function __construct($rootdir = './', $trigger = array())
		{
			$this->trigger = $trigger;
			$this->rootdir = $rootdir;
		}


		/**
		 * add an scandir-result to the ignorelist
		 * @param string name
		 */
		public function addIgnore($name)
		{
			$this->ignore[] = $name;
		}


		/**
		 * start the crawler
		 */
		public function run()
		{
			return $this->readdir($this->rootdir);
		}


		/**
		 * recursiv readdir method
		 * @param string dirpath
		 * @return array directory content
		 */
		private function readdir($path)
		{
			$tree = Array();
			foreach(scandir($path) as $content) {

				// is $content on ignorelist?
				if(in_array($content, $this->ignore)) {
					// trigger callback
					$this->trigger('ignore', $path, $content);
					continue;
				}
				
				// is $content a directory?
				if(is_dir($path . $content)) {
					// trigger callback
					$this->trigger('directory', $path, $content);

					$tree[$path][$content] = $this->readdir($path . $content . '/');
					continue;
				}

				// is $content a file?
				if(is_file($path . $content)) {
					// trigger callback
					$this->trigger('file', $path, $content);

					$tree[$path][] = $content;

					continue;
				}

				// trigger callback
				$this->trigger('else', $path, $content);

				$tree[$path][] = $content;
			}

			return $tree;
		}


		/**
		 * @param string trigger-type (ignore, directory, file, else)
		 * @param string result-path
		 * @param string ignore-, directory-, file- or else name
		 */
		private function trigger($type, $path, $content)
		{
			if(!isset($this->trigger[$type])) {
				return;
			}

			if(is_callable($this->trigger[$type]))
				call_user_func($this->trigger[$type], $path, $content);
		}
	}

