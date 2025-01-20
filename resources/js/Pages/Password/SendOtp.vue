<script setup>

import { Card, CardContent, CardFooter, CardHeader, CardDescription } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm } from "@inertiajs/vue3";
import PrimaryBtn from '../../components/PrimaryBtn.vue';
const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.send-otp"), {
        onFinish: () => form.reset("email"),
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
                <CardDescription>
                    Enter your email below to reset your password.
                </CardDescription>
            </CardHeader>
            <form @submit.prevent="submit">
                <CardContent class="grid gap-4">


                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" placeholder="m@example.com" required v-model="form.email" />
                    </div>


                </CardContent>
                <CardFooter>
                    <PrimaryBtn class="w-full" :disabled="form.processing">Submit</PrimaryBtn>
                </CardFooter>
            </form>

        </Card>
    </div>
</template>