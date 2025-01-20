<script setup>
import { ref } from "vue";

const props = defineProps({
    productImage: String,
});

const currentImage = props.productImage
    ? `/product/img/${props.productImage}`
    : null;
const preview = ref(currentImage);

const emit = defineEmits(["image"]);

const imageSelected = (e) => {
    preview.value = URL.createObjectURL(e.target.files[0]);

    emit("image", e.target.files[0]);
};
</script>
<template>
    <div>
        <label
            for="image"
            class="block relative rounded-md mt-1 h-[140px] overflow-hidden cursor-pointer border-slate-300 border"
            :class="{ '!border-red-500': oversizeImage }"
        >
            <img
                :src="preview ?? '/product/img/defult.jpg'"
                class="object-cover object-center w-full h-full"
                alt=""
            />
        </label>
        <input
            @input="imageSelected"
            type="file"
            name="image"
            id="image"
            hidden
        />
    </div>
</template>