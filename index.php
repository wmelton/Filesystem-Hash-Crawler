<?php

	require_once "FilesystemCrawler.class.php";
	require_once "FilesystemHash.class.php";


	$c = new FilesystemHash('./');
	$c->addIgnore('.DS_Store');
	$c->addIgnore('crawlerResults');

	$generateMaster = FALSE;
	if(isset($_GET['generateMaster']))
		$generateMaster = TRUE;

	$result = $c->run(TRUE, $generateMaster);

	if($result['valid'] !== TRUE) {
		@mail('my@email.com', 'Filesystem is invalid', $result['protocol']);
	}

	echo $result['protocol'];