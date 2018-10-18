
function seleccionarInfoPartido() 
{ 

   // buscar region sanitaria en base al partido seleccionado

   var xmlhttp;
   xmlhttp = new XMLHttpRequest();
   
   xmlhttp.onreadystatechange = function() {
   if (xmlhttp.readyState == 4 )
   		{
   		   // obtener el Response
   		   var myObj  = JSON.parse(xmlhttp.responseText);
   		   
   		   // actualizar los datos de region sanitaria
           var region = myObj['region'];   
   		   document.getElementById('regionSanitaria').value = region["1"];

           // actualizar los datos de localidad
           var selectLoc = document.getElementsByName('localidad')[0];
           var localidad = myObj['localidad'];

           // Inicializo y agrego la option de vacio
           document.getElementById('localidad').options.length = 0;
           
           var optionNula   = document.createElement('option');
		   optionNula.value = 0;
		   optionNula.text  = "vacio";
		   selectLoc.add(optionNula);

           // Agrego las localidades por partido
           for (value in localidad) {
			   var option   = document.createElement('option');
			   option.value = value;
			   option.text  = localidad[value];
			   selectLoc.add(option);
		   }

   		}
   }	
   
   // realizar la request para el partido
   var valor = document.getElementById('partido').value;
   xmlhttp.open("GET","./model/pedirRegionSanitaria.php?id="+valor,true);
   xmlhttp.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
   xmlhttp.send();
      
}


function tieneDocumento()
{
  
  // Obtengo la respuesta si tiene o no documento, su tipo y numero
  var respuesta = document.getElementById('tieneDoc').value;
  var nroDoc    = document.getElementById('nroDocumento');
  var tipoDoc   = document.getElementById('tipoDocumento');

  if (respuesta == 0)
  {  
  	  // Desactivar los campos y limpiar las variables

  	  // para nro documento
      nroDoc.readOnly  = true;
      nroDoc.value     = "";
      nroDoc.required  = !nroDoc.required;

      // tipo de documento 
      tipoDoc.disabled = !tipoDoc.disabled;
      tipoDoc.value    = "";

  }
  else 
  {
  	 // se vuelve a habilitar las opciones de edicion 
     nroDoc.readOnly  = false;
     nroDoc.required  = !nroDoc.required;
     tipoDoc.disabled = !tipoDoc.disabled;
  } 	

}




