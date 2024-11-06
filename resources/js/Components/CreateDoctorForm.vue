<template>
    <div class="doctorForm">
        <h2 class="text-lg font-bold text-white">Criar Doutor</h2>
        <form @submit.prevent="submit">
            <div>
                <label for="name" class="block text-white">Nome</label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    class="mt-1 block w-full border rounded"
                    required
                />
            </div>
            <div class="mt-4">
                <label for="specialty" class="block text-white">Especialidade</label>
                <input
                    id="specialty"
                    type="text"
                    v-model="form.specialty"
                    class="mt-1 block w-full border rounded"
                    required
                />
            </div>
            <div class="mt-4">
                <label for="email" class="block text-white">Email</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    class="mt-1 block w-full border rounded"
                    required
                />
            </div>
            <div class="mt-4">
                <label for="phone" class="block text-white">Telefone</label>
                <input
                    id="phone"
                    type="text"
                    v-model="form.phone"
                    class="mt-1 block w-full border rounded"
                    required
                />
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Criar Doutor
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const form = ref({
    name: '',
    specialty: '',
    email: '',
    phone: ''
});

const successMessage = ref('');
const errorMessage = ref('');

// Função para limpar mensagens de alert
const clearMessage = () => {
    successMessage.value = '';
    errorMessage.value = '';
};

const submit = async () => {
  try {
    await axios.post('/doctors', form.value);
    successMessage.value = "Doutor cadastrado com sucesso!";
    emit('alert', { type: 'success', message: successMessage.value });
    form.value = { name: '', specialty: '', email: '', phone: '' };
  } catch (error) {
    errorMessage.value = "Erro ao cadastrar o doutor.";
    emit('alert', { type: 'error', message: errorMessage.value });
  }
};
</script>

<style scoped>
.doctorForm {
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
