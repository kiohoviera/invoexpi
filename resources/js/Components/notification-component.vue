<script setup>

</script>

<template>
    <div>
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
            <a
                class="nav-link dropdown-toggle hide-arrow"
                href="javascript:void(0);"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
            >
                <i class="bx bx-bell bx-sm" style="color: white"></i>
                <span class="badge bg-danger rounded-pill badge-notifications">{{ data.length }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Notification</h5>
                        <a
                            href="javascript:void(0)"
                            class="dropdown-notifications-all text-body"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Mark all as read"
                        ><i class="bx fs-4 bx-envelope-open"></i
                        ></a>
                    </div>
                </li>
                <li class="dropdown-notifications-list scrollable-container">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read" v-for="notif in data">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                      <span class="avatar-initial rounded-circle bg-label-warning"
                                      ><i class="bx bx-error"></i
                                      ></span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ notif.product_name }}</h6>
                                    <p class="mb-0">{{ notif.sku }}</p>
                                    <p class="mb-0">{{ notif.location }}</p>
                                    <small class="text-muted">{{ notif.expiration_date }}</small>
                                </div>
                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                    ><span class="badge badge-dot"></span
                                    ></a>
                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                    ><span class="bx bx-x"></span
                                    ></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </div>
</template>

<script>

import axios from "axios";

export default {
    name: "notification-component",

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

    },

    mounted() {
        this.getExpiringInventory();
    },
    methods:{


        getExpiringInventory() {

            axios.get('api/getExpiringInventory').then(response => {
                this.data = response.data;
            })
        },
    }
}
</script>

<style scoped>

</style>
