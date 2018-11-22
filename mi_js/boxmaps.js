    mapboxgl.accessToken = 'pk.eyJ1IjoicHJ1c3NvMjUiLCJhIjoiY2pvbjgzazJ2MHM3dTNxbzV6MHowYXZ1bCJ9.1rAOSL_0iJrGtCl4rq6tHw';
    

    var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/mapbox/streets-v9',
    center: [-58.3772300, -34.6083], // starting position
    zoom: 8 // starting zoom
    });

    // Add zoom and rotation controls to the map.
    map.addControl(new mapboxgl.NavigationControl());

    $.ajax({
               	url: "./controller/ScriptController.php", 
 			    type: "get",
               	dataType: "json",
               	data: {
               	      act: "mapaInstitucion", 	
               	      valor: document.getElementById("pac_id").value
               	      },	
			    contentType: "application/json; charset=utf-8",
				success: function(data) {
                          // datos de respuesta
                          // console.log(data);
						  // add markers to map
						
						  data.features.forEach(function(marker) {

						  // create a HTML element for each feature
						  var el = document.createElement('div');
						  el.className = 'marker';

						  // make a marker for each feature and add to the map
						  new mapboxgl.Marker(el)
						  .setLngLat(marker.geometry.coordinates)
						  .addTo(map);
						});

					},
				error: function() {
				        console.log("No se encontraron mapas");
		  }
 });	



