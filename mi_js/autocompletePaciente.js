// AJAX call for autocomplete 
$(document).ready(function(){
  $("#search-box").keyup(function(){
    $.ajax({
    type: "POST",
    url:  "./model/buscarNombrePaciente.php",
    data: 'paciente='+$(this).val(),
    beforeSend: function(){
      $("#search-box").css("background","#FFF url(./images/LoaderIcon.gif) no-repeat 150px");
    },
    success: function(data){
      $("#suggesstion-box").show();
      $("#suggesstion-box").html(data);
      $("#search-box").css("background","#FFF");
    }
    });
  });
});


//To select country name
function selectPaciente(nombre,apellido,tipo,numero) 
{
  $("#search-box").val(apellido);

  // completar datos
  $("#nombre").val(nombre);
  $("#apellido").val(apellido);
  $("#tipoDocumento").val(tipo);
  $("#nroDocumento").val(numero);

  // esconder lista de 
  $("#suggesstion-box").hide();

}