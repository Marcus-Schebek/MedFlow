<template>
  <Modal :show="show" @close="closeModal">
    <form @submit.prevent="submitForm" class="p-6">
      <h2 class="text-lg text-white">Editar Agendamento</h2>

      <!-- Alertas de Sucesso e Erro -->
      <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ successMessage }}
        <button type="button" class="close" @click="clearMessage">&times;</button>
      </div>
      <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ errorMessage }}
        <button type="button" class="close" @click="clearMessage">&times;</button>
      </div>

      <div class="mb-4">
        <label for="doctor" class="block text-white">Médico:</label>
        <select v-model="form.doctor_id" required>
          <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.id">{{ doctor.name }}</option>
        </select>
      </div>
      <div class="mb-4">
        <label for="date" class="block text-white">Data e Hora:</label>
        <input type="datetime-local" v-model="form.date" required />
      </div>
      <div class="mb-4">
        <label for="duration" class="block text-white">Duração (min):</label>
        <input type="number" v-model="form.duration" required />
      </div>
      <div class="flex justify-end">
        <button type="button" @click="handleClose" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
          Cancelar
        </button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
      </div>
    </form>
  </Modal>
</template>

<script setup>
import { ref, watch } from "vue";
import axios from "axios";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  show: Boolean,
  appointmentId: Number,
  closeModal: Function,
});

const emit = defineEmits(["close"]);

const form = ref({
  doctor_id: "",
  date: "",
  duration: "",
});

const doctors = ref([]);
const successMessage = ref('');
const errorMessage = ref('');

// Função para limpar mensagens de alert
const clearMessage = () => {
  successMessage.value = '';
  errorMessage.value = '';
};

// Buscar detalhes do agendamento e lista de médicos ao abrir o modal
watch(() => props.show, async (newValue) => {
  if (newValue) {
    await fetchDoctors();
    await fetchAppointmentDetails();
  }
});

const fetchDoctors = async () => {
  try {
    const response = await axios.get('/doctors');
    doctors.value = response.data.doctors;
  } catch (error) {
    console.error("Erro ao carregar doutores:", error);
  }
};

const fetchAppointmentDetails = async () => {
  try {
    const response = await axios.get(`/appointments/${props.appointmentId}`);
    form.value = response.data.appointment;
  } catch (error) {
    console.error("Erro ao buscar detalhes do agendamento:", error);
  }
};

const handleClose = () => {
  emit("close");
};

const submitForm = async () => {
  try {
    await axios.put(`/appointments/${props.appointmentId}`, form.value);
    emit('alert', { type: 'success', message: 'Agendamento atualizado com sucesso!' });
    handleClose();
  } catch (error) {
    emit('alert', { type: 'error', message: 'Erro ao atualizar agendamento.' });
  }
};
fetchDoctors();
fetchAppointmentDetails();
</script>

<style scoped>
.alert {
  position: relative;
  padding: 0.75rem 1.25rem;
  margin-bottom: 1rem;
  border: 1px solid transparent;
  border-radius: 0.25rem;
}

.alert-success {
  color: #155724;
  background-color: #d4edda;
  border-color: #c3e6cb;
}

.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}

.alert-dismissible .close {
  position: absolute;
  top: 0;
  right: 0;
  padding: 0.75rem 1.25rem;
  color: inherit;
  cursor: pointer;
}
</style>
