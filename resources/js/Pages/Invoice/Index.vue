<script setup>

import Layout from '../Layout/App.vue'
import { TailwindPagination } from 'laravel-vue-pagination';
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/solid'

import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showModal = ref(false);
const searchQuery = ref("");

const invoices_info = ref([]);

const props = defineProps({
    invoice: Object
})

const getResults = (page) => {
    router.get(`invoice?page=${page}`);
};


function print(){
    showModal.value = false;
    const invoice = document.getElementById("invoice").innerHTML;
    const originalcontents=document.body.innerHTML;
    document.body.innerHTML=invoice;
    window.print();
    document.body.innerHTML.originalcontents;
    setTimeout(function(){
        location.reload();
    },500)
}


const searching = () => {
    if (searchQuery.value.trim() === "") {
        
        router.get(route('invoice'), {}, {
            preserveState: true,  
            preserveScroll: true  
        });
    } else {
         
        router.get(route('invoice', { invoice_id: searchQuery.value }), {}, {
            preserveState: true,
            preserveScroll: true
        });
    }
};


const onInputChange = () => {
    if (searchQuery.value.trim() === "") {
      
        router.get(route('invoice'), {}, {
            preserveState: true,  
            preserveScroll: true  
        });
    }
};


function showinvoice(invoice) {

    showModal.value = true;

    try {
        axios.get(route('invoiceinfo', { invoice_id: invoice })).then(response => {

            invoices_info.value = response.data.data || [];

        });

    } catch (error) {
        console.error('Error fetching customers:', error);
    }
};


</script>

<template>

    <Layout>

        <div class="w-full px-2">
            <div class="flex justify-between mt-2 items-center py-2">
                <h3 class="text-2xl font-bold py-2">Invoice</h3>
            </div>


            <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="bg-white py-6 px-8 rounded-lg shadow-lg w-2/3 relative z-10">
                    <h2 class="text-xl text-center font-bold mb-8">Invoice</h2>

                    <div id="invoice"> 
                    <div class="flex justify-between items-center">
                        <div class="flex gap-5 items-center">
                            <img src="https://w7.pngwing.com/pngs/705/139/png-transparent-vue-js-framework-user-interfaces-progressive-vue-logo-3d-icon.png"
                                alt="profile" class="w-16 h-16 rounded-2xl border border-black" />
                            <div>
                                <h1 v-if="invoices_info.header_info?.store.name" class="font-bold">
                                    {{ invoices_info.header_info?.store.name }}
                                </h1>
                                <h1>
                                    {{ invoices_info.header_info.store.info.address }}
                                </h1>
                            </div>
                        </div>

                        <div>
                            <div>
                                <h1 class="text-center font-semibold">Billing to</h1>
                                <div class="flex gap-1">
                                    <div class="flex gap-1">
                                        <h1>Customer name:</h1>
                                        <h1 class="font-semibold">
                                            {{
                                                invoices_info.header_info.user.profile.first_name +" "+invoices_info.header_info.user.profile.last_names
                                            }}
                                        </h1>
                                    </div>
                                    <div class="flex gap-1">
                                        <h1>Phone:</h1>
                                        <h1 class="font-semibold">
                                            {{ invoices_info.header_info.user.mobile }}
                                        </h1>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <h1>Address:</h1>
                                    <h1 class="font-semibold">
                                        {{ invoices_info.header_info.user.profile.address }}
                                    </h1>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="flex gap-1">
                                    <h1 class="font-semibold">Invoice Number:</h1>
                                    <h1 v-if="invoices_info?.header_info" class="font-bold">
                                        {{ invoices_info?.header_info.invoice_number }}
                                    </h1>
                                </div>
                                <div class="flex gap-2">
                                    <h1 class="font-semibold">Issue date :</h1>
                                    <h1 v-if="invoices_info?.header_info" class="font-bold">
                                        {{ invoices_info?.header_info?.issue_date }}
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="bg-gray-400 h-[2px] mt-5" />

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-3">
                        <thead class="text-xs text-black uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="grid grid-cols-8">
                                <th scope="col" class="py-3 px-6 col-span-1">item</th>
                                <th scope="col" class="py-3 px-6 col-span-2">Name</th>
                                <th scope="col" class="py-3 px-6 col-span-1">price</th>
                                <th scope="col" class="py-3 px-6 col-span-1">Qty</th>
                                <th scope="col" class="py-3 px-6 col-span-1">vat (5%)</th>

                                <th scope="col" class="py-3 px-6 col-span-2 text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody v-for="(items, index) in invoices_info?.items" :key="index">
                            <tr
                                class="grid grid-cols-8 items-center font-semibold bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="py-2 px-6 col-span-1">{{ index + 1 }}</td>
                                <td class="py-2 px-6 col-span-2">{{ items.product_name }}</td>
                                <td class="py-2 px-6 col-span-1">{{ items.original_price }}</td>

                                <td class="py-2 px-6 col-span-1">{{ items.quantity }}</td>
                                <td class="py-2 px-6 col-span-1">{{ items.vat }}</td>
                                <td class="py-2 px-6 col-span-2 text-end font-bold text-black">
                                    {{ items.price }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-end">
                        <div class="flex-col px-6 gap-4">
                            <div class="flex gap-4 justify-between">
                                <h1 class="font-semibold">Subtotal</h1>
                                <h1 v-if="invoices_info?.sub_total" class="font-bold">
                                    {{ invoices_info?.sub_total }}
                                </h1>
                            </div>
                            <div class="flex gap-4 justify-between">
                                <h1 class="font-semibold">Discount</h1>
                                <h1 v-if="invoices_info?.discount" class="font-bold">
                                    {{ invoices_info.discount }}
                                </h1>
                            </div>
                            <div class="flex gap-4 justify-between">
                                <h1 class="font-semibold">Total Vat</h1>
                                <h1 v-if="invoices_info?.total_vat" class="font-bold">
                                    {{ invoices_info.total_vat }}
                                </h1>
                            </div>
                        </div>
                    </div>
                    <hr class="bg-gray-400 h-[2px] mt-1" />

                    <div class="flex justify-end">
                        <div class="flex-col px-6 gap-4">
                            <div class="flex gap-4 justify-between">
                                <h1 class="font-semibold">Total</h1>
                                <h1 v-if="invoices_info?.total_price" class="font-bold">
                                    {{ invoices_info.total_price }}
                                </h1>
                            </div>
                        </div>
                    </div>

                </div>

                    <div class="flex justify-end mt-4 space-x-4">
                        <button @click="showModal = false"
                            class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">
                            Cancel
                        </button>
                        <button @click="print()"
                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                            Print
                        </button>
                    </div>
                </div>
            </div>


            <hr class="bg-gray-400 h-[2px]" />
            <div class="w-[calc(100%-30px)] flex justify-center">
                <div class="w-[calc(100%-200px)] flex justify-center">
                    <!-- Search bar -->
                    <div class="relative flex mt-5 w-[500px]">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-auto text-gray-500 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input @keydown.enter.prevent="searching" @input="onInputChange" autocomplete="off"
                            type="search" id="voice-search" v-model="searchQuery"
                            class="bg-gray-50 border border-gray-400 outline-none text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search..." required />
                    </div>
                </div>
            </div>



            <div class="flex flex-col h-[450px] justify-between">
                <div class="overflow-x-auto relative sm:rounded-lg mt-4">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="grid grid-cols-11">
                                <th scope="col" class="py-3 px-6 col-span-1">no</th>

                                <th scope="col" class="py-3 px-6 col-span-3">Name</th>
                                <th scope="col" class="py-3 px-6 col-span-2">phone</th>

                                <th scope="col" class="py-3 px-6 col-span-1">subtotal</th>
                                <th scope="col" class="py-3 px-6 col-span-1">discount</th>
                                <th scope="col" class="py-3 px-6 col-span-1">vat</th>

                                <th scope="col" class="py-3 px-6 col-span-1">payable</th>

                                <th scope="col" class="py-3 px-6 col-span-1 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody v-for="invoice in invoice.data" :key="invoice.id">

                             
                            <tr v-if="invoice.invoice_id"
                                class="grid grid-cols-11 h-12 items-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="py-2 px-6 col-span-1">{{ invoice.invoice_id }}</td>
                                <td class="py-2 px-6 col-span-3">
                                    {{ invoice.user.profile.first_name +" "+invoice.user.profile.last_names }}
                                </td>
                                <td class="py-2 px-6 col-span-2">{{ invoice.user.mobile }}</td>

                                <td class="py-2 px-6 col-span-1">{{ invoice.total_price }}</td>
                                <td class="py-2 px-6 col-span-1">{{ invoice.total_discount }}</td>
                                <td class="py-2 px-6 col-span-1">{{ invoice.total_vat }}</td>
                                <td class="py-2 px-6 col-span-1">
                                    {{ invoice.payable }}
                                </td>

                                <td class="py-2 px-6 col-span-1 text-right">
                                    <div class="flex justify-end gap-5">
                                        <EyeIcon @click="showinvoice(invoice.id)"
                                            class=" w-5 h-5 text-white-500 hover:text-gray-400 " />
                                        <TrashIcon class=" w-5 h-5 text-red-500 hover:text-red-300" />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-center">
                    <TailwindPagination :data="invoice" @pagination-change-page="getResults" />
                </div>
            </div>

        </div>
    </Layout>

</template>