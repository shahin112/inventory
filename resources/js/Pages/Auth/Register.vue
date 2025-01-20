<script setup>
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Link } from '@inertiajs/vue3';
import { useForm } from "@inertiajs/vue3";
import PrimaryBtn from '../../components/PrimaryBtn.vue';

const form = useForm({
    first_name: "",
    last_name: "",
    mobile: "",
    email: "",
    password: "",
    store_name: "",

});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password"),
        onError: (e) => {
            console.log(e);
        }
    });
};

</script>

<template>

    <Head title="- Register" />

    <div class="mx-auto h-screen flex justify-center items-center">
        <Card class="mx-auto max-w-sm">
            <CardHeader>
                <CardTitle class="text-xl text-center">
                    Sign Up
                </CardTitle>
                <CardDescription>
                    Enter your information to create an account
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit">
                    <div class="grid gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <Label for="first-name">First name</Label>
                                <Input id="first-name" placeholder="Shahin" required v-model="form.first_name" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="last-name">Last name</Label>
                                <Input id="last-name" placeholder="Miah" required v-model="form.last_name" />
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="store name">Store name</Label>
                            <Input id="store name" placeholder="Ostad limited" required v-model="form.store_name" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="email" placeholder="my@gmail.com" required v-model="form.email" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="mobile">Mobile</Label>
                            <Input id="mobile" placeholder="017***********" required v-model="form.mobile" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="password">Password</Label>
                            <Input id="password" type="password" v-model="form.password" />
                        </div>
                        <PrimaryBtn :disabled="form.processing">Register</PrimaryBtn>

                    </div>
                </form>
                <div class="mt-4 text-center text-sm">
                    Already have an account?
                    <Link href="login" class="underline">
                    Log in
                    </Link>
                </div>
            </CardContent>
        </Card>
    </div>
</template>