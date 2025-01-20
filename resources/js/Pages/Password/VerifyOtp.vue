<script setup>

import { Card, CardContent, CardFooter, CardHeader, CardDescription } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm } from "@inertiajs/vue3";
import PrimaryBtn from '../../components/PrimaryBtn.vue';
const form = useForm({
    otp: "",
});

const submit = () => {
    form.post(route("password.verify-otp"), {
        onFinish: () => form.reset("otp"),
        onError: (error) => {
            console.log(error);
        }
    });
};
</script>

<template>

    <Head title="- send-otp" />

    <div class="mx-auto h-screen flex justify-center items-center">

        <Card class="w-full max-w-sm">
            <CardHeader>
                <CardDescription class=" text-center font-bold text-black/80 ">
                    Enter below the OTP code.
                </CardDescription>
            </CardHeader>
            <form @submit.prevent="submit">
                <CardContent class="grid gap-4">


                    <div class="grid gap-2">
                        <Label for="otp">OTP</Label>
                        <Input id="otp" type="number" placeholder="1234" required v-model="form.otp" />
                    </div>


                </CardContent>
                <CardFooter>
                    <PrimaryBtn class="w-full" :disabled="form.processing">Submit</PrimaryBtn>
                </CardFooter>
            </form>

        </Card>
    </div>
</template>