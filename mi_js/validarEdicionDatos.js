$(function(){
	$("#pacienteForm").submit(function(){

         // recuperar campos input principale para validar    
         var nombre=$("#nombre").val();  
         var apellido=$("#apellido").val(); 
         var fechaNacimineto=$("#fechaNacimineto").val(); 
         var lugarNacimineto=$("#lugarNacimineto").val(); 
         var domicilio=$("#domicilio").val(); 
         var tieneDoc=$("#tieneDoc").val(); 
         var tipoDocumento=$("#tipoDocumento").val(); 
         var nroDocumento=$("#nroDocumento").val(); 
         var nroHistClinica=$("#nroHistClinica").val(); 
         var nroCarpeta=$("#nroCarpeta").val(); 
         var telefono=$("#telefono").val(); 

         // Validar valor no nulo y longitud maxima de nombre   
       	 if ((nombre.length == 0 ) || (nombre.length > 20 )) {
  		     alert('Nombre no debe ser vacio ni superar los 20 caracteres');
	         return false;				
         }		

         // Validar valor no nulo y longitud maxima de apellido  
         if ((apellido.length == 0 ) || (apellido.length > 20 )) {
  		     alert('Apellido no debe ser vacio ni superar los 20 caracteres');
	         return false;				
         }	
        
         // Validar valor no nulo para fecha
         if (fechaNacimineto.length == 0 ) {
  		      alert('La fecha de Nacimiento no puede ser vacia');
	          return false;				
         }	
        
         // Validar Lugar de nacimiento y longitud maxima
         if ((lugarNacimineto.length == 0 ) || (nombre.length > 70 )) {
  		      alert('El lugar Nacimiento no puede ser vacio ni superar los 70 caracteres');
	          return false;				
         }	

         // Validar Domicilio y longitud maxima 
         if ((domicilio.length == 0) || (domicilio.length > 70)) {
        	  alert('El domicilio no puede ser vacio ni superar los 70 caracteres');
	          return false;	
         }

         // Controlar valor correcto para Tiene documento
         if (( tieneDoc != 1 ) && (tieneDoc != 0 )) {
        	  alert('Valor invalido para la opcion tiene Documentos');
	          return false;	
         }

         // Si tiene documento 
         if (tieneDoc == 1 )
         {
            // Tipo y Nro no pueden estar vacios  	 
            if ((tipoDocumento.length == 0 ) ||  (nroDocumento.length == 0))
            {
           	  alert('Debe completar Tipo y Numero Documento');
	            return false;	
            } 		
          
            // Verificar que Documento es un numero
            if (isNaN(nroDocumento))
            {
           	  alert('Numero Documento no es numerico');
	            return false;
            }
        
         }

         // Si no tiene DOC
         if (tieneDoc == 0)	
         {
        	//  No deberia completar Nro ni Tipo de documento 
            if ((tipoDocumento.length == 0 ) || (nroDocumento != 0))
            {
           	   alert('No debe completar Tipo y Numero Documento, para opcion: Sin Documento ');
	             return false;	
            } 	
         }

         //  Verificar que carpeta es numerico
         if (isNaN(nroCarpeta))
         {
           	alert('Numero Carpeta no es numerico');
	          return false;
         }

         //  Verificar que historia clinica es numerica 
         if (isNaN(nroHistClinica))
         {
           	alert('Numero Historia Clinica no es numerico');
	          return false;
         }
        
        // Vericar telenofo no supera el maximo permitido
 	      if (telefono.length > 70) 
 	      {		
           alert('Numero telefico no puede superar los 70 caracteres');
	         return false;   
 	      } 

        return confirm("Desea guardar los datos?");	

	});
});	


function tieneDocumento()
{
  
  // Obtengo la respuesta si tiene o no documento, su tipo y numero
  var respuesta = document.getElementById('tieneDoc').value;


  if (respuesta == 0)
  {  
      // Desactivar los campos y limpiar las variables
      alert('La opcion "No tiene Documento", deja al paciente sin un documento asociado');       

  }
}