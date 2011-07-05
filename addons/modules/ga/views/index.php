<div id="analytics"></div>

<script>
jQuery(function($) {
	var visits = <?php echo isset($analytic_visits) ? $analytic_visits : 0; ?>;
	var views = <?php echo isset($analytic_views) ? $analytic_views : 0; ?>;
	
	$('#analytics').css({
		height: '300px',
		width: '95%'
	});

	$.plot($('#analytics'), [{ label: 'Visits', data: visits },{ label: 'Page views', data: views }], {
		lines: { show: true },
		points: { show: true },
		grid: { backgroundColor: '#fffaff' },
		series: {
			lines: { show: true, lineWidth: 1 },
			shadowSize: 0
		},
		xaxis: { mode: "time" },
		yaxis: { min: 0},
		selection: { mode: "x" }
	});
});


</script>