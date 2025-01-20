<script setup>

import { ref } from 'vue';
import Layout from '../Layout/App.vue'
import Toast from './../../Toast/toast.js'
import { router, usePage } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/solid'
import { PencilIcon } from 'lucide-vue-next';
import CreateUpateCustomer from './components/CreateUpateCustomer.vue';



const showModal = ref(false);
const update = ref(false);
const selectedCustomer = ref(null);

const props = defineProps({
    customer: Array
})

const auth = usePage().props.auth;
const page = usePage();

const openUpdateModal = (customer) => {

    showModal.value = true;
    update.value = true;
    selectedCustomer.value = customer;
};

const closeModal = () => {
    selectedCustomer.value = null;
    showModal.value = false;
    update.value = false;
};


const openModal = () => {
    update.value = false;
    selectedCustomer.value = null;
    showModal.value = true;

};


const createCustomer = () => {
    showModal.value = false;
    update.value = false;
    selectedCustomer.value = null;
    router.reload({
        only: ['customer'],
        preserveScroll: true,
    });

};

function deleteCustomer(customerId) {

    router.delete(route('customer.delete'), {
        data: {
            id: customerId,
            store_id: auth.user.store_id,
        },
        onSuccess: () => {
            Toast.successToast(page.props.flash.message);

        }, onError: (errors) => {

            Toast.errorToast(errors['id']);
           

        }


    });







}



</script>

<template>
    <Layout>
        <div class="w-full px-2">

            <div class="flex justify-between mt-2 items-center py-2">
                <h3 class="text-2xl font-bold py-2">Customer</h3>
                <button @click="openModal" class="rounded-xl border h-10 px-5 bg-blue-400 hover:bg-blue-500 text-white">
                    Create
                </button>
            </div>

            <CreateUpateCustomer :visible="showModal" :title="update ? 'Update Customer' : 'Create Customer'"
                :store_id="auth.user.store_id" :initialData="selectedCustomer"
                :submitText="update ? 'Update' : 'Create'" :action="update ? 'put' : 'post'" @submit="createCustomer"
                @close="closeModal" />




            <div class="overflow-x-auto relative sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="grid grid-cols-11">
                            <th scope="col" class="py-3 px-6 col-span-1">No</th>
                            <th scope="col" class="py-3 px-6 col-span-3">Name</th>

                            <th scope="col" class="py-3 px-6 col-span-2">mobile</th>

                            <th scope="col" class="py-3 px-6 col-span-3">address</th>

                            <th scope="col" class="py-3 px-6 col-span-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody v-for="(customers, index) in customer.data" :key="customers.id">



                        <tr v-if="customers.id"
                            class="grid grid-cols-11 h-12 items-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-2 px-6 col-span-1">{{ index + 1 }}</td>
                            <td class="py-2 px-6 col-span-3">
                                {{ customers.profile.first_name + " " + customers.profile.last_names }}
                            </td>
                            <td class="py-2 px-6 col-span-2">{{ customers.mobile }}</td>
                            <td class="py-2 px-6 col-span-3">{{ customers.profile.address }}</td>

                            <td class="py-2 px-6 col-span-2">
                                <div class="flex justify-center gap-5">
                                    <PencilIcon @click="openUpdateModal(customers)"
                                        class=" w-5 h-5 text-white-500  hover:text-gray-400 " />
                                    <TrashIcon @click="deleteCustomer(customers.id)"
                                        class=" w-5 h-5 text-red-500 hover:text-red-300" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </Layout>

</template>