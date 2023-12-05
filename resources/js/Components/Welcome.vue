<script>

</script>


<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import 'tabulator-tables/dist/css/tabulator_bootstrap5.min.css';
import {TabulatorFull as Tabulator} from 'tabulator-tables';
const registros = ref([]);



var fechaActual = new Date();
var mes = fechaActual.getMonth() + 1;
var anio = fechaActual.getFullYear();
var data = {};

//funcion para obtener el primer dia de la semana y el ultimo dia de la semana actual, los retorna separados por |
function obtenerRangoSemana() {

    const hoy = new Date();
    const diaSemana = hoy.getDay();

    //restar días según el día de la semana actual para obtener el lunes
    const inicioSemana = new Date(hoy);
    inicioSemana.setDate(hoy.getDate() - diaSemana + (diaSemana === 0 ? -6 : 1));

    //sumar días para obtener el final de la semana (domingo)
    const finSemana = new Date(inicioSemana);
    finSemana.setDate(inicioSemana.getDate() + 6);

    //formatear las fechas como cadenas 'YYYY-MM-DD'
    const inicioSemanaFormateado = inicioSemana.toISOString().split('T')[0];
    const finSemanaFormateado = finSemana.toISOString().split('T')[0];

    return `${inicioSemanaFormateado} | ${finSemanaFormateado}`;
}

//funcion para obtener el primer dia del mes y el ultimo dia del mes actual, los retorna separados por |
function obtenerRangoMes() {
    const hoy = new Date();
    const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    const ultimoDiaMes = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);

    const primerDiaMesFormateado = obtenerFormatoFecha(primerDiaMes);
    const ultimoDiaMesFormateado = obtenerFormatoFecha(ultimoDiaMes);

    return `${primerDiaMesFormateado} | ${ultimoDiaMesFormateado}`;
}

//funcion para obtener el formato correcto de una fecha
const obtenerFormatoFecha = (fecha) => {
        const año = fecha.getFullYear();
        const mes = fecha.getMonth() + 1; 
        const dia = fecha.getDate();

       
        const formatoMes = mes < 10 ? `0${mes}` : mes;
        const formatoDia = dia < 10 ? `0${dia}` : dia;

        return `${año}-${formatoMes}-${formatoDia}`;
    };

//funcion para obtener el primer dia del año y el ultimo dia del año actua, los retorna separados por |
function obtenerRangoAnio() {
    const hoy = new Date();
    const primerDiaAnio = new Date(hoy.getFullYear(), 0, 1);
    const ultimoDiaAnio = new Date(hoy.getFullYear() + 1, 0, 0);

    const primerDiaAnioFormateado = obtenerFormatoFecha(primerDiaAnio);
    const ultimoDiaAnioFormateado = obtenerFormatoFecha(ultimoDiaAnio);

    return `${primerDiaAnioFormateado} | ${ultimoDiaAnioFormateado}`;
}

//función para sumar tiempos en formato 'HH:mm:ss', recibe los tiempos y retorna la suma
function sumarTiempos(tiempo1, tiempo2) {
    const [h1, m1, s1] = tiempo1.split(':').map(Number);
    const [h2, m2, s2] = tiempo2.split(':').map(Number);

    let segundosTotales = s1 + s2 + 60 * (m1 + m2) + 3600 * (h1 + h2);

    const horas = Math.floor(segundosTotales / 3600);
    segundosTotales %= 3600;
    const minutos = Math.floor(segundosTotales / 60);
    const segundos = segundosTotales % 60;

    return `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;
}

//función para sumar el array de tiempos
function sumarTiemposArray(tiemposArray) {
    return tiemposArray.reduce((acumulador, registro) => sumarTiempos(acumulador, registro.total), '00:00:00');
}

//funcion para mostrar el tiempo acumulado de la semana actual
const mostrarSemana = async () => {
    var rangoSemana = obtenerRangoSemana();

    var tiempo = await cargarRangoTotal(rangoSemana);

    var semana = document.getElementById('horasSemana');
    semana.innerHTML = `
            <div class="bg-white-500 text-black lg:w-1/3 p-2 text-sm leading-relaxed flex items-center justify-center flex-wrap flex-col">
                <p class="text-lg font-bold">Semana</p>
                <p class="text-3xl bg-black text-white rounded-xl">${tiempo}</p>
            </div>
        `;
}

//funcion para mostrar el tiempo acumulado del mes actual
const mostrarMes = async () => {
    var rangoMes = obtenerRangoMes();
    
    var tiempo = await cargarRangoTotal(rangoMes);
    
    var mes = document.getElementById('horasMes');
    mes.innerHTML = `
        <div class="bg-white-500 text-black lg:w-1/3 p-2 text-sm leading-relaxed flex items-center justify-center flex-wrap flex-col">
            <p class="text-lg font-bold">Mes</p>
            <p class="text-3xl bg-black text-white rounded-xl">${tiempo}</p>
        </div>
    `;
}

//funcion para mostrar el tiempo acumulado del mes actual
const mostrarAnio = async () => {
    var rangoAnio = obtenerRangoAnio();
    
    var tiempo = await cargarRangoTotal(rangoAnio);

    var anio = document.getElementById('horasAnio');
    anio.innerHTML = `
        <div class="text-black lg:w-1/3 p-2 text-sm leading-relaxed flex items-center justify-center flex-wrap flex-col">
            <p class="text-lg font-bold">Año</p>
            <p class="text-3xl bg-black text-white rounded-xl">${tiempo}</p>
        </div>
    `;
}

//funcion para mostrar la suma de tiempos de un rango
const cargarRangoTotal = async (rango) => {
    try {

        const inicioSemana = rango.split(' | ')[0];
        const finSemana = rango.split(' | ')[1];



        const response = await fetch(`/horas-rango/${inicioSemana}/${finSemana}`);
        const data = await response.json();
        if(data){
            const tiempoTotal = sumarTiemposArray(data);

            return tiempoTotal;
        } else{
            return '00:00:00';
        }
    } catch (error) {
        console.error('Error al obtener los registros:', error);
        return '00:00:00';
    }
}



//funcion que solicita al servidor los datos del registro del mes actual
const cargarMes = async () => {

    try {
        const response = await fetch(`/mostrar-mes/${mes}/${anio}`);
        data = await response.json();
        var mensual = document.getElementById('mensual');
        const columnas = [
            { title: "Fecha", field: "fecha" },
            { title: "Horas", field: "rango" },
            { title: "Ubicación", field: "ubicacion" },
            { title: "IP", field: "ip" },
            { title: "Total Horas", field: "total" },
        ];
        renderizarTabla(data, mensual, columnas);

    } catch (error) {
        console.error('Error al obtener los registros:', error);
    }
};


//funcion para crear las tablas con tabulator, recibe los datos, el div objetivo y el formato de columnas
const renderizarTabla = (dato, div, columns) => {

    new Tabulator(div, {
        data: dato,
        layout: "fitData",
        pagination: "local",
        movableColumns: false,
        paginationSize: 1,
        columns: columns,
        locale: true,
        langs: {
            "es-es": {
                "pagination": {
                    "page_size": "Mostrar",
                    "page_title": "Mostrar Página",
                    "first": "<<",
                    "first_title": "<<",
                    "last": ">>",
                    "last_title": ">>",
                    "prev": "<",
                    "prev_title": "<",
                    "next": ">",
                    "next_title": ">",
                    "all": "Todo",
                    "counter": {
                        "showing": "Mostrando",
                        "of": "de",
                        "rows": "filas",
                        "pages": "páginas",
                    }
                }
            }
        },
    });
};

//una vez cargados los elementos, llamamos a las funciones para mostrar los datos
onMounted(() => {
    cargarMes();
    mostrarSemana();
    mostrarMes();
    mostrarAnio();
});
</script>

<template>
    <div>
        <div class="p-1 lg:p-1 bg-white border-b border-gray-200 flex items-center justify-center flex-wrap shadow-lg ">
            <div class="flex items-center ">
                <h1 class="text-3xl font-medium text-gray-900">
                    <i class="fa-solid fa-user-tie"></i> {{ $page.props.auth.user.name }} <i
                        class="fa-solid fa-user-tie"></i>
                </h1>
            </div>
            <div class="flex items-center lg:mx-8">
                <ApplicationLogo />
            </div>
            <div class="flex items-center ">
                <h1 class="text-3xl font-medium text-gray-900">
                    <i class="fa-solid fa-building"></i> {{ $page.props.auth.user.empresa.nombre }} <i
                        class="fa-solid fa-building"></i>
                </h1>
            </div>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5 p-5 lg:p-5 rounded--xl ">
            <div class="shadow-lg rounded">
                <div class="flex items-center justify-center ">
                    <h2 class="ms-2 text-xl font-semibold text-gray-900 ">
                        Tus registros del último mes
                    </h2>
                </div>
                <div class="flex items-center">
                    <div id="mensual"></div>
                </div>
            </div>

            <div class="shadow-lg rounded-xl animate__animated animate__zoomIn animate__delay-1s">
                <div class="flex items-center justify-center rounded">
                    <h2 class="ms-3 text-xl font-semibold text-gray-900">
                        Tus horas totales
                    </h2>
                </div>
                <div
                    class=" p-1 lg:p-1 bg-white border-b border-gray-200 flex flex-wrap flex-col lg:flex-row rounded-xl items-center justify-center">
                    <div class="animate__animated animate__zoomIn animate__delay-2s" id="horasSemana">

                    </div>
                    <div class="animate__animated animate__zoomIn animate__delay-3s" id="horasMes">

                    </div>
                    <div class="animate__animated animate__zoomIn animate__delay-4s" id="horasAnio">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/*Theme the Tabulator element*/
#mensual {
    background-color: #ccc;
    border: 1px solid #333;
    border-radius: 5px;
    
}

/*Theme the header*/
.tabulator-header {
    color: #000000;
}

/*Allow column header names to wrap lines*/
.tabulator-header .tabulator-col,
.tabulator-header .tabulator-col-row-handle {
    white-space: normal;
}
</style>