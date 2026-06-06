<script setup>
import { ref, watch, computed } from 'vue'
import { router, Link, Head, usePage  } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    personas: Object,
    filters: Object,
})

const search = ref(props.filters?.search ?? '')
const page = usePage()
const appUrl = computed(() => page.props.appUrl)
console.log("La URL actual es:", appUrl.value)

watch(search, (value) => {
    router.get(route('personas.index'), { search: value }, {
        preserveState: true,
        replace: true,
    })
})

function destroy(perCod, nombre) {
    if (confirm(`¿Eliminar a ${nombre}?`)) {
        router.delete(route('personas.destroy', perCod))
    }
}
</script>

<template>
    <AppLayout title="Personas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Personas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Buscador + Botón -->
                <div class="flex items-center justify-between mb-4">
                    <div class="relative max-w-sm w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Buscar por nombre, apellido o código..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        />
                    </div>
                    <Link
                        :href="route('personas.create')"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors duration-150"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva Persona
                    </Link>
                </div>

                <!-- Tabla -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre Completo</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Profesión</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Celular</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-if="personas.data.length === 0">
                                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                                        No se encontraron personas.
                                    </td>
                                </tr>
                                <tr
                                    v-for="persona in personas.data"
                                    :key="persona.per_cod"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-100"
                                >
                                    <td class="px-4 py-3">
                                        <img
                                            :src="`${appUrl}/storage/${persona.per_foto}`"
                                            :alt="persona.per_nom"
                                            class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-200"
                                            @error="$event.target.src=`${appUrl}/img/placeholder.png`"
                                        />
                                    </td>
                                    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">
                                        {{ persona.per_cod.trim() }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                        {{ persona.per_nom.trim() }} {{ persona.per_appm.trim() }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ persona.per_prof.trim() }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ persona.per_cel.trim() }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ persona.per_email.trim() }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            :class="persona.per_est
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-600'"
                                            class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        >
                                            {{ persona.per_est ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                         <Link
        :href="route('personas.show', persona.per_cod.trim())"
        class="inline-flex items-center gap-1 text-green-600 hover:text-green-800 text-xs font-medium transition-colors"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        Ver
    </Link>
    <span class="text-gray-300">|</span>
                                            <Link
                                                :href="route('personas.edit', persona.per_cod)"
                                                class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 text-xs font-medium transition-colors"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </Link>
                                            <span class="text-gray-300">|</span>
                                            <button
                                                @click="destroy(persona.per_cod.trim(), persona.per_nom.trim())"
                                                class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 text-xs font-medium transition-colors"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>
                            Mostrando {{ personas.from ?? 0 }}–{{ personas.to ?? 0 }} de {{ personas.total }} registros
                        </span>
                        <div class="flex gap-1">
                            <template v-for="link in personas.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    v-html="link.label"
                                    :class="[
                                        'px-3 py-1 rounded border text-xs transition-colors duration-100',
                                        link.active
                                            ? 'bg-indigo-600 text-white border-indigo-600'
                                            : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'
                                    ]"
                                />
                                <span
                                    v-else
                                    v-html="link.label"
                                    class="px-3 py-1 rounded border border-gray-100 dark:border-gray-700 text-xs text-gray-300 cursor-not-allowed"
                                />
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>