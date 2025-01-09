<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Available Garages vue component</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="garage in garages" :key="garage.id" 
                class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-xl font-semibold">{{ garage.name }}</h2>
                <p class="text-gray-600">{{ garage.description }}</p>
                <div class="mt-4">
                    <span :class="garage.is_available ? 'text-green-600' : 'text-red-600'">
                        {{ garage.is_available ? 'Available' : 'Not Available' }}
                    </span>
                </div>
                <button 
                    @click="openBookingModal(garage)"
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    :disabled="!garage.is_available">
                    Book Now
                </button>
            </div>
        </div>

        <!-- Booking Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Book Garage</h2>
                <form @submit.prevent="createBooking">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Start Time</label>
                        <input type="datetime-local" v-model="bookingForm.start_time" 
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">End Time</label>
                        <input type="datetime-local" v-model="bookingForm.end_time" 
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showModal = false" 
                            class="px-4 py-2 border rounded">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const garages = ref([]);
const showModal = ref(false);
const selectedGarage = ref(null);
const bookingForm = ref({
    start_time: '',
    end_time: '',
});

onMounted(async () => {
    try {
        const response = await axios.get('/api/garages');
        garages.value = response.data.data;
    } catch (error) {
        console.error('Error fetching garages:', error);
    }
});

const openBookingModal = (garage) => {
    selectedGarage.value = garage;
    showModal.value = true;
};

const createBooking = async () => {
    try {
        await axios.post('/api/bookings', {
            garage_id: selectedGarage.value.id,
            start_time: bookingForm.value.start_time,
            end_time: bookingForm.value.end_time,
        });
        showModal.value = false;
        // Optionally show success message
    } catch (error) {
        console.error('Error creating booking:', error);
        // Show error message to user
    }
};
</script>
