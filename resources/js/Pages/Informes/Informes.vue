<script>
export default {
    name: 'Informes',
    data() {
        return {
            selectedUser: null,
            fechaInicial: null,
            fechaFinal: null,
            usuarios: [],
            columnas: [
                { title: "Fecha", field: "fecha" },
                { title: "Horas", field: "rango" },
                { title: "Ubicación", field: "ubicacion" },
                { title: "IP", field: "ip" },
                { title: "Total Horas", field: "total" },
            ],
        };
    },
    methods: {
        //funcion asincrona para cargar los usuarios seleccionables en el select
        async cargarUsuarios() {
            try {
                const response = await fetch('/usuarios-empresa');
                const data = await response.json();
                this.usuarios = data;
            } catch (error) {
                //console.error('Error al obtener los usuarios:', error);
                this.mostrarAlerta('error', 'No se pueden obtener los usuarios');
            }
        },
        //funcion para obtener el dia de hoy
        obtenerFechaHoy() {
            const hoy = new Date();
            const yyyy = hoy.getFullYear();
            let mm = hoy.getMonth() + 1;
            let dd = hoy.getDate();

            if (mm < 10) {
                mm = `0${mm}`;
            }

            if (dd < 10) {
                dd = `0${dd}`;
            }

            return `${yyyy}-${mm}-${dd}`;
        },
        //funcion asincrona para mostrar el informe
        async consultarInformes() {

            const fechaHoy = this.obtenerFechaHoy();

            if (this.fechaInicial > fechaHoy || this.fechaFinal > fechaHoy) {

                //console.error('Las fechas no pueden ser posteriores a hoy.');
                this.mostrarAlerta('error', 'Las fechas deben ser anteriores o iguales al dia de hoy');
                return;
            }

            try {
                const response = await fetch(`/registro-usuario/${this.selectedUser}/${this.fechaInicial}/${this.fechaFinal}`);

                if (response.ok) {
                    const data = await response.json();

                    var tabla = document.getElementById('informe');
                    this.renderizarTabla(data, tabla, this.columnas);
                } else {
                    //console.error('Error al obtener los registros:', response.statusText);
                    this.mostrarAlerta('error', 'No hay respuesta del servidor');

                }
            } catch (error) {
                //console.error('Error en la solicitud:', error);
                this.mostrarAlerta('error', 'No hay datos de ese usuario');
            }
        },
        //funcion para pintar la tabla, recibe tres parametros, un array de datos a mostrar, un array con el nombre de las columnas y el div donde estara la tabla
        renderizarTabla(dato, div, columns) {

            var tabla = new Tabulator(div, {
                data: dato,
                layout: "fitData",
                pagination: "local",
                movableColumns: false,
                paginationSize: 10,
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

            this.generarBotones(tabla, dato);


        },
        //funcion para generar una alerta, recibe dos parametros, tipo de alerta y el mensaje que debe aparecer
        mostrarAlerta (tipo, mensaje) {
            return Swal.fire({
                icon: tipo,
                title: mensaje,
                showConfirmButton: false,
                timer: 1500,
            });
        },
        //funcion para generar los botones de descarga, recibe la tabla a descargar y los datos para asignar los nombres a los documentos
        generarBotones(tabla, dato) {
            //obtenemos el div de los botones
            var botonesDiv = document.getElementById("botones");

            //limpiamos el contenido del div
            botonesDiv.innerHTML = '';

            //funcion para crear un boton asignandole los estilos y el evento
            function crearBoton(texto, formato, nombreArchivo, opciones, clases) {
                var button = document.createElement("button");
                button.innerHTML = texto;
                botonesDiv.appendChild(button);

                //dividimos las clases y las añadimos una por una
                if (clases) {
                    var clasesArray = clases.split(' ');
                    clasesArray.forEach(function (clase) {
                        button.classList.add(clase);
                    });
                }

                button.addEventListener("click", function () {
                    tabla.download(formato, nombreArchivo, opciones);
                });
            }

            //variable con el nombre del usuario al que pertenece el documento
            var nombre = dato[0].usuario.name;

            //creamos los botones
            crearBoton("Descargar CSV", "csv", "registro_" + nombre + ".csv", null, "bg-green-500 hover:bg-green-400 text-black font-bold py-1 px-2 border-b-4 border-green-700 hover:border-green-500 rounded");
            crearBoton("Descargar JSON", "json", "registro_" + nombre + ".json", null, "bg-green-500 hover:bg-green-400 text-black font-bold py-1 px-2 border-b-4 border-green-700 hover:border-green-500 rounded");
            crearBoton("Descargar XLSX", "xlsx", "registro_" + nombre + ".xlsx", { sheetName: "Registros: " + nombre }, "bg-green-500 hover:bg-green-400 text-black font-bold py-1 px-2 border-b-4 border-green-700 hover:border-green-500 rounded");
            crearBoton("Descargar PDF", "pdf", "registro_" + nombre + ".pdf", { orientation: "portrait", title: "Registros: " + nombre }, "bg-green-500 hover:bg-green-400 text-black font-bold py-1 px-2 border-b-4 border-green-700 hover:border-green-500 rounded");
            crearBoton("Descargar HTML", "html", "registro_" + nombre + ".html", { style: true }, "bg-green-500 hover:bg-green-400 text-black font-bold py-1 px-2 border-b-4 border-green-700 hover:border-green-500 rounded");
        }

    },
    mounted() {
        this.cargarUsuarios();
    },
}

</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import 'tabulator-tables/dist/css/tabulator_bootstrap5.min.css';
import { TabulatorFull as Tabulator } from 'tabulator-tables';


</script>

<template>
    <AppLayout title="Informes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Informes
            </h2>
        </template>
        <div class="sm:flex flex-grow sm:items-center sm:justify-center m-1">
            <div class="rounded-xl p-4 m-2 bg-white border-b border-gray-200 shadow-lg flex-grow">
                <select v-model="selectedUser"
                    class="w-full mb-3 rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option :value="null" selected disabled>Selecciona un usuario</option>
                    <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
                        {{ usuario.name }}
                    </option>
                </select>

                <div class="flex mb-3">
                    <input v-model="fechaInicial" type="date" :max="obtenerFechaHoy()"
                        class="flex-grow mr-2 rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <input v-model="fechaFinal" type="date" :disabled="!fechaInicial" :min="fechaInicial"
                        :max="obtenerFechaHoy()"
                        class="flex-grow ml-2 rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <button @click="consultarInformes" :disabled="!selectedUser || !fechaInicial || !fechaFinal"
                    class="w-full bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded"
                    :style="{ cursor: (!selectedUser || !fechaInicial || !fechaFinal) ? 'not-allowed' : 'pointer' }">
                    Consultar
                </button>
            </div>

            <div id="contenedor" class="rounded-xl p-4 m-2 bg-white border-b border-gray-200 shadow-lg">
                <div id="informe">
                </div>
                <div class="flex flex-wrap justify-center gap-4 my-3" id="botones">
                </div>
            </div>
        </div>

    </AppLayout>
</template>