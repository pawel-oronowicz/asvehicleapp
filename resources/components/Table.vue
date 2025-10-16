<template>
    <v-data-table
        :headers="headers"
        :items="vehicles"
        :items-per-page="5"
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar
                flat
            >
                <v-toolbar-title>Vehicles</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                    <v-dialog
                        v-model="dialog"
                        max-width="600px"
                    >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                        color="primary"
                        dark
                        class="mb-2"
                        v-bind="attrs"
                        v-on="on"
                        >
                        New Vehicle
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                        <span class="text-h5">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                        <v-container>
                            <v-row>
                            <v-col
                                cols="16"
                                sm="8"
                                md="6"
                            >
                                <v-text-field
                                v-model="editedItem.registrationNumber"
                                label="Registration Number"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="16"
                                sm="8"
                                md="6"
                            >
                                <v-text-field
                                v-model="editedItem.brand"
                                label="Brand"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="16"
                                sm="8"
                                md="6"
                            >
                                <v-text-field
                                v-model="editedItem.model"
                                label="Model"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="16"
                                sm="8"
                                md="6"
                            >
                                <v-select
                                    :items="['Bus', 'Passenger', 'Truck']"
                                    v-model="editedItem.type"
                                    label="Type"
                                ></v-select>
                            </v-col>
                            </v-row>
                        </v-container>
                        </v-card-text>

                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="close"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="save"
                        >
                            Save
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                        <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                        <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>

        <template v-slot:item.createdAt="{ item }">
            {{ formatDateTime(item.createdAt) }}
        </template>

        <template v-slot:item.updatedAt="{ item }">
            {{ formatDateTime(item.updatedAt) }}
        </template>

        <template v-slot:item.actions="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                small
                @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>
            </template>
            <template v-slot:no-data>
            <v-btn
                color="primary"
                @click="initialize"
            >
                Reset
            </v-btn>
        </template>
    </v-data-table>
</template>

<script>
    import vehicleService from '../services/vehicleService';

    export default {
        data: () => {
            return {
                dialog: false,
                dialogDelete: false,
                headers: [
                    { text: 'No.', value: 'id' },
                    { text: 'Registration Number', value: 'registrationNumber' },
                    { text: 'Brand', value: 'brand' },
                    { text: 'Model', value: 'model' },
                    { text: 'Vehicle Type', value: 'type' },
                    { text: 'Creation Date', value: 'createdAt' },
                    { text: 'Modification Date', value: 'updatedAt' },
                    { text: 'Actions', value: 'actions', sortable: false },
                ],
                vehicles: [],
                editedIndex: -1,
                editedItem: {
                    registrationNumber: '',
                    brand: '',
                    model: '',
                    type: '',
                },
                defaultItem: {
                    registrationNumber: '',
                    brand: '',
                    model: '',
                    type: '',
                },
            }
        },
        mounted () {
            this.getAllVehicles()
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Vehicle' : 'Edit Vehicle'
            },
        },
        watch: {
            dialog (val) {
                val || this.close()
            },
            dialogDelete (val) {
                val || this.closeDelete()
            },
        },
        methods: {
            async getAllVehicles() {
                vehicleService.list()
                .then(response => (this.vehicles = response.data.results))
                .catch(err => console.error(err))
            },

            async editItem (item) {
                this.editedIndex = this.vehicles.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                this.editedIndex = this.vehicles.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialogDelete = true
            },

            async deleteItemConfirm () {
                try {
                    await vehicleService.delete(this.editedItem.id)
                    await this.getAllVehicles();
                    
                    this.closeDelete()
                } catch (error) {
                    console.error('Error deleting vehicle:', error)
                    alert('Failed to delete vehicle. Please try again.')

                    this.closeDelete()
                }
            },

            close () {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            closeDelete () {
                this.dialogDelete = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            async save () {
                try {
                    await vehicleService.save(this.editedItem)
                    await this.getAllVehicles();

                    this.close()
                } catch (error) {
                    console.error('Error saving vehicle:', error)
                    alert('Failed to save vehicle. Please try again.')

                    this.close()
                }
            },

            formatDateTime(datetime) {
                return datetime ? datetime.slice(0, 16) : '';
            }
        }
    }
</script>
