$(function() {
      
     // logica press key paciente 
     $("#paciente").on('keyup keydown',function(){
         $("#id").val(-1); // valor por defecto en caso que no se selecion un paciente
         document.getElementById("paciente").style.color = "red";
     });

     
     // logica autocomplete
     $("#paciente").autocomplete({
        source: function (request, response) 
        {
               // valor del input en --> request.term

               $.ajax({
               	    url: "./controller/ScriptController.php", 
 					type: "get",
               	    dataType: "json",
               	    data: {
               	      act: "datosAutocomplete", 	
               	      valor: request.term,
               	    },	
					contentType: "application/json; charset=utf-8",
					success: function(data) {
                        response(data); // datos de respuesta
					},
					error: function() {
				        console.log("No se ha podido obtener la información");
				        response([]);
				    }
				});	
        },

        // logica valor del autocomplete
        select:function (event, ui)  {
         	   $("#id").val(ui.item.id); // asignar el ID del paciente	   
         	   document.getElementById("paciente").style.color = "green";
        }
     });     

     $("#institucion").change(function(){
         
     	 //console.log(document.getElementById("institucion").value)

	      var geojson = {
			  type: 'FeatureCollection',
			  features: [{
			    type: 'Feature',
			    geometry: {
			      type: 'Point',
			      coordinates: [-77.032, 38.913]
			    },
			    properties: {
			      title: 'Mapbox',
			      description: 'Washington, D.C.'
			    }
			  },
			  {
			    type: 'Feature',
			    geometry: {
			      type: 'Point',
			      coordinates: [-58.3772300, -34.6083]
			    },
			    properties: {
			      title: 'Mapbox',
			      description: 'San Francisco, California'
			    }
			  }]
			};


			// add markers to map
			geojson.features.forEach(function(marker) {

			  // create a HTML element for each feature
			  var el = document.createElement('div');
			  el.className = 'marker';

			  // make a marker for each feature and add to the map
			  new mapboxgl.Marker(el)
			  .setLngLat(marker.geometry.coordinates)
			  .addTo(map);
			});

     	 /*

     	          $.ajax({
               	    url: "./controller/ScriptController.php", 
 					type: "get",
               	    dataType: "json",
               	    data: {
               	      act: "mapaInstitucion", 	
               	      valor: document.getElementById("institucion").value,
               	    },	
					contentType: "application/json; charset=utf-8",
					success: function(data) {
                        // datos de respuesta
                        document.getElementById("mapIns").src = data[0].mapa;
					},
					error: function() {
				        console.log("No se encontraron mapas");
				    }
				});	

		 */				

         //console.log(document.getElementById("mapIns").src = "https://wwf.org");
     });
});



