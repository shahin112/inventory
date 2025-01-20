<script setup>
import { Button } from '@/components/ui/button'
import Layout from '../Layout/App.vue'
import { computed, ref } from 'vue';

import { router } from '@inertiajs/vue3'

import { TrashIcon, MinusIcon, PlusIcon } from '@heroicons/vue/24/outline';



const searchCustomer = ref("");

const selectedCustomerName = ref(null);
const selectedCustomerid = ref(null);
const selectedProducts = ref([]);
const customers = ref([]);
const discount = ref(0);


const props = defineProps({
    products: Array
});




const searchingcutomer = async () => {
    try {
        const response = await axios.get(route('searchcustomer', { query: searchCustomer.value }));

        customers.value = response.data.customers || [];
    } catch (error) {
        console.error('Error fetching customers:', error);
    }
};



const onInputChangecustomer = () => {
    if (searchCustomer.value === "") {
        selectedCustomerName.value = null;
        selectedCustomerid.value = null;
        customers.value = [];
    }
};

const selectCustomer = (customer) => {
    selectedCustomerName.value = customer.first_name+" "+customer.last_name;
    selectedCustomerid.value = customer.id;
    customers.value = [];
};

const addToCart = (product) => {
    const existingProduct = SelectedItem.value.products.find(
        (p) => p.id === product.id
    );

    if (existingProduct) {
        console.log("already exits");
    } else {
        SelectedItem.value.products.push({ ...product, qty: 1 });
    }
};

const SelectedItem = ref({
    products: [],
});


const totalPayable = computed(() => {
    const total = SelectedItem.value.products.reduce(
        (sum, product) => sum + product.price * product.qty,
        0
    );

    const discountValue = parseFloat(discount.value) || 0;
    return total - (discountValue > total ? total : discountValue);
});


const increaseQty = (id) => {
    const product = SelectedItem.value.products.find((product) => product.id === id);
    if (product && product.qty < product.stock) {
        product.qty++;
    } else {
        console.log("Cannot increase quantity beyond stock.");
    }
};

const decreaseQty = (id) => {
    const product = SelectedItem.value.products.find((product) => product.id === id);
    if (product && product.qty > 1) {
        product.qty--;
    } else {
        console.log("Minimum quantity is 1.");
    }
};

const removeProduct = (id) => {
    SelectedItem.value.products = SelectedItem.value.products.filter(
        (product) => product.id !== id
    );
};




const handleSale = async () => {
    selectedProducts.value = SelectedItem.value.products.map((product) => ({
        product_id: product.id,
        quantity: product.qty,
    }));
    //

    const payload = {
        discount: discount.value || 0,
        user_id: selectedCustomerid.value,
        products: selectedProducts.value,
        store_id: 1
    };

    router.post(route('sellProducts'), payload, {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({
                only: ['products'],
            });
            selectedCustomerName.value = null;
            selectedCustomerid.value = null;
            SelectedItem.value.products = [];
            searchCustomer.value = "";
            customers.value = [];
            selectedProducts.value = [];
            discount.value = null;

        },
        onError: (errors) => {
            console.error('Error during sale:', errors);
        },
    });

};

</script>

<template>

    <Layout>

        <div class="w-full px-2">
            <div class="relative flex justify-between mt-2 items-center py-2">
                <h3 class="text-2xl font-bold py-2">Sales</h3>
                <h3 class="text-2xl font-bold py-2">
                    {{ selectedCustomerName || "" }}
                </h3>

                <div class="relative">
                    <div class="flex">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-auto text-gray-500 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input @keydown.enter.prevent="searchingcutomer" @input="onInputChangecustomer"
                            autocomplete="off" type="search" id="voice-search" v-model="searchCustomer"
                            class="bg-gray-50 border border-gray-400 outline-none text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search..." required />
                    </div>



                    <div v-if="customers.length > 0"
                        class="absolute right-0 bg-white border mt-2 p-2 rounded shadow-lg max-w-sm max-h-[200px] overflow-y-auto w-full z-50">
                        <ul>
                            <li v-for="(customer, index) in customers" :key="index" @click="selectCustomer(customer)"
                                class="cursor-pointer p-2 hover:bg-gray-200">
                                {{ customer.first_name + " " + customer.last_name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr class="bg-gray-400 h-[2px]" />

            <div class="flex">

                <div class="w-1/2 bg-gray-300">


                    <div class="flex justify-center w-full mt-5">
                        <h1 class="w-full text-center font-semibold text-2xl">Available </h1>
                    </div>

                    <div class="overflow-x-auto relative sm:rounded-lg mt-4 mr-2 ml-2 mb-2">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 h-12 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr class="grid grid-cols-7">
                                    <th scope="col" class="py-3 px-6 col-span-1">No</th>
                                    <th scope="col" class="py-3 px-6 col-span-2">image</th>
                                    <th scope="col" class="py-3 px-6 col-span-2">Name</th>

                                    <th scope="col" class="py-3 px-6 col-span-1">Qty</th>
                                    <th scope="col" class="py-3 px-6 col-span-1">Price</th>

                                </tr>
                            </thead>
                            <tbody v-for="(product, index) in props.products" :key="product.id"
                                @click="addToCart(product)">
                                <tr
                                    class="grid grid-cols-7  h-12 items-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                                    <td class="py-2 px-6 col-span-1">{{ index + 1 }}</td>
                                    <td class="py-2 px-6 col-span-2"><img class="w-8 h-8"
                                            :src="'/product/img/' + (product.image ? product.image : 'no-image.jpeg')" />
                                    </td>

                                    <td class="py-2 px-6 col-span-2">{{ product.title }}</td>
                                    <td class="py-2 px-6 col-span-1">{{ product.stock }}</td>
                                    <td class="py-2 px-6 col-span-1">{{ product.price }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="w-1/2 bg-gray-300 ml-1">


                    <div class="flex justify-center w-full mt-5">
                        <h1 class="w-full text-center font-semibold text-2xl">Selected </h1>
                    </div>

                    <div class="overflow-x-auto relative sm:rounded-lg mt-4 mr-2 ml-2 mb-2">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 h-12 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr class="grid grid-cols-10">
                                    <th scope="col" class="py-3 px-6 col-span-1">No</th>
                                    <th scope="col" class="py-3 px-6 col-span-2">Image</th>
                                    <th scope="col" class="py-3 px-6 col-span-2">Name</th>
                                    <th scope="col" class="py-3 px-6 col-span-1">Qty</th>
                                    <th scope="col" class="py-3 px-6 col-span-1">Price</th>
                                    <th scope="col" class="py-3 px-6 col-span-1">Total</th>
                                    <th scope="col" class="py-3 px-6 col-span-2 text-right">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in SelectedItem.products" :key="product.id"
                                    class="grid grid-cols-10 h-12 items-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-2 px-6 col-span-1">{{ index + 1 }}</td>
                                    <td class="py-2 px-6 col-span-2"><img class="w-8 h-8"
                                            :src="'/product/img/' + (product.image ? product.image : 'no-image.jpeg')" />
                                    </td>
                                    <td class="py-2 px-6 col-span-2">{{ product.title }}</td>
                                    <td class="py-2 px-6 col-span-1">{{ product.qty }}</td>
                                    <td class="py-2 px-6 col-span-1">{{ product.price }}</td>
                                    <td class="py-2 px-6 col-span-1">
                                        {{ product.price * product.qty }}
                                    </td>
                                    <td class="py-1 px-6 col-span-2 text-right">
                                        <div class="flex justify-end gap-2">

                                            <MinusIcon @click="decreaseQty(product.id)"
                                                class=" w-5 h-5 text-white-500 hover:text-gray-400" />

                                            <PlusIcon @click="increaseQty(product.id)"
                                                class=" w-5 h-5 text-white-500  hover:text-gray-400 " />

                                            <TrashIcon @click="removeProduct(product.id)"
                                                class=" w-5 h-5 text-red-500 hover:text-red-300" />



                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="SelectedItem.products.length > 0"
                            class="flex justify-end gap-3 mt-2 mr-5 items-center">
                            <h1>Discount</h1>
                            <input v-model="discount" type="number" min="0"
                                class="bg-gray-50 border border-gray-300 p-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4"
                                placeholder="Enter discount" />
                            <h1>Total Payable =</h1>
                            <h1>{{ totalPayable }}</h1>

                            <button @click="handleSale" :disabled="!selectedCustomerid"
                                class="border items-center py-1 px-5 rounded-lg text-white capitalize font-bold" :class="{
                                    'bg-gray-400': !selectedCustomerid,
                                    'bg-blue-700': selectedCustomerid,
                                }">
                                Submit Sale
                            </button>
                        </div>
                        <div class="text-center font-semibold mt-10" v-else>
                            Card is empty
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </Layout>

</template>