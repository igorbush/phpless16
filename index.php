<?php 

require_once "vendor/autoload.php";
$api = new \Yandex\Geo\Api();

if (isset($_POST['search'])) {
	$address = "'" . (string)$_POST['address']. "'";
	$api->setQuery($address);
	$api->setLimit(5);
	$api->setLang(\Yandex\Geo\Api::LANG_RU);
	$api->load();
	$response = $api->getResponse();
	$response->getQuery();
	$response->getFoundCount();
	$collection = $response->getList();
}
if (isset($_POST['add'])) {
	$latitude = $_POST['latitude'];
	$longitude = $_POST['longitude'];
	$add = $_POST['add'];
	$address = "'" . (string)$_POST['query']. "'";
	$api->setQuery($address);
	$api->setLimit(5);
	$api->setLang(\Yandex\Geo\Api::LANG_RU);
	$api->load();
	$response = $api->getResponse();
	$response->getQuery();
	$response->getFoundCount();
	$collection = $response->getList();
} else {
	$latitude = '61.67';
	$longitude = '50.80';
	$add = 'Случайный аддрес';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Примеры. Балун и хинт</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	<style> html, body, #map {width: 100%; height: 100%; min-height:500px; ; padding: 0; margin: 0 auto;} </style>
</head>
<body style="background-color: #9fafFF; padding: 90px;">
	<div class="container">
		<div class="row" style="background-color: rgba(0,0,0,0.3); padding: 30px;">
			<div class="col-xl-5">
				<form method="POST">
					<div class="input-group">
						<input type="text" name="address" class="form-control form-control-sm">
						<div class="input-group-append">
							<input type="submit" name="search" value="найти" class="btn btn-primary">
						</div>
					</div>
					<?php if(isset($_POST['search']) || isset($_POST['add'])): ?>
					<?php foreach ($collection as $item): ?>
						<form method="POST">
						<input type="hidden" name="query" value="<?php echo $address; ?> ">
						<input type="hidden" name="longitude" value="<?= $item->getLongitude(); ?>">
						<input type="hidden" name="latitude" value="<?= $item->getLatitude(); ?>">
						<input type="submit" name="add" value="<?= $item->getAddress(); ?>" class="list-group-item list-group-item-primary" style="margin: 30px 0; width: 100%; cursor: pointer;">
						</form>
					<?php endforeach; ?>
				<?php endif; ?>
				</form>
			</div>
			<div class="col-xl-7">
				<div id="map"></div>
				<script>
					ymaps.ready(init);
					function init () {
					    var myMap = new ymaps.Map("map", {
					            center: [<?php echo $latitude; ?>, <?php echo $longitude; ?>],
					            zoom: 11
					        }, {
					            searchControlProvider: 'yandex#search'
					        }),
					        myPlacemark = new ymaps.Placemark([<?php echo $latitude; ?>, <?php echo $longitude; ?>], {
					            balloonContentBody: "<?php echo $add; ?>",
					        });
					    myMap.geoObjects.add(myPlacemark);
					}
				</script>
			</div>
		</div>
	</div>
</body>
</html>