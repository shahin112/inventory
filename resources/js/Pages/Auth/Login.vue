<script setup>
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm } from "@inertiajs/vue3";
import PrimaryBtn from '../../components/PrimaryBtn.vue';
const form = useForm({
    email: "",
    password: "",
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
        onError: (e) => {
            console.log(e);
        }
    });
};
</script>

<template>

    <Head title="- Login" />

    <div class="mx-auto h-screen flex justify-center items-center">

        <Card class="w-full max-w-sm">
            <CardHeader>
                <CardTitle class="text-2xl text-center">
                    Login
                </CardTitle>
            </CardHeader>
            <form @submit.prevent="submit">
                <CardContent class="grid gap-4">


                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" placeholder="m@example.com" required v-model="form.email" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="password">Password</Label>
                        <Input id="password" type="password" required v-model="form.password" />
                    </div>

                </CardContent>
                <CardFooter>
                    <PrimaryBtn class="w-full" :disabled="form.processing">Login</PrimaryBtn>
                </CardFooter>
            </form>

            <div class="mb-4 text-center text-sm">
                Don't have an account?
                <Link href="register" class="underline">
                Register
                </Link>
            </div>
        </Card>
    </div>
</template>