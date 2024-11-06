<template>
  <div class="appointment-form">
    <h2 class="text-white">Cadastrar Consulta</h2>
    <form @submit.prevent="submitAppointment">
      <div class="form-group">
        <label for="doctor" class="text-white">Médico</label>
        <select v-model="form.doctor_id" id="doctor" required>
          <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.id">
            {{ doctor.name }} - {{ doctor.specialty }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label for="appointment_date" class="text-white">Data da Consulta</label>
        <input type="datetime-local" v-model="form.date" id="date" required>
      </div>
      <div class="form-group">
        <label for="duration" class="text-white">Duração (minutos)</label>
        <input type="number" v-model="form.duration" id="duration" required min="1">
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cadastrar</button>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, defineEmits } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

// Props e Eventos
const props = defineProps(['isModalOpen']);
const emit = defineEmits(['appointmentCreated']);

const form = useForm({
  doctor_id: '',
  date: '',
  duration: ''
});

const doctors = ref([]);

// Função para buscar lista de doutores
const fetchDoctors = () => {
  axios.get('/doctors')
    .then(response => {
      doctors.value = response.data.doctors;
    })
    .catch(error => {
      console.error("Erro ao carregar doutores:", error);
    });
};

// Função para submeter a consulta
const submitAppointment = () => {
  form.post(route('appointments.create'), {
    onSuccess: () => {
      emit("appointmentCreated", { type: "success", message: "Consulta cadastrada com sucesso!" });
      form.reset(); // Limpa o formulário
    },
    onError: () => {
      emit('appointmentCreated', { type: 'error', message: 'Erro ao cadastrar consulta.' });
    }
  });
};

// Resetar o formulário ao fechar o modal
watch(() => props.isModalOpen, (newVal) => {
  if (!newVal) form.reset();
});

// Carregar os doutores ao montar o componente
onMounted(() => {
  fetchDoctors();
});
</script>
<style scoped>
.appointment-form {
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input, select {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

button {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}
</style>
