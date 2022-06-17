
let form = document.querySelector("form");

const mensajeVacio = "Este campo no puede estar vacio";

let datosCorrectos = true;

let mensajeInput = document.querySelector("#descripcion");
let tituloInput = document.querySelector("#titulo");


function crearMsgError(mensaje, father) {
    let elem = document.createElement("P");

    elem.textContent = mensaje;
    elem.classList.add("error");
    father.insertAdjacentElement("afterend", elem);
}


function comprobarMensaje(e) {
    if (mensajeInput.value === "") {
        crearMsgError(mensajeVacio, mensajeInput);
        return datosCorrectos = false;
    }
}
function comprobarTitulo(e) {
    if (tituloInput.value === "") {
        let padreTitulo=tituloInput.parentNode;
        crearMsgError(mensajeVacio, padreTitulo);
        return datosCorrectos = false;
    }
}
form.addEventListener("submit", e => {
    mensajesError = document.querySelectorAll(".error-msg");
    for (let i = 0; i < mensajesError.length; i++) {
        mensajesError[i].remove();
    }
    e.preventDefault();
    datosCorrectos = true;

    comprobarTitulo(e);
    comprobarMensaje(e);



    if (datosCorrectos) {
        form.submit();
    }
});