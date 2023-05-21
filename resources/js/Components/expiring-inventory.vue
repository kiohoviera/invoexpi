<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12" v-show="error">
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ errorMsg }}
                        <button type="button" @click="hide" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <div class="col-md-12" id="alert2" v-show="success">
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ successMsg }}
                        <button type="button" @click="hide" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">
                            Remove Inventory
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Serial #</label>
                            <input type="text" class="form-control" v-model="form['serial_number']" required />
                        </div>
                        <div class="form-group mb-3">
                            <label>Location</label>
                            <select class="form-control" v-model="form['location']" required>
                                <option value="Storage">Storage</option>
                                <option value="Discount">Discount</option>
                                <option value="Vegetable">Vegetable</option>
                                <option value="Meat">Meat</option>
                                <option value="Frozen">Frozen</option>
                                <option value="Spices">Spices</option>
                                <option value="Sweets">Sweets</option>
                                <option value="Beverages">Beverages</option>
                                <option value="Processed Food">Processed Food</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-danger btn-block w-100" @click.prevent="deleteSelected"><i class="bx bxs-trash"></i> Delete Record </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Expiring Inventory</h5>
                    </div>
                    <div class="card-body">
                        <div class="demo-inline-spacing mt-3">
                            <div class="list-group">
                                <a v-for="inv in data" href="javascript:void(0);" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex justify-content-between w-100">
                                        <h6 class="mb-2">{{ inv.product_name }}</h6>
                                        <small>Exp : {{ inv.expiration_date }}</small>
                                    </div>
                                    <p class="mb-1">Barcode: {{ inv.serial_number }}</p>
                                    <p class="mb-1">SKU: {{ inv.sku }}</p>
                                    <p class="mb-1">Location: {{ inv.location }}</p>
                                    <p class="mb-1">Quantity: {{ inv.quantity }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Expired Inventory</h5>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <button class="btn btn-outline-danger" @click.prevent="deleteExpired" type="button"><i class="bx bx-trash"> </i> Clear All</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="demo-inline-spacing mt-3">
                            <div class="list-group">
                                <a v-for="inv in expired" href="javascript:void(0);" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex justify-content-between w-100">
                                        <h6 class="mb-2">{{ inv.product_name }}</h6>
                                        <small>Exp : {{ inv.expiration_date }}</small>
                                    </div>
                                    <p class="mb-1">Barcode: {{ inv.serial_number }}</p>
                                    <p class="mb-1">SKU: {{ inv.sku }}</p>
                                    <p class="mb-1">Location: {{ inv.location }}</p>
                                    <p class="mb-1">Quantity: {{ inv.quantity }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import axios from "axios";
export default {
    name: "dashboard",

    data(){
        return {
            form: {
                'serial_number' : '',
                'sku' : '',
                'product_name' : '',
                'quantity' : '',
                'location' : '',
                'expiration_date' : '',
            },
            data : [],
            expired: [],
            logId : null,
            error: false,
            success: false,
            errorMsg : '',
            successMsg: ''
        }
    },

    created() {
        this.updateScanLogs();
        this.getScanLogs();
    },

    mounted() {
        this.getExpiringInventory();
        this.getExpiredInventory();
        setInterval( () => {
            this.getScanLogs();
        }, 1000);
    },
    methods:{

        getScanLogs() {
            axios.get('/api/getScanLogs').then(response => {
                console.log(response.data);
                console.log(this.logId);

                if (this.logId != response.data.id) {
                    this.form = {
                        'serial_number' : '',
                        'sku' : '',
                        'product_name' : '',
                        'quantity' : '',
                        'location' : '',
                        'expiration_date' : '',
                    }

                    this.logId = response.data.id;
                    this.form['serial_number'] = response.data.serial_number;
                    this.form['sku'] = response.data.sku;
                    this.form['product_name'] = response.data.product_name;
                    this.form['quantity'] = response.data.quantity;
                    this.form['location'] = response.data.location;
                    this.form['expiration_date'] = response.data.expiration_date;
                    this.deleteSelected();
                } else {
                    console.log('pareho sila')
                }

            })
        },

        updateScanLogs() {
          axios.get('/api/updateScanLogs').then(response => {
              console.log('Scan Logs :' + response.data);
          })
        },

        getExpiringInventory() {

            axios.get('api/getExpiringInventory').then(response => {
                this.data = response.data;
            })
        },

        getExpiredInventory() {

            axios.get('api/getExpiredInventory').then(response => {
                this.expired = response.data;
            })
        },

        deleteExpired() {
            axios.delete('/api/deleteExpiredInventory').then(response => {
                console.log(response);
            });
        },

        deleteSelected() {
            axios.post('/api/deleteSelected/', this.form).then(response => {
                if (!response.data.deleted) {
                    this.error = true;
                    this.errorMsg = response.data.message
                    this.success = false;
                } else {
                    this.success = true;
                    this.successMsg = response.data.message
                    this.error = false;
                }
            });

            this.getExpiringInventory();
            this.getExpiredInventory();
        },

        hide() {
            this.error = false;
            this.success = false;
        }
    }
}
</script>

<style scoped>

</style>
