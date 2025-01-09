<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <router-link to="/" class="text-xl font-bold text-gray-800">
                                Garage App
                            </router-link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <router-link to="/" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300"
                                :class="{ 'border-indigo-500 text-gray-900': $route.path === '/' }">
                                Dashboard
                            </router-link>
                            <router-link to="/garages" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300"
                                :class="{ 'border-indigo-500 text-gray-900': $route.path === '/garages' }">
                                Garages
                            </router-link>
                            <router-link to="/bookings" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300"
                                :class="{ 'border-indigo-500 text-gray-900': $route.path === '/bookings' }">
                                Bookings
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <router-view></router-view>
            </div>
        </main>
    </div>
</template>

<script setup>
// No setup needed
</script>

<style scoped>
/* Component styles will go here */
</style>
