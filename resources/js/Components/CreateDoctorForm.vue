<template>
    <div>
        <h2 class="text-lg font-bold">Criar Doutor</h2>
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
        // Envia o formulário para criar um doutor
        await axios.post('/doctors', form.value);

        // Define mensagem de sucesso e reseta o formulário
        successMessage.value = "Doutor cadastrado com sucesso!";
        form.value = { name: '', specialty: '', email: '', phone: '' };
    } catch (error) {
        // Define mensagem de erro em caso de falha
        errorMessage.value = "Erro ao cadastrar o doutor.";
        console.error(error);
    }
};
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
