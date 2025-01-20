<script setup>
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet'
import { ChartBarStacked, ChartNoAxesColumn, CircleUser, DollarSign, Home, LineChart, Menu, Package, Package2, Text, UserRound, Users } from 'lucide-vue-next'
import { Link, usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const logout = () => {
    router.post(route("logout"));
};

const auth=usePage().props.auth;
</script>

<template>
    <div class="grid min-h-screen w-full md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]">
        <div class="hidden border-r bg-muted/40 md:block">
            <div class="flex h-full max-h-screen flex-col gap-2">
                <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
                    <Link href="/" class="flex items-center gap-2 font-semibold">
                    <Package2 class="h-6 w-6" />
                    <span class="">{{ auth.store.store_name  }}</span>
                    </Link>

                </div>
                <div class="flex-1">
                    <nav class="grid items-start px-2 text-sm font-medium lg:px-4 gap-3">
                        <Link href="/" :class="$page.url === '/' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                            class="flex items-center gap-3 rounded-lg px-3 py-2  transition-all hover:text-primary">
                        <Home class="h-4 w-4" />
                        Dashboard
                        </Link>
                        <Link href="/customer"
                            :class="$page.url === '/customer' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                            class="flex items-center gap-3 rounded-lg px-3 py-2   transition-all hover:text-primary">
                        <UserRound class="h-4 w-4" />
                        Customer
                        </Link>
                        <Link href="/category"
                            :class="$page.url === '/category' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                            class="flex items-center gap-3 rounded-lg px-3 py-2   transition-all hover:text-primary">
                        <ChartBarStacked class="h-4 w-4" />
                        Category
                        </Link>
                        <Link href="/products"
                            :class="$page.url === '/product' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                            class="flex items-center gap-3 rounded-lg px-3 py-2   transition-all hover:text-primary">
                        <Package class="h-4 w-4" />
                        Product
                        </Link>
                        <Link href="/sale"
                            :class="$page.url === '/sale' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                            class="flex items-center gap-3 rounded-lg px-3 py-2   transition-all hover:text-primary">
                        <DollarSign class="h-4 w-4" />
                        Create Sale
                        </Link>
                        <Link href="/invoice"
                            :class="$page.url === '/invoice' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                            class="flex items-center gap-3 rounded-lg px-3 py-2   transition-all hover:text-primary">
                        <ChartNoAxesColumn class="h-4 w-4" />
                        Invoice
                        </Link>
                         
                    </nav>
                </div>


            </div>
        </div>

        <div class="flex flex-col">
            <header class="flex h-14 items-center gap-4 border-b bg-muted/40 px-4 lg:h-[60px] lg:px-6">
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="outline" size="icon" class="shrink-0 md:hidden">
                            <Menu class="h-5 w-5" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left" class="flex flex-col">
                        <nav class="grid gap-2 text-lg font-medium">
                            <Link href="#" class="flex items-center gap-2 text-lg font-semibold">
                            <Package2 class="h-6 w-6" />
                            <span>Ostad Inventory</span>
                            </Link>
                            <Link href="/"
                                :class="$page.url === '/' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2  hover:text-foreground">
                            <Home class="h-5 w-5" />
                            Dashboard
                            </Link>
                            <Link href="/customer"
                                :class="$page.url === '/customer' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2  hover:text-foreground">
                            <Users class="h-5 w-5" />
                            Customer
                            </Link>
                            <Link href="/category"
                                :class="$page.url === '/category' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2  hover:text-foreground">
                            <ChartBarStacked class="h-5 w-5" />
                            Category
                            </Link>

                            <Link href="/products"
                                :class="$page.url === '/products' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2  hover:text-foreground">
                            <Package class="h-5 w-5" />
                            Product
                            </Link>

                            <Link href="/sale"
                                :class="$page.url === '/sale' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2  hover:text-foreground">
                            <LineChart class="h-5 w-5" />
                            Create Sale
                            </Link>

                            <Link href="/invoice"
                                :class="$page.url === '/invoice' ? 'bg-muted text-primary' : 'text-muted-foreground'"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2  hover:text-foreground">
                            <ChartNoAxesColumn class="h-5 w-5" />
                            Invoice
                            </Link>
                           
                        </nav>

                    </SheetContent>
                </Sheet>
                <div class="w-full">
                    
                </div>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="secondary" size="icon" class="rounded-full">
                            <CircleUser class="h-5 w-5" />
                            <span class="sr-only">Toggle user menu</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>My Account</DropdownMenuLabel>
                        <DropdownMenuItem @click="logout">Logout</DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </header>
            <main class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">

                <div class="flex flex-1  rounded-lg border border-dashed shadow-sm">

                    <slot />

                </div>
            </main>
        </div>
    </div>
</template>