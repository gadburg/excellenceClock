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
        async cargarUsuarios() {
            try {
                const response = await fetch('/usuarios-empresa');
                const data = await response.json();
                this.usuarios = data;
            } catch (error) {
                console.error('Error al obtener los usuarios:', error);
            }
        },
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
        async consultarInformes() {
            // Validar si las fechas son válidas
            const fechaHoy = this.obtenerFechaHoy();

            if (this.fechaInicial > fechaHoy || this.fechaFinal > fechaHoy) {
                // Mostrar un mensaje de error o realizar alguna acción
                console.error('Las fechas no pueden ser posteriores a hoy.');
                return;
            }

            try {
                const response = await fetch(`/registro-usuario/${this.selectedUser}/${this.fechaInicial}/${this.fechaFinal}`);

                if (response.ok) {
                    const data = await response.json();
                    
                    var tabla = document.getElementById('informe');
                    this.renderizarTabla(data, tabla, this.columnas);
                } else {
                    console.error('Error al obtener los registros:', response.statusText);
                    // Puedes realizar alguna acción adicional en caso de error
                }
            } catch (error) {
                console.error('Error en la solicitud:', error);
            }
        },
        renderizarTabla(dato, div, columns) {

            new Tabulator(div, {
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
        },

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
/*const columnas = [
    { title: "Fecha", field: "fecha" },
    { title: "Horas", field: "rango" },
    { title: "Ubicación", field: "ubicacion" },
    { title: "IP", field: "ip" },
    { title: "Total Horas", field: "total" },
]*/

/*const renderizarTabla = (dato, div, columns) => {

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
};*/

</script>

<template>
    <AppLayout title="Informes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Informes
            </h2>
        </template>
        <div class="sm:flex sm:items-center sm:justify-center">
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

  <div class="rounded-xl p-4 m-2 bg-white border-b border-gray-200 shadow-lg">
    <div id="informe">
    </div>
  </div>
</div>

    </AppLayout>
</template>