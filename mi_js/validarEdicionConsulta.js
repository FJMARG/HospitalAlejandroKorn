$(function(){
	$("#consultaForm").submit(function(){

		 // recuperar campos input principale para validar    
     var paciente=$("#paciente").val();  
     var id_paciente=$("#id").val(); 
     var fechaNacimineto=$("#fechaConsulta").val(); 
		 var motivo=$("#motivo").val();
		 var internacion=$("#internacion").val();
		 var diagnostico=$("#diagnostico").val();
		 var observaciones=$("#observaciones").val();


		 // Validar los campos obligatorios
       	 if (paciente.length == 0 ) {
  		     alert('Debe completar el nombre del paciente');
	         return false;				
         }		

         if ((id_paciente.length == 0 ) || (id_paciente== -1 )){
  		     alert('Verifique seleccionar el paciente desde la lista de sugerencias');
	         return false;				
         }

         if (fechaNacimineto.length == 0 ) {
  		     alert('Fecha de Fecha de Nacimiento no puede estar vacia');
	         return false;				
         }

         if (motivo.length == 0 ) {
  		     alert('El motivo de la consulta no debe ser vacio');
	         return false;				
         }

         if (internacion.length == 0 ) {
  		     alert('El campo internacion no debe estar vacio');
	         return false;				
         }


         if (diagnostico.length == 0 ) {
  		     alert('El diagnostico no debe estar vacio');
	         return false;				
         }

         if (diagnostico.observaciones == 0 ) {
  		     alert('Las observaciones no debe estar vacio');
	         return false;		
         }

         return confirm("Se guardaran los cambios sobre la consulta. Desea continunar ?");	

	});
});
