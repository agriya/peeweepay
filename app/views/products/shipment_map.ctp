<?php /* SVN: $Id: $ */ ?>
<div class="shipmentMap js-shipment-map">
<h2><?php echo __l('Shipment costs per country'); ?></h2>
<script type='text/javascript' src='http://www.google.com/jsapi'></script>
  <script type='text/javascript'>
   google.load('visualization', '1', {'packages': ['geomap']});
   google.setOnLoadCallback(drawMap);

    function drawMap() {
     var data = new google.visualization.DataTable();
	  cost_count  = __cfg('productshipmentcost').length;
      data.addRows(cost_count);
      data.addColumn('string', 'Country');
      data.addColumn('number', 'Shipment Costs');	  
	  
	  for(i = 0; i < cost_count; i++){
			var cost = parseFloat(__cfg('productshipmentcost')[i].shipment_cost);
			data.setValue(i, 0, __cfg('productshipmentcost')[i].country);
			data.setValue(i, 1, cost);
	  }
      var options = {};
      options['dataMode'] = 'regions';

      var container = document.getElementById('map_canvas');
      var geomap = new google.visualization.GeoMap(container);
      geomap.draw(data, options);
	  	 
  };
  </script>

    <div id='map_canvas'></div>

</div>
