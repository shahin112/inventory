<script setup>

import Layout from '../Layout/App.vue';
import { ref } from 'vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { router } from '@inertiajs/vue3'
import ImageUpload from "@/Components/ImageUpload.vue";
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/solid'



import { useForm, usePage } from '@inertiajs/vue3';
import { PencilIcon } from 'lucide-vue-next';

const showModal = ref(false)

const props = defineProps({
    products: Array,
    category: Array
})

const auth = usePage().props.auth;
const form = useForm({

    category_id: "",
    title: "",
    short_des: "",
    price: "",
    discount: "",
    image: "",
    stock: "",
    store_id: auth.user.store_id,
});


const createProduct = () => {
    form.post(route("products"), {
        onSuccess: () => {
            form.reset();
        },
        onFinish: (response) => {
            showModal.value = false;
            refreshProducts();


        }
    });
};

const refreshProducts = () => {
    router.reload({
        only: ['products'],
        preserveScroll: true,
    });
};


</script>

<template>

    <Layout>



        <div class="w-full px-2">
            <div class="flex justify-between mt-2 items-center py-2">
                <h3 class="text-2xl font-bold py-2">Products</h3>
                <button @click="showModal = true"
                    class="rounded-xl border h-10 px-5 bg-blue-400 hover:bg-blue-500 text-white">
                    Create
                </button>
            </div>

            <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="bg-white p-8 rounded-lg shadow-lg w-1/2 relative z-10">
                    <h2 class="text-xl text-center font-semibold mb-4">Create Product</h2>

                    <form @submit.prevent="createProduct()">
                        <div class="flex gap-2 justify-center items-center w-full">
                            <div class="grid gap-2 w-1/2">
                                <Label for="title">Product Title</Label>
                                <Input id=" title" placeholder="Mobile" required v-model="form.title" />
                            </div>

                            <div class="w-1/2 mt-[1px]">
                                <label for="unitDropdown"
                                    class="block text-gray-700 text-sm font-bold ">Category:</label>
                                <select v-model="form.category_id" id="unitDropdown"
                                    class="w-full  py-[6px] border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <option value="" disabled>select category</option>
                                    <option v-for="number in category" :key="number" :value="number.category_id">
                                        {{ number.category_name }}
                                    </option>
                                </select>

                            </div>
                        </div>



                        <div class="flex gap-2 justify-center w-full mt-4">
                            <div class="grid gap-2 w-1/2">
                                <Label for="price">Price</Label>
                                <Input id="price" placeholder="$10" required v-model="form.price" />
                            </div>
                            <div class="grid gap-2 w-1/2">
                                <Label for="unit">Unit</Label>
                                <Input id="unit" placeholder="10" required v-model="form.stock" />
                            </div>
                        </div>

                        <label for="exampleInputPassword1" class="block text-gray-700 font-medium mb-2">Image
                            Uploads</label>

                        <ImageUpload :productImage="form.image" @image="(e) => (form.image = e)" />



                        <div class="flex justify-end mt-4 space-x-4">
                            <button @click="showModal = false"
                                class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                Create
                            </button>
                        </div>

                    </form>
                </div>
            </div>



            <div class="overflow-x-auto relative sm:rounded-lg mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="grid grid-cols-12">
                            <th scope="col" class="py-3 px-6 col-span-1">No</th>
                            <th scope="col" class="py-3 px-6 col-span-1">image</th>
                            <th scope="col" class="py-3 px-6 col-span-2">Name</th>

                            <th scope="col" class="py-3 px-6 col-span-2">Price</th>

                            <th scope="col" class="py-3 px-6 col-span-2">unit</th>
                            <th scope="col" class="py-3 px-6 col-span-2">category</th>

                            <th scope="col" class="py-3 px-6 col-span-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody v-for="(product, index) in products" :key="product.id">

                        <tr v-if="product.id"
                            class="grid grid-cols-12 items-center h-12 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-2 px-6 col-span-1">{{ index + 1 }}</td>
                            <td class="py-2 px-6 col-span-1"><img class="w-8 h-8 "
                                    :src="'/product/img/' + (product.image ? product.image : 'no-image.jpeg')" />
                            </td>

                            <td class="py-2 px-6 col-span-2">{{ product?.title }}</td>
                            <td class="py-2 px-6 col-span-2">{{ product?.price }}</td>
                            <td class="py-2 px-6 col-span-2">{{ product?.stock }}</td>
                            <td v-if="product?.category?.category_name" class="py-2 px-6 col-span-2">
                                {{ product?.category.category_name }}
                            </td>

                            <td class="py-2 px-6 col-span-2 text-right">
                                <div class="flex justify-end gap-4">
                                    
                                    <PencilIcon class=" w-5 h-5 text-white-500  hover:text-gray-400 " />
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