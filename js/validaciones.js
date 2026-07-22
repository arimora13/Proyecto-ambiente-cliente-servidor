// js/validaciones.js
// Validaciones basicas del lado del cliente para los formularios de Huertica.

function validarLogin() {
    var correo = document.getElementById('correo').value.trim();
    var clave  = document.getElementById('clave').value.trim();

    if (correo === "" || clave === "") {
        alert("Debe completar correo y contrasena");
        return false;
    }
    if (!validarCorreo(correo)) {
        alert("El correo electronico no tiene un formato valido");
        return false;
    }
    return true;
}

function validarCorreo(correo) {
    var patron = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return patron.test(correo);
}

// Validacion generica de formulario: recibe un array de ids de campos obligatorios
function validarCamposObligatorios(idsCampos) {
    for (var i = 0; i < idsCampos.length; i++) {
        var campo = document.getElementById(idsCampos[i]);
        if (campo && campo.value.trim() === "") {
            alert("El campo " + idsCampos[i] + " es obligatorio");
            campo.focus();
            return false;
        }
    }
    return true;
}

// Validacion especifica para el formulario de usuarios
function validarFormularioUsuario() {
    var camposObligatorios = ["nombre", "apellidoPaterno", "apellidoMaterno", "correo", "idRol", "idEstado"];
    if (!validarCamposObligatorios(camposObligatorios)) return false;

    var correo = document.getElementById("correo").value.trim();
    if (!validarCorreo(correo)) {
        alert("El correo electronico no es valido");
        return false;
    }

    var claveCampo = document.getElementById("clave");
    if (claveCampo && claveCampo.value.trim() !== "" && claveCampo.value.trim().length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres");
        return false;
    }
    return true;
}

// Validacion generica para formularios de cultivo / riego / fertilizacion / plaga / reporte / alerta
function validarFormularioGeneral(idsCampos) {
    return validarCamposObligatorios(idsCampos);
}

// Confirmacion antes de eliminar un registro
function confirmarEliminar(mensaje) {
    return confirm(mensaje || "¿Esta seguro que desea eliminar este registro?");
}
