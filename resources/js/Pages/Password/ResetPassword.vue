<script setup>
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm } from "@inertiajs/vue3";
import PrimaryBtn from '../../components/PrimaryBtn.vue';
const form = useForm({
    
    password: "",
});

const submit = () => {
    form.post(route("password.reset"), {
        onFinish: () => form.reset("password"),
        onError: (e) => {
            console.log(e);
        }
    });
};
</script>

<template>

    <Head title="- set new password" />

    <div class="mx-auto h-screen flex justify-center items-center">

        <Card class="w-full max-w-sm">
            <CardHeader>
                <CardTitle class="text-2xl text-center">
                    Set new passwrod
                </CardTitle>
            </CardHeader>
            <form @submit.prevent="submit">
                <CardContent class="grid gap-4">



                    <div class="grid gap-2">
                        <Label for="password">Password</Label>
                        <Input id="password" type="password" required v-model="form.password" />
                    </div>


                </CardContent>
                <CardFooter>
                    <PrimaryBtn class="w-full" :disabled="form.processing">Done</PrimaryBtn>
                </CardFooter>
            </form>

            
        </Card>
    </div>
</template>