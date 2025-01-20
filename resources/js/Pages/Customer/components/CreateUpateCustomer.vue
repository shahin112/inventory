<template>
    <div v-if="visible" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <form @submit.prevent="handleSubmit" class="bg-white p-8 rounded-lg w-2/5 relative z-10">
            <h2 class="text-xl text-center font-bold mb-4">{{ title }}</h2>


            <div class="grid gap-2 mt-5">
                <Label for="first-name">First name *</Label>
                <Input id="first-name" placeholder="Shahin" v-model="form.first_name" />
                <p v-if="errors.first_name" class="text-red-500 text-sm">{{ errors.first_name }}</p>
            </div>

            <div class="grid gap-2 mt-5 ">
                <Label for="last-name">Last name *</Label>
                <Input id="last-name" placeholder="miah" v-model="form.last_name" />
                <p v-if="errors.last_name" class="text-red-500 text-sm">{{ errors.last_name }}</p>
            </div>

            <div class="grid gap-2 mt-5">
                <Label for="mobile">Customer Mobile *</Label>
                <Input id="mobile" placeholder="01xxxxxxxxxx" v-model="form.mobile" />
            </div>
            <div v-if="errors.mobile" class="text-red-500">{{ errors.mobile }}</div>

            <div class="grid gap-2 mt-5">
                <Label for="address">Customer address *</Label>
                <Input id="address" placeholder="Hobigonj,Sylhet" required v-model="form.address" />
            </div>

            <div class="flex justify-end mt-4 space-x-4">
                <button @click="closeModal" class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    {{ submitText }}
                </button>
            </div>
        </form>
    </div>
</template>



<script setup>
import { ref, watch } from "vue";
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm, usePage } from "@inertiajs/vue3";
import Toast from '../../../Toast/toast.js'


const props = defineProps({
    visible: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
    },
    store_id: {
        type: String,
    },
    submitText: {
        type: String,
    },
    action: {
        type: String,
    },
    initialData: {
        type: Object,
        default: () => ({}),
    },
});
const page = usePage();

const errors = ref({});


const emit = defineEmits(["submit", "close"]);


const form = useForm({
    id: "",
    first_name: "",
    last_name: "",
    mobile: "",
    address: "",
    store_id: props.store_id

});


watch(
    () => props.initialData,
    (newData) => {
        if (newData) {
            form.id = newData.id;
            form.first_name = newData.profile.first_name || "";
            form.last_name = newData.profile.last_names || "";
            form.mobile = newData.mobile || "";
            form.address = newData.profile.address || "";

        }
    },
    { immediate: true }
);


const closeModal = () => {
    form.reset();
    errors.value = ""
    emit("close");
};


const handleSubmit = async () => {

    if (props.action === 'post') {

        form.post(route("customer"), {
            onSuccess: () => {
                Toast.successToast(page.props.flash.message);
                form.reset();
                errors.value = ""
                emit("submit");
            },

            onError: (error) => {
                errors.value = { ...error };
            }
        });


    } else {

        form.put(route("customer.update"), {
            onSuccess: () => {
                Toast.successToast(page.props.flash.message);
                form.reset();
                errors.value = ""
                emit("submit");
            },

            onError: (error) => {
                errors.value = { ...error };
                if (error['id']) {
                    Toast.errorToast(error['id']);
                }


            }
        });


    }
};
</script>
