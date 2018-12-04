$(function() {
     
    // toquen de acceso 
    mapboxgl.accessToken = 'pk.eyJ1IjoicHJ1c3NvMjUiLCJhIjoiY2pvbjgzazJ2MHM3dTNxbzV6MHowYXZ1bCJ9.1rAOSL_0iJrGtCl4rq6tHw';

    // Add zoom and rotation controls to the map.
    map.addControl(new mapboxgl.NavigationControl());

                  
     // logica press key paciente 
     $("#paciente").keypress(function(e){
         var keycode = (e.keyCode ? e.keyCode : e.which); // detectar el enter 
         if (keycode != '13') {
             $("#id").val(-1); // valor por defecto en caso que no se selecion un paciente
             document.getElementById("paciente").style.color = "red";
         }
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

     $("#paciente").on('change', function() {

            $.ajax({
                      url: "./controller/ScriptController.php", 
                      type: "get",
                      dataType: "json",
                      data: {
                              act: "mapaInstitucion",   
                              valor: document.getElementById("id").value
                            },  
                      contentType: "application/json; charset=utf-8",
                      success: function(data) {

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
     }); 
});



