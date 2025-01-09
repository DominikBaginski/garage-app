<template>
    <div class="space-y-6">
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error! vue component</strong>
            <span class="block sm:inline">{{ error }}</span>
        </div>

        <div v-if="loading" class="text-center py-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
            <p class="mt-2 text-gray-600">Loading dashboard data...</p>
        </div>

        <template v-else>
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Total Garages</h3>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ stats.totalGarages }}</p>
                    <p class="text-sm text-gray-500">{{ stats.availableGarages }} available</p>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Active Bookings</h3>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ stats.activeBookings }}</p>
                    <p class="text-sm text-gray-500">{{ stats.pendingBookings }} pending</p>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Today's Bookings</h3>
                    <p class="mt-2 text-3xl font-bold text-purple-600">{{ stats.todayBookings }}</p>
                    <p class="text-sm text-gray-500">{{ stats.upcomingBookings }} upcoming</p>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
                    <div v-if="recentBookings.length === 0" class="text-center py-4 text-gray-500">
                        No recent bookings found
                    </div>
                    <div v-else class="space-y-4">
                        <div v-for="booking in recentBookings" :key="booking.id" class="flex items-center justify-between border-b pb-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ booking.garage?.name }}</p>
                                <p class="text-sm text-gray-500">{{ formatDate(booking.start_time) }} - {{ formatDate(booking.end_time) }}</p>
                            </div>
                            <span :class="[
                                'px-2 py-1 text-xs font-semibold rounded-full',
                                booking.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                booking.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800'
                            ]">
                                {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const stats = ref({
    totalGarages: 0,
    availableGarages: 0,
    activeBookings: 0,
    pendingBookings: 0,
    todayBookings: 0,
    upcomingBookings: 0
});

const recentBookings = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchStats = async () => {
    try {
        loading.value = true;
        error.value = null;
        
        console.log('Fetching dashboard stats...');
        const response = await axios.get('/dashboard');
        console.log('Dashboard response:', response.data);
        
        if (response.data && response.data.stats) {
            stats.value = response.data.stats;
            recentBookings.value = response.data.recentBookings || [];
        } else {
            throw new Error('Invalid response format from API');
        }
    } catch (err) {
        console.error('Error fetching dashboard data:', err);
        error.value = err.response?.data?.message || 'Failed to load dashboard data. Please try again later.';
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    try {
        return new Date(dateString).toLocaleString();
    } catch (err) {
        console.error('Date formatting error:', err);
        return dateString;
    }
};

onMounted(() => {
    console.log('Dashboard component mounted');
    fetchStats();
});
</script>
