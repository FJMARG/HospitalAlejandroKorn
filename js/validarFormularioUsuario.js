$(function(){
	$("#userForm").submit(function(){
		var user=$("#user").val();
		var confirmUser=$("#confirmUser").val();
		var pass=$("#pass").val();
		var confirmPass=$("#confirmPass").val();
		var nombre=$("#nombre").val();
		var apellido=$("#apellido").val();
		var email=$("#email").val();
		var confirmEmail=$("#confirmEmail").val();
		if ((user.lenght === 0)||(confirmUser.lenght === 0)||(pass.lenght === 0)||(confirmPass.lenght === 0)||(nombre.lenght === 0)||(apellido.lenght === 0)||(email.lenght === 0)||(confirmEmail.lenght === 0)){
			alert("No pueden haber campos vacios.");
			return false;			
		}
		if (user != confirmUser){
			alert("El campo de usuario no coincide con el campo de confirmacion de usuario.");
			return false;
		}
		if (pass != confirmPass){
			alert("El campo de contrasena no coincide con el campo de confirmacion de contrasena.");
			return false;
		}
		if (email != confirmEmail){
			alert("El campo de email no coincide con el campo de confirmacion de Email.");
			return false;
		}
		if (user.lenght < 6){
			alert("El nombre de usuario debe tener al menos 6 caracteres.");
			return false;
		}
		if (pass.lenght < 6){
			alert("La contrasena debe tener al menos 6 caracteres.");
			return false;
		}
		if (nombre.lenght < 3){
			alert("La longitud del nombre debe ser de al menos 3 caracteres.");
			return false;
		}
		if (apellido.lenght < 3){
			alert("La longitud del apellido debe ser de al menos 3 caracteres.");
			return false;
		}
		if(email.indexOf('@') == -1 || email.indexOf('.') == -1) {
	        alert('El email ingresado no es valido.');
	        return false;
	    }

	    return confirm ("¿Esta seguro/a?");
	});
});