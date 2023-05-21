<template>
    <div>
        <button @click="cancel">cancel</button>
        <div class="row" v-if="!loading">
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
                       <div class="input-group mb-3">
                           <input type="text" class="form-control" v-model="form['search']">
                           <div class="input-group-append">
                               <button class="btn btn-outline-warning" @click.prevent="search" type="button"><i class="bx bx-search"> </i> Search</button>
                           </div>
                       </div>

                   </div>
                   <div class="card-body">
                       <div class="demo-inline-spacing mt-3" v-if="data.length > 0">
                           <div class="list-group">
                               <a v-for="inv in data" href="javascript:void(0);" class="list-group-item list-group-item-action flex-column align-items-start">
                                   <div class="d-flex justify-content-between w-100">
                                       <h6 class="mb-2">{{ inv.product_name }}</h6>
                                       <small>Exp : {{ inv.expiration_date }}</small>
                                       <br />
                                       <small>
                                           <button @click.prevent="removeInventory(inv.id)" class="btn btn-danger btn-xs">
                                               <i class="bx bxs-trash"></i>
                                           </button>
                                       </small>
                                   </div>
                                   <p class="mb-1">Serial: {{ inv.serial_number }}</p>
                                   <p class="mb-1">SKU: {{ inv.sku }}</p>
                                   <p class="mb-1">Location: {{ inv.location }}</p>
                                   <p class="mb-1">Quantity: {{ inv.quantity }}</p>
                               </a>
                           </div>
                       </div>
                       <div v-else>
                           <h4>No Data Available..</h4>
                       </div>
                   </div>
               </div>
           </div>
        </div>
        <div class="timer">

        </div>
        <fab
            :position="position"
            :bg-color="bgColor"
            :actions="fabActions"
            @csv="upload"
            @insert="insert"
            @expiring="expiring"
        ></fab>
    </div>

</template>

<script>

import fab from 'vue-fab'
import axios from "axios";

export default {
    name: "dashboard",
    components: {
        fab
    },
    data(){
        return {
            form: {
                'search' : ''
            },
            data : [],
            bgColor: '#778899',
            position: 'bottom-right',
            fabActions: [
                {
                    name: 'csv',
                    icon: 'upload_file',
                },
                {
                    name: 'insert',
                    icon: 'add_circle_outline',
                    color: "green"
                },
                {
                    name: 'expiring',
                    icon: 'remove',
                    color: "red"
                }
            ],
            loading : true,
            error: false,
            success: false,
            errorMsg : '',
            successMsg: '',
            rtlContent3: null
        }
    },

    created() {
        this.getInventory();
    },

    mounted() {
        console.log('test');

        const start = new Date().getTime();
        const limit = start + (1800 * 1000);
        this.rtlContent3 = setInterval(() => {
            const now = new Date().getTime();
            const distance = limit - now;
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            this.rtlContent3 = minutes + 'm ' + seconds + 's ';
            console.log(this.rtlContent3);
            if (distance === 0) {
                clearInterval(this.rtlContent3);
                this.rtlContent3 = null;
            }
        }, 1000);

    },
    methods:{

        cancel() {
            console.log(this.rtlContent3);
            clearInterval(this.rtlContent3);
        },
        getInventory() {
          axios.get('api/getInventory').then(response => {
              this.data = response.data;
          });
          this.loading = false;
        },

        upload(){
            window.location.replace('/uploadInventory')
        },
        expiring(){
            window.location.replace('/expiringInventory')
        },
        insert(){
            console.log('insert');
            window.location.replace('/createInventory')
        },
        search() {
            if (this.form['search'] !== ''){
                axios.get('/api/searchInventory/' + this.form['search']).then(response => {
                    this.data = response.data;
                    console.log(response.data);
                    console.log('search');
                })
            } else {
                this.getInventory();
            }

        },
        removeInventory(id) {
            axios.delete('/api/deleteSelected/' + id).then(response => {
                console.log(response);
                if (!response.data.deleted) {
                    this.error = true;
                    this.errorMsg = response.data.message
                } else {
                    this.success = true;
                    this.successMsg = response.data.message
                    this.getInventory();
                }
            });
        },

        hide() {
            this.error = false;
            this.success = false;
        }
    },
}
</script>

<style scoped>

</style>
