<?php

/**
 * Copyright (C) 2019-2022 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>
<!doctype html>
<html>

<head>
	<title>CED E4</title>
	<script src="/js/Chart.bundle.js"></script>
	<style>
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>
</head>

<body>

	<div style="width:75%;">
		<canvas id="mycanvas"></canvas>
	</div>
	<script type="text/javascript">


		var Graph;

		function createChart(chartdata) {
			console.log("createChart");
			var ctx = $("#mycanvas");
			Graph = new Chart(ctx, {
				type: 'line',
				data: chartdata,
				options: {
					title: {
						display: true,
						text: 'Temperature CED E4 ' + new Date()
					}
				}
			});
		}


		function getData() {
			$.ajax({
				url: "/public_site/example/graph_data.json",
				method: "GET",
				success: function (data) {
					var array_date = [];
					var array_valori_1 = [];
					var array_valori_2 = [];

					for (var i in data) {
						array_date.push(data[i].data);
						array_valori_1.push(data[i].s217);
						array_valori_2.push(data[i].s218);
					}

					var chartdata = {
						labels: array_date,
						datasets: [
							{
								label: 'sensore 217',
								backgroundColor: 'rgba(0, 255, 0, 0.3)',
								borderColor: 'rgba(0, 255, 0, 0.3)',
								data: array_valori_1,
								fill: false
							},
							{
								label: 'sensore 218',
								backgroundColor: 'rgba(0, 0, 255, 0.3)',
								borderColor: 'rgba(0, 0, 255, 0.3)',
								data: array_valori_2,
								fill: false
							}
						]
					};
					createChart(chartdata);

				},
				error: function (data) {
					console.log(data);
				}
			});
		}
		jQuery(document).ready(function () {
			getData();
		});


	</script>
</body>

</html>