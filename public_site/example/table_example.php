<?php

/**
 * Copyright (C) 2019-2022 ARPA Piemonte - Dipartimento Naturali e Ambientali
 * This file is part of easyportal (Easy and simple PHP site with bootstrap-italia library).
 * easyportal is licensed under the AGPL-3.0-or-later License.
 * License text available at https://www.gnu.org/licenses/agpl.txt
 */

?>

<script type="text/javascript" src="/js/tabulator/tabulator.min.js"></script>
<link href="/css/tabulator/tabulator.min.css" rel="stylesheet">
<br>
<font style="color:red;">Click on a row for Graph!</font>
<div id="example-table"></div>

<script type="text/javascript">
	//create Tabulator on DOM element with id "example-table"
	var table = new Tabulator("#example-table", {
		height: "311px",
		layout: "fitColumns",
		placeholder: "No Data Set",
		columns: [
			{ title: "Name", field: "name", sorter: "string", width: 200 },
			{ title: "Progress", field: "progress", sorter: "number", formatter: "progress" },
			{ title: "Gender", field: "gender", sorter: "string" },
			{ title: "Rating", field: "rating", formatter: "star", hozAlign: "center", width: 100 },
			{ title: "Favourite Color", field: "col", sorter: "string" },
			{ title: "Date Of Birth", field: "dob", sorter: "date", hozAlign: "center" },
			{ title: "Driver", field: "car", hozAlign: "center", formatter: "tickCross", sorter: "boolean" },
		],
		rowClick: function (e, row) { //trigger an alert message when the row is clicked
			//alert("Row " + row.getData().codstaz + " Clicked!!!!");
			open_popup(row.getData().codstaz);
		},
		paginationSize: 10,
	});

	//trigger AJAX load on "Load Data via AJAX" button click
	table.setData("/public_site/example/table_data.json");

</script>
<script type="text/javascript">
	function open_popup(url) {
		var popup = window.open(url, "_blank", "width=800, height=580");
	}
</script>
<br>