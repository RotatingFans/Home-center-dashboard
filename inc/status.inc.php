<?php
include_once('lib/status.php');

//Systemzeit
$uptime = formatTimefromSeconds(getUptime());
$data['uptimeSort'] = $uptime;
$data['uptime'] = $uptime;

$date = new DateTime();
$date = $date->getTimestamp() - getUptime();
$data['lastBootTime'] = date('d/m/Y H:i:s', $date);

//CPU
$sysload = getSysLoad();
$data['sysload_0'] = $sysload[0];
$data['sysload_1'] = $sysload[1];
$data['sysload_2'] = $sysload[2];

$data['cpuClock'] = number_format(getCpuClock(), 2, '.', ',');

$data['coreTemp'] = number_format(getCoreTemprature(), 2, '.', ',');

//Speicher
$memory = getMemoryUsage();
$data['memoryPercent'] = $memory['percent'];
$data['memoryPercentDisplay'] = number_format($memory['percent'], 0,'.', ',');
$data['memoryTotal'] = formatBytesBinary($memory['total']);
$data['memoryFree'] = formatBytesBinary($memory['free']);
$data['memoryUsed'] = formatBytesBinary($memory['used']);

//Swap
$swap = getSwapUsage();
$data['swapPercent'] = $swap['percent'];
$data['swapPercentDisplay'] = number_format($swap['percent'], 0,'.', ',');
$data['swapTotal'] = formatBytesBinary($swap['total']);
$data['swapFree'] = formatBytesBinary($swap['free']);
$data['swapUsed'] = formatBytesBinary($swap['used']);

//Systemspeicher
$sysMemory = getMemoryInfo();
$data['sysMemory'] = '';
foreach ($sysMemory as $index => $mem) {

    if ($index != (count($sysMemory) - 1)) {

        $data['sysMemory'] .= '
            <div class="row">
                <div class="col-xs-2 storageRow">' . @htmlentities($mem['device'], ENT_QUOTES, 'UTF-8') . '</div>
                <div class="col-xs-2 storageRow">' . @htmlentities($mem['mountpoint'], ENT_QUOTES, 'UTF-8') . '</div>
                <div class="col-xs-2 storageRow">' . @htmlentities($mem['percent'], ENT_QUOTES, 'UTF-8') . '%</div>
                <div class="col-xs-2 storageRow">' . formatBytesBinary($mem['total']) . '</div>
                <div class="col-xs-2 storageRow">' . formatBytesBinary($mem['used']) . '</div>
                <div class="col-xs-2 storageRow">' . formatBytesBinary($mem['free']) . '</div>
            </div>';
    }
}

//Netzwerk
$network = getNetworkDevices();
$data['network'] = '';
foreach ($network as $index => $net) {

    $data['network'] .= '
            <div class="row">
                <div class="col-xs-3 networkRow">' . @htmlentities($net['name'], ENT_QUOTES, 'UTF-8') . '</div>
                <div class="col-xs-3 networkRow">' . formatBytesBinary($net['in']) . '</div>
                <div class="col-xs-3 networkRow">' . formatBytesBinary($net['out']) . '</div>
                <div class="col-xs-3 networkRow">' . number_format($net['errors'], 0, ',', '.') . '/' . number_format($net['drops'], 0, ',', '.') . '</div>
            </div>';
}
?>

	<div class="panel panel-primary">
		<div class="panel-heading">System Time</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-6 col-sm-4 col-md-2" style="text-align: right">
					Running Time:
				</div>
				<div class="col-xs-6  col-sm-8 col-md-10">
					<?= $data['uptime'] ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 col-sm-4 col-md-2" style="text-align: right">
					Last Start:
				</div>
				<div class="col-xs-6  col-sm-8 col-md-10">
					<?= $data['lastBootTime'] ?>
				</div>
			</div>
		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">System</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-6 col-sm-4 col-md-2" style="text-align: right">
					CPU Utilization:
				</div>
				<div class="col-xs-6  col-sm-8 col-md-10">
					<?= $data['sysload_0'] ?> >
						<?= $data['sysload_1'] ?> >
							<?= $data['sysload_2'] ?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6 col-sm-4 col-md-2" style="text-align: right">
					CPU Clock:
				</div>
				<div class="col-xs-6  col-sm-8 col-md-10">
					<?= $data['cpuClock'] ?> MHz
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 col-sm-4 col-md-2" style="text-align: right">
					Temperature:
				</div>
				<div class="col-xs-6  col-sm-8 col-md-10">
					<?= $data['coreTemp'] ?> &deg; C
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-6 col-sm-2 col-md-2" style="text-align: right">
					RAM:
				</div>
				<div class="col-xs-6  col-sm-10 col-md-10">
					<div class="row">
						<div class="col-sm-8">
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?= $data['memoryPercentDisplay'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $data['memoryPercentDisplay'] ?>%;">
									<span class="sr-only"><?= $data['memoryPercentDisplay'] ?>% Complete</span>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							(
							<?= $data['memoryUsed'] ?> /
								<?= $data['memoryTotal'] ?> )
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 col-sm-2 col-md-2" style="text-align: right">
					SWAP:
				</div>
				<div class="col-xs-6  col-sm-10 col-md-10">
					<div class="row">
						<div class="col-sm-8">
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?= $data['swapPercentDisplay'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $data['swapPercentDisplay'] ?>%;">
									<span class="sr-only"><?= $data['swapPercentDisplay'] ?>% Complete</span>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							(
							<?= $data['swapUsed'] ?> /
								<?= $data['swapTotal'] ?> )
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">Storage</div>
		<div class="panel-body">

			<div class="row">
				<div class="row">
					<div class="col-xs-2 storageHead">Partition</div>
					<div class="col-xs-2 storageHead">Mountpoint</div>
					<div class="col-xs-2 storageHead">Utilization</div>
					<div class="col-xs-2 storageHead">Overall</div>
					<div class="col-xs-2 storageHead">Used</div>
					<div class="col-xs-2 storageHead">Free</div>
				</div>

				<?= $data['sysMemory'] ?>

			</div>
		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">Network</div>
		<div class="panel-body">

			<div class="row">
				<div class="row">
					<div class="col-xs-3 networkHead">Interface</div>
					<div class="col-xs-3 networkHead">Recieved</div>
					<div class="col-xs-3 networkHead">Sent</div>
					<div class="col-xs-3 networkHead">Error/Lost</div>
				</div>
				<?= $data['network'] ?>

			</div>
		</div>
	</div>
