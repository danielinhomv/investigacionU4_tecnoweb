<script setup>
import { ref, computed } from 'vue'
import { useForm, Link, usePage  } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    persona: Object,
})

const isEditing = computed(() => !!props.persona)
const photoPreview = ref(null)
const photoInput = ref(null)
const { appUrl } = usePage().props

const form = useForm({
    per_cod:   props.persona?.per_cod?.trim()   ?? '',
    per_nom:   props.persona?.per_nom?.trim()   ?? '',
    per_appm:  props.persona?.per_appm?.trim()  ?? '',
    per_prof:  props.persona?.per_prof?.trim()  ?? '',
    per_telf:  props.persona?.per_telf?.trim()  ?? '',
    per_cel:   props.persona?.per_cel?.trim()   ?? '',
    per_email: props.persona?.per_email?.trim() ?? '',
    per_dir:   props.persona?.per_dir?.trim()   ?? '',
    per_fnac:  props.persona?.per_fnac          ?? '',
    per_lnac:  props.persona?.per_lnac?.trim()  ?? '',
    per_est:   props.persona?.per_est           ?? true,
    per_foto:  null,
})

function selectPhoto() {
    photoInput.value.click()
}

function onPhotoChange(e) {
    const file = e.target.files[0]
    if (!file) return
    form.per_foto = file
    const reader = new FileReader()
    reader.onload = (ev) => { photoPreview.value = ev.target.result }
    reader.readAsDataURL(file)
}

function submit() {
    if (isEditing.value) {
        form.transform((data) => {
            const payload = { ...data, _method: 'PUT' }
            if (!payload.per_foto) {
                delete payload.per_foto  // ← no enviar si no cambió
            }
            return payload
        }).post(route('personas.update', props.persona.per_cod.trim()), {
            forceFormData: true,
        })
    } else {
        form.post(route('personas.store'), {
            forceFormData: true,
        })
    }
}
</script>

<template>
    <AppLayout :title="isEditing ? 'Editar Persona' : 'Nueva Persona'">
        <template #header>
            <div class="flex items-center gap-3">
                <Link
                    :href="route('personas.index')"
                    class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ isEditing ? 'Editar Persona' : 'Nueva Persona' }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                        <!-- Foto -->
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-6">
                            <div class="relative">
                                <img
                                    :src="photoPreview ?? (persona ? `${appUrl}/storage/${persona.per_foto}` :`${appUrl}/img/placeholder.png`)"
                                    class="h-20 w-20 rounded-full object-cover ring-4 ring-gray-100 dark:ring-gray-700"
                                />
                                <button
                                    type="button"
                                    @click="selectPhoto"
                                    class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-1.5 shadow transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto de perfil</p>
                                <p class="text-xs text-gray-400 mt-0.5">JPG, PNG. Máx 2MB</p>
                                <p v-if="form.errors.per_foto" class="text-xs text-red-500 mt-1">{{ form.errors.per_foto }}</p>
                            </div>
                            <input ref="photoInput" type="file" accept="image/*" class="hidden" @change="onPhotoChange" />
                        </div>

                        <!-- Campos -->
                        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <!-- Código -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Código <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_cod"
                                    type="text"
                                    maxlength="14"
                                    :disabled="isEditing"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition dark:bg-gray-700 dark:text-gray-200',
                                        isEditing ? 'bg-gray-50 text-gray-400 cursor-not-allowed border-gray-200 dark:border-gray-600' : 'border-gray-300 dark:border-gray-600',
                                        form.errors.per_cod ? 'border-red-400' : '']"
                                    placeholder="Ej: 12345678"
                                />
                                <p v-if="form.errors.per_cod" class="text-xs text-red-500 mt-1">{{ form.errors.per_cod }}</p>
                            </div>

                            <!-- Estado -->
                            <div class="flex items-end pb-1">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <div
                                        @click="form.per_est = !form.per_est"
                                        :class="form.per_est ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative w-10 h-5 rounded-full transition-colors duration-200 cursor-pointer"
                                    >
                                        <span
                                            :class="form.per_est ? 'translate-x-5' : 'translate-x-1'"
                                            class="absolute top-0.5 h-4 w-4 bg-white rounded-full shadow transition-transform duration-200"
                                        />
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ form.per_est ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </label>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Nombre(s) <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_nom"
                                    type="text"
                                    maxlength="150"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_nom ? 'border-red-400' : '']"
                                    placeholder="Nombre(s)"
                                />
                                <p v-if="form.errors.per_nom" class="text-xs text-red-500 mt-1">{{ form.errors.per_nom }}</p>
                            </div>

                            <!-- Apellido -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Apellido <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_appm"
                                    type="text"
                                    maxlength="150"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_appm ? 'border-red-400' : '']"
                                    placeholder="Apellido"
                                />
                                <p v-if="form.errors.per_appm" class="text-xs text-red-500 mt-1">{{ form.errors.per_appm }}</p>
                            </div>

                            <!-- Profesión -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Profesión <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_prof"
                                    type="text"
                                    maxlength="100"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_prof ? 'border-red-400' : '']"
                                    placeholder="Profesión u ocupación"
                                />
                                <p v-if="form.errors.per_prof" class="text-xs text-red-500 mt-1">{{ form.errors.per_prof }}</p>
                            </div>

                            <!-- Fecha de nacimiento -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Fecha de Nacimiento <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_fnac"
                                    type="date"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_fnac ? 'border-red-400' : '']"
                                />
                                <p v-if="form.errors.per_fnac" class="text-xs text-red-500 mt-1">{{ form.errors.per_fnac }}</p>
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Teléfono <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_telf"
                                    type="text"
                                    maxlength="17"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_telf ? 'border-red-400' : '']"
                                    placeholder="Teléfono fijo"
                                />
                                <p v-if="form.errors.per_telf" class="text-xs text-red-500 mt-1">{{ form.errors.per_telf }}</p>
                            </div>

                            <!-- Celular -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Celular <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_cel"
                                    type="text"
                                    maxlength="17"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_cel ? 'border-red-400' : '']"
                                    placeholder="Número celular"
                                />
                                <p v-if="form.errors.per_cel" class="text-xs text-red-500 mt-1">{{ form.errors.per_cel }}</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Email <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_email"
                                    type="email"
                                    maxlength="50"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_email ? 'border-red-400' : '']"
                                    placeholder="correo@ejemplo.com"
                                />
                                <p v-if="form.errors.per_email" class="text-xs text-red-500 mt-1">{{ form.errors.per_email }}</p>
                            </div>

                            <!-- Lugar de nacimiento -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Lugar de Nacimiento <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_lnac"
                                    type="text"
                                    maxlength="150"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_lnac ? 'border-red-400' : '']"
                                    placeholder="Ciudad, País"
                                />
                                <p v-if="form.errors.per_lnac" class="text-xs text-red-500 mt-1">{{ form.errors.per_lnac }}</p>
                            </div>

                            <!-- Dirección -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Dirección <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.per_dir"
                                    type="text"
                                    maxlength="150"
                                    :class="['w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200',
                                        form.errors.per_dir ? 'border-red-400' : '']"
                                    placeholder="Dirección completa"
                                />
                                <p v-if="form.errors.per_dir" class="text-xs text-red-500 mt-1">{{ form.errors.per_dir }}</p>
                            </div>

                        </div>

                        <!-- Footer botones -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-600 flex items-center justify-end gap-3">
                            <Link
                                :href="route('personas.index')"
                                class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg shadow-sm transition-colors"
                            >
                                <svg v-if="form.processing" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                                {{ form.processing ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Guardar') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>