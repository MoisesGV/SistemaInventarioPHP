;
$("#formAcceso").on('submit', function(e){

	//EJECUTAMOS EL preventDefault PARA QUE AL PULSAR EL BOTON ENVIAR NO EJECUTE LA FUNTION ACTION 
	e.preventDefault();

	usuarioLogin=$("#usuarioLogin").val();
	usuarioClave=$("#usuarioClave").val();

	$.post("../ajax/usuario.php?op=verificar", 
		{"login":usuarioLogin, "clave":usuarioClave},
		function(data){
			if (data!="null") {
				$(location).attr("href","escritorio.php");
				console.log(data)
			}
			else{
				bootbox.alert("Usuario y/o Contraseña Incorrectos");
			}
		})

})