var paso = 1;
const pi = 1;
const pf = 3;
var idseleccionados = [];

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
};

document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    tabs(); // cambia la session que se presiona
    mostrarSeccion(paso);
    botononesPaginador(paso);
    paginaSiguiente();
    paginaAnterior();
    consultarAPI();  // consulta l API en el backend de PHP
    nombreCliente(); //agrega el nombre al objeto cita
    seleccionarFecha(); //agrega la fecha al objeto cita y hora
    mostrarResumen();
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            paso = e.target.dataset.paso;
            mostrarSeccion(paso);
            botononesPaginador(paso);
        });

    });
}
function mostrarSeccion(p) {
    // let botonseleccionado=`#boton-${p}`;
    let pasoSelector = `#paso-${p}`;
    let tabseleccionado = `[data-paso="${p}"]`;  // seleccion por atributo

    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior)
        seccionAnterior.classList.remove('mostrar');
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior)
        tabAnterior.classList.remove('actual');

    const tab = document.querySelector(tabseleccionado);     /// seleccion por atributo
    tab.classList.add('actual');
    botononesPaginador(p);
    if (p == 3) {
        mostrarResumen();
    }
}

function botononesPaginador(p) {
    const pa = document.querySelector("#anterior");
    const ps = document.querySelector("#siguiente");

    if (p == 1) {
        pa.classList.add('ocultar');
        ps.classList.remove('ocultar');
    } else if (p == 3) {
        ps.classList.add('ocultar');
        pa.classList.remove('ocultar');
    } else {
        ps.classList.remove('ocultar');
        pa.classList.remove('ocultar');
    }
}

function paginaAnterior() {
    const pa = document.querySelector('#anterior');
    pa.addEventListener('click', function () {
        if (paso <= pi) {
            return;
        } else paso--;
        mostrarSeccion(paso);
    });
}

function paginaSiguiente() {
    const ps = document.querySelector('#siguiente');
    ps.addEventListener('click', function () {
        if (paso >= pf) {
            return;
        } else paso++;
        mostrarSeccion(paso);
    });
}

async function consultarAPI() {
    try {

        const url = `${location.origin}/api/servicios`;
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;

        servicioDiv.onclick = function () {

            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicio').appendChild(servicioDiv);

    });
}
function seleccionarServicio(clicked) {
    const { servicios } = cita;
    const { id } = clicked;

    let idsel = `[data-id-servicio="${id}"]`;
    const divServicio = document.querySelector(idsel);
    // comprobar si el servicio ya fue agregado al arreglo
    if (servicios.some(agregado => agregado.id === id)) {
        divServicio.classList.remove('seleccionado');
        cita.servicios = servicios.filter(agregado => agregado.id != id);
    }
    else {
        cita.servicios = [...servicios, clicked];
        divServicio.classList.add('seleccionado');
    }
}
function nombreCliente() {
    cita.nombre = txtnombre.value;
    cita.id = idCliente.value;
}
function seleccionarFecha() {
    txtfecha.onchange = function (e) {
        let dia = new Date(e.target.value).getUTCDay();
        if ([0, 6].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('error', 'Fines de semana esta cerrado', '.errorPaso2');
        } else {
            cita.fecha = e.target.value;
        }
    };
    txthora.onchange = function (e) {
        let horacita = e.target.value.split(":")[0];
        if ((horacita < 10 || horacita > 18) || (horacita == 12)) {
            mostrarAlerta('error', 'El Salon se encuenta cerrado a esa Hora. Hora No valida', '.errorPaso2');
        } else {
            cita.hora = e.target.value;
        }
    };
}
function mostrarAlerta(tipo, mensaje, elemento, desaparece = true) {

    var alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    var alerta = document.createElement('DIV');
    alerta.id = "alertafecha";
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    var html = document.querySelector(elemento);
    html.appendChild(alerta);
    if (desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}
function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    if ((Object.values(cita).includes("")) || Object.values(cita.servicios).length == 0) {
        mostrarAlerta('error', 'FALTAN DATOS', '.contenido-resumen', false);
        return;
    }
    // Scripting
    const { nombre, fecha, hora, servicios } = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span>${nombre}`;

    let fechaObj = new Date(fecha);
    fechaObj.setMinutes(fechaObj.getMinutes() + fechaObj.getTimezoneOffset());  // se corrige el problema de cambio de horario
    let year = fechaObj.getFullYear();
    let mes = fechaObj.getMonth();
    let dia = fechaObj.getDate() + 1;


    let opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    let fechadia = fechaObj.toLocaleDateString('es-MX', opciones);
    let fechaFormateada = fechaObj.toLocaleDateString('es-MX', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span>${hora} horas`;

    //heading para titulo de resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';

    resumen.appendChild(headingServicios);

    servicios.forEach(servicio => {
        // const{id, precio, nombre}  = servicio;
        // Destruction para acceder a los objetos del arreglo..
        // caso contrario usar servicio.variable;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');
        const textoServicio = document.createElement('P');
        textoServicio.textContent = servicio.nombre;
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $ ${servicio.precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        resumen.appendChild(contenedorServicio);
    });

    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingCita);

    let botonReservar = document.createElement('Button');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = function () {
        reservarCita();
    };

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}
async function reservarCita() {
    const { id, fecha, hora, servicios } = cita;
    const idServicios = servicios.map(servicio => servicio.id);

    const datos = new FormData();
    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);
  

    try {
        const url = `${location.origin}/api/citas`;
        const rpta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await rpta.json();

        if (resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita creada satisfactoriamente',
                text: 'Tu cita, se creo con exito, recordar incluirla en su agenda..!',
                // footer: '<a href="">Why do I have this issue?</a>'
                boton: 'OK'
            }).then(() => {
                setTimeout(() => {
                    window.location.reload();
                }, 5000);
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error inesperado al Guardar la Cita'
        })
    }

}
