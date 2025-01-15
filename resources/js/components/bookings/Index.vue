<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Bookings</h2>
                <button @click="showCreateModal = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    New Booking
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Garage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="booking in bookings" :key="booking.id">
                            <td class="px-6 py-4 whitespace-nowrap">{{ booking.garage_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(booking.start_time) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(booking.end_time) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    booking.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                    booking.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-red-100 text-red-800'
                                ]">
                                    {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button v-if="booking.status === 'pending'" @click="editBooking(booking)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button @click="deleteBooking(booking.id)" class="text-red-600 hover:text-red-900">Cancel</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-medium mb-4">{{ showEditModal ? 'Edit' : 'Create' }} Booking</h3>
            <form @submit.prevent="showEditModal ? updateBooking() : createBooking()">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Garage</label>
                    <select v-model="form.garage_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option v-for="garage in availableGarages" :key="garage.id" :value="garage.id">
                            {{ garage.name }}
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="datetime-local" v-model="form.start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="datetime-local" v-model="form.end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div v-if="showEditModal" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="closeModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const bookings = ref([]);
const availableGarages = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const form = ref({
    garage_id: '',
    start_time: '',
    end_time: '',
    status: 'pending'
});
const editingId = ref(null);

const fetchBookings = async () => {
    try {
        const response = await axios.get('/api/bookings');
        bookings.value = response.data.data;
    } catch (error) {
        console.error('Error fetching bookings:', error);
    }
};

const fetchAvailableGarages = async () => {
    try {
        const response = await axios.get('/api/garages');
        availableGarages.value = response.data.filter(garage => garage.is_available);
    } catch (error) {
        console.error('Error fetching garages:', error);
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString();
};

const createBooking = async () => {
    try {
        await axios.post('/api/bookings', form.value);
        await fetchBookings();
        closeModal();
    } catch (error) {
        console.error('Error creating booking:', error);
    }
};

const editBooking = (booking) => {
    editingId.value = booking.id;
    form.value = {
        garage_id: booking.garage_id,
        start_time: booking.start_time.slice(0, 16), // Format for datetime-local input
        end_time: booking.end_time.slice(0, 16),
        status: booking.status
    };
    showEditModal.value = true;
};

const updateBooking = async () => {
    try {
        await axios.put(`/api/bookings/${editingId.value}`, form.value);
        await fetchBookings();
        closeModal();
    } catch (error) {
        console.error('Error updating booking:', error);
    }
};

const deleteBooking = async (id) => {
    if (!confirm('Are you sure you want to cancel this booking?')) return;
    
    try {
        await axios.delete(`/api/bookings/${id}`);
        await fetchBookings();
    } catch (error) {
        console.error('Error cancelling booking:', error);
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    form.value = {
        garage_id: '',
        start_time: '',
        end_time: '',
        status: 'pending'
    };
    editingId.value = null;
};

onMounted(() => {
    fetchBookings();
    fetchAvailableGarages();
});
</script>
