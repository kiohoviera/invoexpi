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
                            Create / Update Inventory
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Serial #</label>
                            <input type="text" class="form-control" v-model="form['serial_number']" required />
                        </div>
                        <div class="form-group mb-3">
                            <label>SKU</label>
                            <input type="text" class="form-control" v-model="form['sku']" required />
                        </div>
                        <div class="form-group mb-3">
                            <label>Product Name</label>
                            <input type="text" class="form-control" v-model="form['product_name']" required />
                        </div>
                        <div class="form-group mb-3">
                            <label>Quantity</label>
                            <input type="number" class="form-control" v-model="form['quantity']" required />
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
                            <label>Expiration Date</label>
                            <input type="date" class="form-control" v-model="form['expiration_date']" required />
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-success btn-block w-100" @click.prevent="store"><i class="bx bxs-save"></i> Save Record </button>
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
    name: "create-inventory",

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
            bgColor: '#778899',
            position: 'bottom-right',
            fabActions: [
                {
                    name: 'csv',
                    icon: 'upload_file'
                },
                {
                    name: 'insert',
                    icon: 'add_circle_outline'
                }
            ],
            logId : null,
            error: false,
            success: false,
            errorMsg : '',
            successMsg: ''
        }
    },

    created() {
        this.getScanLogs();
    },
    mounted() {
        setInterval( () => {
            this.getScanLogs();
        }, 1000);
    },
    methods:{
       store() {

           axios.post('/api/updateInventory', this.form).then(response => {
               console.log('response');
               console.log(response.data.message);

               if (response.data.error){
                   this.errorMsg = response.data.message;
                   this.error = true;
               } else {
                   this.success = true;
                   this.successMsg = response.data.message
               }
           })
       },

        getScanLogs() {
           axios.get('/api/getScanLogs').then(response => {
               console.log(response.data);
               console.log(this.logId);

               if (this.logId != response.data.id) {
                   this.logId = response.data.id;
                   this.form['serial_number'] = response.data.serial_number;
                   this.form['sku'] = response.data.sku;
                   this.form['product_name'] = response.data.product_name;
                   this.form['quantity'] = response.data.quantity;
                   this.form['location'] = response.data.location;
                   this.form['expiration_date'] = response.data.expiration_date;
                   console.log(this.form);
               } else {
                   console.log('pareho sila')
               }

           })
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
