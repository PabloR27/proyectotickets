
let form = document.querySelector("form");

const mensajeVacio = "Este campo no puede estar vacio";

let datosCorrectos = true;

let usuarioInput = document.querySelector("#username");
let emailInput = document.querySelector("#email");


function crearMsgError(mensaje, father) {
    let elem = document.createElement("P");

    elem.textContent = mensaje;
    elem.classList.add("error");
    father.insertAdjacentElement("afterend", elem);
}


function comprobarUsuario(e) {
    if (usuarioInput.value === "") {
        crearMsgError(mensajeVacio, usuarioInput);
        return datosCorrectos = false;
    }
}
function comprobarEmail(e) {
    if (emailInput.value === "") {
        
        crearMsgError(mensajeVacio, emailInput);
        return datosCorrectos = false;
    }
}
form.addEventListener("submit", e => {
    mensajesError = document.querySelectorAll(".error");
    for (let i = 0; i < mensajesError.length; i++) {
        mensajesError[i].remove();
    }
    e.preventDefault();
    datosCorrectos = true;

    comprobarEmail(e);
    comprobarUsuario(e);



    if (datosCorrectos) {
        form.submit();
    }
});