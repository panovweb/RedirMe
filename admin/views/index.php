<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<div class="chartBlock half" id="compareClicksChart"></div>
<div class="block stats half">
	<span class="title">[stats_title]</span>
	<section class="row top">
		<span class="name">[name_label]</span>
		<span class="value">[value_label]</span>
	</section>
	<article class="row">
		<section class="name">[links_label]</section>
		<section class="value">[links_value]</section>
	</article>
	<article class="row">
		<section class="name">[categories_label]</section>
		<section class="value">[categories_value]</section>
	</article>
	[users_row]
	<section class="row bottom">
		<span class="name">[name_label]</span>
		<span class="value">[value_label]</span>
	</section>
</div>
<div class="chartBlock" id="compareChart"></div>
<script type="text/javascript">
	google.charts.load("current", {packages:["corechart"]});
	google.charts.setOnLoadCallback(drawClicksChart);
	google.charts.setOnLoadCallback(drawCategoryChart);
	function drawClicksChart() {
		var data = google.visualization.arrayToDataTable([
			['[chart_link_label]', '[chart_clicks_amount_label]'],
			[chart_clicks_data]
		]);
		var options = {
			title: '[chart_clicks_title]',
			titleTextStyle: {
				color: '#000',
				fontName: 'Noto Sans, sans-serif',
				fontSize: '15px',
				bold: false
			},
			chartArea: {
				width: '98%'
			},
			pieHole: 0.4,
			legend: {
				position: 'none'
			},
		};
		var chart = new google.visualization.PieChart(document.getElementById('compareClicksChart'));
		chart.draw(data, options);
	}
	function drawCategoryChart() {
		var data = google.visualization.arrayToDataTable([
			['[chart_cat_label]', '[chart_amount_label]'],
			[chart_data]
		]);
		var options = {
			title: '[chart_title]',
			titleTextStyle: {
				color: '#000',
				fontName: 'Noto Sans, sans-serif',
				fontSize: '15px',
				bold: false
			},
			chartArea: {
				width: '98%'
			},
			pieHole: 0.4,
			legend: {
				position: 'labeled'
			},
		};
		var chart = new google.visualization.PieChart(document.getElementById('compareChart'));
		chart.draw(data, options);
	}
</script>