<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    persona: Object,
})

const { appUrl } = usePage().props


function formatDate(date) {
    if (!date) return '—'
    return new Date(date).toLocaleDateString('es-BO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    })
}
</script>

<template>
    <AppLayout :title="persona.per_nom.trim() + ' ' + persona.per_appm.trim()">
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
                    Detalle de Persona
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Tarjeta principal -->
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden">

                    <!-- Banner + foto -->
                    <div class="h-24 bg-gradient-to-r from-indigo-500 to-indigo-700 relative">
                        <div class="absolute -bottom-10 left-6">
                            <template v-if="persona.per_foto && persona.per_foto.trim() !== 'img/placeholder.png'">
                                <img
                                    :src="`${appUrl}/storage/${persona.per_foto}`"
                                    :alt="persona.per_nom"
                                    class="h-20 w-20 rounded-full object-cover ring-4 ring-white dark:ring-gray-800 shadow-lg"
                                />
                            </template>
                            <template v-else>
                                <div class="h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-600 ring-4 ring-white dark:ring-gray-800 shadow-lg flex items-center justify-center">
                                    <i class="fa-solid fa-user text-3xl text-gray-400 dark:text-gray-300"></i>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Nombre + acciones -->
                    <div class="pt-14 pb-5 px-6 flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ persona.per_nom.trim() }} {{ persona.per_appm.trim() }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ persona.per_prof.trim() }}
                            </p>
                            <span
                                :class="persona.per_est
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-600'"
                                class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium"
                            >
                                {{ persona.per_est ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <Link
                                :href="route('personas.edit', persona.per_cod.trim())"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </Link>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700" />

                    <!-- Datos en grid -->
                    <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Código -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Código</p>
                            <p class="text-sm font-mono text-gray-800 dark:text-gray-200">{{ persona.per_cod.trim() }}</p>
                        </div>

                        <!-- Fecha de nacimiento -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Fecha de Nacimiento</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ formatDate(persona.per_fnac) }}</p>
                        </div>

                        <!-- Lugar de nacimiento -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Lugar de Nacimiento</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ persona.per_lnac.trim() }}</p>
                        </div>

                        <!-- Profesión -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Profesión</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ persona.per_prof.trim() }}</p>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Teléfono</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-phone text-gray-400 text-xs"></i>
                                {{ persona.per_telf.trim() || '—' }}
                            </p>
                        </div>

                        <!-- Celular -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Celular</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-mobile-screen text-gray-400 text-xs"></i>
                                {{ persona.per_cel.trim() || '—' }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Email</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-gray-400 text-xs"></i>
                                <a :href="`mailto:${persona.per_email.trim()}`" class="text-indigo-600 hover:underline">
                                    {{ persona.per_email.trim() }}
                                </a>
                            </p>
                        </div>

                        <!-- Dirección -->
                        <div>
                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Dirección</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-gray-400 text-xs"></i>
                                {{ persona.per_dir.trim() || '—' }}
                            </p>
                        </div>

                    </div>

                    <!-- Footer fechas -->
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-600 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500">
                        <span>Registrado: {{ formatDate(persona.per_create) }}</span>
                        <span>Actualizado: {{ formatDate(persona.per_update) }}</span>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>