<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?php echo $api_key; ?>" type="text/javascript"></script>
<script>
var lat = '<?php echo @$lat; ?>';
var lng = '<?php echo @$lng ?>';

var html='';


if (GBrowserIsCompatible())
{
	var map = new GMap2(document.getElementById("<?php echo $el_id; ?>"));
	var ct = new GLatLng(lat, lng);

	map.setCenter(ct, 15);

	map.addControl( new GSmallMapControl() );
	//map.addControl( new GHierarchicalMapTypeControl () );

	var gm=new GMarker(ct);


	if(html != '')
	{
		GEvent.addListener(gm, "click", function()
				{
					this.openInfoWindowHtml( html );
				} );
	}

	map.addOverlay(gm);

	map.enableContinuousZoom();
	map.enableInfoWindow();
}
</script>