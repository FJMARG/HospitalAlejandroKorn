$(function(){
	$("#searchForm").submit(function(){

        // recuperar campos input     
		var nombre=$("#nombrePaciente").val();
		var apellido=$("#apellidoPaciente").val();
		var tpDoc=$("#tipoDocumento").val();
		var nroDoc=$("#numeroDocumento").val();
		var histCli=$("#numeroHistoriaClinica").val();

        
        // Verificar que al menos uno no este vacio para realizar la busqueda
		if ((nombre.length == 0 ) && (apellido.length == 0 ) && (tpDoc.length == 0 ) 
		&& (nroDoc.length == 0 ) && (histCli.length == 0 ))
		{
		   	alert('Debe completar al menos un campo');
	        return false;		
		}		
	});
});		