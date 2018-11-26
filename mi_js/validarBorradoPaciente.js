$(function(){
	$("#pacienteBorrado").submit(function(e){
      
      function llamarVerificacion() {    
          $.ajax({
              url: "./controller/ScriptController.php", 
              async: false,
 			  type: "get",
              dataType: "json",
              data: {
               	      act: "cantidadConsultas", 	
               	      valor: $("#idPaciente").val()
               	    },	
			        contentType: "application/json; charset=utf-8",
      				success: function(data) {
                                
                                // console.log(data.cantidad);
      						   if ( data.cantidad > 0 ) 
      						   {
									       alert("El Paciente tiene consultas asociadas en el sistema");
                         e.preventDefault();
      						   }
      				},
        			error: function() {
        				        console.log("No se datos");
					}
		  });

      }
      
      // llamar a consulta AJAX
      llamarVerificacion();      

	});
});	