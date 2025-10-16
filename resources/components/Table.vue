<template>
    <v-data-table
        :headers="headers"
        :items="vehicles"
        :items-per-page="5"
        class="elevation-1"
    ></v-data-table>
</template>

<script>
    import vehicleService from '../services/vehicleService';

    export default {
        data () {
            return {
                headers: [
                    { text: 'No.', value: 'id' },
                    { text: 'Registration Number', value: 'registrationNumber' },
                    { text: 'Brand', value: 'brand' },
                    { text: 'Model', value: 'model' },
                    { text: 'Vehicle Type', value: 'type' },
                    { text: 'Creation Date', value: 'createdAt' },
                    { text: 'Modification Date', value: 'updatedAt' },
                    { text: 'Actions', value: 'iron', sortable: false },
                ],
                vehicles: []
            }
        },
        mounted () {
            this.getAllVehicles()
        },
        methods: {
            getAllVehicles() {
                vehicleService.list()
                .then(response => (this.vehicles = response.data.results))
                .catch(err => console.error(err))
            }
        }
    }
</script>
