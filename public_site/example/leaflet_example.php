<?php

/**
 * Copyright (C) 2019-2022 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>
<link rel="stylesheet" href="/css/leaflet.css" />
<script src="/js/leaflet.js"></script>
<script>
	var Leaflet = L.noConflict();
</script>
<style>
	html,
	body {
		height: 80%;
		margin: 0;
	}

	#map {
		width: 600px;
		height: 400px;
	}
</style>
<div id='map' style="z-index:999;"></div>
<script>
	$.getJSON("/public_site/example/geojson.json")
		.done(function (json) {
			console.log("JSON Data: " + json);
			GetSecondJson(json);
		})
		.fail(function (jqxhr, textStatus, error) {
			var err = textStatus + ", " + error;
			console.log("Request Failed: " + err);
		});

	function GetSecondJson(json1) {
		$.getJSON("/public_site/example/geojson2.json")
			.done(function (json) {
				console.log("JSON Data: " + json);
				DrawMap(json, json1);
			})
			.fail(function (jqxhr, textStatus, error) {
				var err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
	}

	function DrawMap(json, json1) {
		var campus = json;
		var bicycleRental = json1;
		const map = Leaflet.map('map').setView([39.74739, -105], 13);

		const tiles = Leaflet.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);

		const baseballIcon = Leaflet.icon({
			iconUrl: 'baseball-marker.png',
			iconSize: [32, 37],
			iconAnchor: [16, 37],
			popupAnchor: [0, -28]
		});

		function onEachFeature(feature, layer) {
			let popupContent = `<p>I started out as a GeoJSON ${feature.geometry.type}, but now I'm a Leaflet vector!</p>`;

			if (feature.properties && feature.properties.popupContent) {
				popupContent += feature.properties.popupContent;
			}

			layer.bindPopup(popupContent);
		}

		/* global campus, bicycleRental, freeBus, coorsField */
		const bicycleRentalLayer = Leaflet.geoJSON([bicycleRental, campus], {

			style(feature) {
				return feature.properties && feature.properties.style;
			},

			onEachFeature,

			pointToLayer(feature, latlng) {
				return Leaflet.circleMarker(latlng, {
					radius: 8,
					fillColor: '#ff7800',
					color: '#000',
					weight: 1,
					opacity: 1,
					fillOpacity: 0.8
				});
			}
		}).addTo(map);

	}

</script>