<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Central de Atendimentos
            </h2>
        </template>

        <!-- Alert -->
        <div v-if="alert.message" :class="`alert ${alert.type === 'success' ? 'alert-success' : 'alert-error'}`" role="alert">
            {{ alert.message }}
            <button type="button" class="close" @click="clearAlert">&times;</button>
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex space-x-4">
                    <template v-if="user && user.is_admin">
                        <button @click="openModalDoctor" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Cadastrar Doutor
                        </button>
                    </template>

                    <button @click="openModalAppointment" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Cadastrar Consulta
                    </button>

                    <button @click="fetchAppointments" class="bg-green-500 text-white px-4 py-2 rounded">
                        Ver Agendamentos
                    </button>
                </div>
            </div>
        </div>

        <Modal :show="isModalDoctorOpen" @close="closeModalDoctor">
            <CreateDoctorForm />
        </Modal>

        <Modal :show="isModalAppointmentOpen" @close="closeModalAppointment">
            <AppointmentForm :isModalOpen="isModalAppointmentOpen" @appointmentCreated="handleAppointmentCreated" />
        </Modal>

        <Modal :show="isModalAppointmentsOpen" @close="closeModalAppointments">
            <div v-if="appointments.length > 0">
                <h2 class="text-lg text-white font-semibold mb-4 p-4">Seus Agendamentos</h2>
                <ul>
                    <li v-for="appointment in appointments" :key="appointment.id" class="mb-2 text-white p-4">
                        <p><strong>Médico:</strong> {{ appointment.doctor.name }}</p>
                        <p><strong>Data:</strong> {{ appointment.date }}</p>
                        <p><strong>Duração:</strong> {{ appointment.duration }} minutos</p>
                        <div class="flex space-x-2 mt-2">
                            <button @click="openEditModal(appointment.id)" class="bg-yellow-500 text-white px-4 py-2 rounded">
                                Editar
                            </button>
                            <button @click="deleteAppointment(appointment.id)" class="bg-red-500 text-white px-4 py-2 rounded">
                                Excluir
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p>Nenhum agendamento encontrado.</p>
            </div>
        </Modal>

        <Modal :show="isEditModalOpen" @close="closeEditModal">
            <EditAppointmentForm :appointmentId="selectedAppointmentId" :doctors="doctorList" @close="closeEditModal" />
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AppointmentForm from "@/Components/AppointmentForm.vue";
import CreateDoctorForm from "@/Components/CreateDoctorForm.vue";
import EditAppointmentForm from "@/Components/EditAppointmentForm.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";

const { props } = usePage();
const user = props.auth.user;

// Alert state
const alert = ref({ type: "", message: "" });

// Function to display alert and close modal for AppointmentForm
const handleAppointmentCreated = (msg) => {
    showAlert(msg);
    closeModalAppointment(); // Close the appointment modal after success
};

// Function to show alert
const showAlert = (msg) => {
    alert.value = msg;
    setTimeout(() => {
        alert.value = { type: "", message: "" };
    }, 5000); // Clear alert after 5 seconds
};

// Function to clear alert manually
const clearAlert = () => {
    alert.value = { type: "", message: "" };
};

// Modal states
const isModalDoctorOpen = ref(false);
const isModalAppointmentOpen = ref(false);
const isModalAppointmentsOpen = ref(false);
const isEditModalOpen = ref(false);

// Appointments data
const appointments = ref([]);
const selectedAppointmentId = ref(null);
const doctorList = ref([]);

// Open and close modal functions
const openModalDoctor = () => (isModalDoctorOpen.value = true);
const closeModalDoctor = () => (isModalDoctorOpen.value = false);

const openModalAppointment = () => (isModalAppointmentOpen.value = true);
const closeModalAppointment = () => (isModalAppointmentOpen.value = false);

const closeModalAppointments = () => (isModalAppointmentsOpen.value = false);
const closeEditModal = () => {
    isEditModalOpen.value = false;
    selectedAppointmentId.value = null;
};

// Fetch appointments for user
const fetchAppointments = async () => {
    try {
        const response = await axios.get(`/appointments/user/${user.id}`);
        appointments.value = response.data.appointments;
        isModalAppointmentsOpen.value = true;
    } catch (error) {
        console.error("Erro ao buscar agendamentos:", error);
    }
};

// Open edit modal and load doctor list
const openEditModal = async (appointmentId) => {
    try {
        selectedAppointmentId.value = appointmentId;
        await loadDoctorList();
        isEditModalOpen.value = true;
    } catch (error) {
        console.error("Erro ao abrir modal de edição:", error);
    }
};

// Delete an appointment
const deleteAppointment = async (appointmentId) => {
    if (confirm("Tem certeza que deseja excluir este agendamento?")) {
        try {
            await axios.delete(`/appointments/${appointmentId}`);
            appointments.value = appointments.value.filter(
                (a) => a.id !== appointmentId
            );
        } catch (error) {
            console.error("Erro ao excluir agendamento:", error);
        }
    }
};

// Load doctor list
const loadDoctorList = async () => {
    try {
        const response = await axios.get("/doctors");
        doctorList.value = response.data.doctors;
    } catch (error) {
        console.error("Erro ao carregar lista de doutores:", error);
    }
};
</script>

<style scoped>
.alert {
    position: relative;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-error {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 0.75rem 1rem;
    color: inherit;
    background: none;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
}
</style>
