<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<title>Crawler-Result <?= date('Y-m-d H:i:s') ?></title>
	</head>
	<body>
		<h1>Crawler-Result</h1>
		
		<h2>Statistic</h2>

		date: <?= date('Y-m-d H:i:s') ?><br />
		rootdir: <?= $this->rootdir ?><br />
		<br />
		crawlingtime:          <?= $result['profiler']['crawlingtime']          ?> Sec<br />
		memory_get_peak_usage: <?= ($result['profiler']['memory_get_peak_usage'] / 1024 / 1024) ?> MB<br />
		memory_get_usage:      <?= ($result['profiler']['memory_get_usage']      / 1024 / 1024) ?> MB<br />
		<br />


		files:   <?= $this->counter_files   ?><br />
		ignored: <?= $this->counter_ignored ?><br />
		folders: <?= $this->counter_folders ?><br />
		else:    <?= $this->counter_else    ?><br />
		total:   <?= ( $this->counter_files + $this->counter_ignored + $this->counter_folders ) ?><br />
		<br />

		isValid? <?= ($compareResult['valid'] ? 'TRUE' : 'FALSE') ?>

		<h2>Protocol</h2>
		<?= $compareResult['protocol'] ?>

	</body>
</html>