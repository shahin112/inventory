<script setup>

import Layout from '../Layout/App.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm, usePage } from '@inertiajs/vue3';
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue';
import { PencilIcon } from 'lucide-vue-next';


const showModal = ref(false);
const auth = usePage().props.auth;

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
});

const localCategories = ref(Array.isArray(props.categories) ? [...props.categories] : []);

const form = useForm({
    category_img: "",
    category_name: "",
    store_id: auth.user.store_id,


});


const createCustomer = () => {
    form.post(route("category"), {
        onSuccess: () => {
            form.reset();
        },
        onFinish: (response) => {
            showModal.value = false;
            if (response?.data) {

                localCategories.value.push(response.data);
            } else {
                console.error('Invalid response structure:', response);
            }
        },
        onError: (e) => {
            console.log(e);
        }
    });
};
</script>

<template>

    <Layout>
        <div class="w-full px-2">



            <div class="flex justify-between mt-2 items-center py-2">
                <h3 class="text-2xl font-bold py-2">Category</h3>
                <button @click="showModal = true"
                    class="rounded-xl border h-10 px-5 bg-blue-400 hover:bg-blue-500 text-white">
                    Create
                </button>
            </div>

            <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="bg-white p-8 rounded-lg shadow-lg w-2/5 relative z-10">
                    <form @submit.prevent="createCustomer()" class="bg-white p-8 rounded-lg  w-full  relative z-10 ">
                        <h2 class="text-xl text-center font-bold mb-4">Create Category</h2>


                        <div class="grid gap-2 mt-5">
                            <Label for="mobile">Category Name *</Label>
                            <Input id="mobile" placeholder="mobile" required v-model="form.category_name" />
                        </div>

                        <div class="flex justify-end mt-4 space-x-4">
                            <button @click="showModal = false"
                                class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">
                                Cancel
                            </button>
                            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="overflow-x-auto relative sm:rounded-lg mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="grid grid-cols-8">
                            <th scope="col" class="py-3 px-6 col-span-1">No</th>
                            <th scope="col" class="py-3 px-6 col-span-2">Category</th>

                            <th scope="col" class="py-3 px-6 col-span-2">Product</th>

                            <th scope="col" class="py-3 px-6 col-span-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody v-for="(category, index) in categories.data" :key="category.id">

                        <tr v-if="category.category_name"
                            class="grid grid-cols-8 items-center h-12 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-2 px-6 col-span-1">{{ index + 1 }}</td>

                            <td class="py-2 px-6 col-span-2 text-black">
                                {{ category.category_name }}
                            </td>
                            <td class="py-2 px-6 col-span-2 text-black">
                                {{ category.product_total }}
                            </td>

                            <td class="py-2 px-6 col-span-3 text-right">
                                <div class="flex justify-end gap-4">

                                    <PencilIcon class=" w-5 h-5 text-white-500 hover:text-gray-400 " />
                                    <TrashIcon class=" w-5 h-5 text-red-500 hover:text-red-300" />


                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Layout>

</template>