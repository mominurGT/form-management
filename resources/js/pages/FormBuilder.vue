<script setup lang="ts">
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@/types'

interface Option {
    label: string
    value: string
}

interface Field {
    id: string
    name: string
    label: string
    type: string
    placeholder?: string
    required?: boolean
    min?: number | string
    max?: number | string
    minLength?: number
    maxLength?: number
    pattern?: string
    step?: number
    accept?: string
    options?: Option[]
    autocomplete?: string
    layout?: 'horizontal' | 'vertical'
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Form Builder', href: '/view' },
]

const props = defineProps<{
    id: number
    configuration: {
        submitText: string
        fields: Field[]
    }
}>()

const availableFields = ref<Field[]>(props.configuration.fields)
const formFields = ref<Field[]>([])
const formLayout = ref<'vertical' | 'horizontal'>('vertical')

const isEditModalOpen = ref(false)
const editingIndex = ref<number | null>(null)
const editingField = ref<Field | null>(null)
const optionsInput = ref('')

function addField(field: Field) {
    formFields.value.push({
        ...field,
        id: `${field.name}-${Date.now()}-${Math.random().toString(36).substr(2, 5)}`,
        layout: formLayout.value,
    })
}

watch(formLayout, (newLayout) => {
    formFields.value = formFields.value.map(field => ({
        ...field,
        layout: newLayout,
    }))
})

function openEditModal(index: number) {
    editingIndex.value = index
    editingField.value = JSON.parse(JSON.stringify(formFields.value[index]))
    if (editingField.value.options) {
        optionsInput.value = editingField.value.options.map(o => `${o.label}:${o.value}`).join('\n')
    } else {
        optionsInput.value = ''
    }
    isEditModalOpen.value = true
}

function closeEditModal() {
    isEditModalOpen.value = false
    editingIndex.value = null
    editingField.value = null
    optionsInput.value = ''
}

function saveEditModal() {
    if (editingIndex.value === null || !editingField.value) return

    if (
        editingField.value.type === 'select' ||
        editingField.value.type === 'radio' ||
        editingField.value.type === 'checkbox'
    ) {
        editingField.value.options = optionsInput.value
            .split('\n')
            .filter(line => line.trim() !== '')
            .map(line => {
                const [label, value] = line.split(':')
                return {
                    label: label.trim(),
                    value: value?.trim() || label.trim(),
                }
            })
        // Clear validation fields not applicable here:
        delete editingField.value.min
        delete editingField.value.max
        delete editingField.value.minLength
        delete editingField.value.maxLength
        delete editingField.value.pattern
        delete editingField.value.step
    }

    formFields.value[editingIndex.value] = editingField.value
    closeEditModal()
}
</script>

<template>

    <Head title="Edit Form Configuration" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 max-w-6xl mx-auto">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-xl font-semibold">Form Builder</h3>
                        <div class="flex items-center gap-3">
                            <label class="font-medium">Layout:</label>
                            <select v-model="formLayout" class="border px-2 py-1 rounded">
                                <option value="vertical">Vertical</option>
                                <option value="horizontal">Horizontal</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-6">
                        <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl shadow-md border p-6">
                            <draggable v-model="formFields" item-key="id" class="space-y-4">
                                <template #item="{ element, index }">
                                    <div class="resize-x overflow-auto min-w-[250px] max-w-full p-4 border rounded shadow-sm bg-white dark:bg-gray-900 flex flex-col gap-2"
                                        :class="element.layout === 'horizontal' ? 'flex items-center gap-4' : 'flex flex-col'">
                                        <div class="flex justify-between items-center w-full">
                                            <label class="font-semibold"
                                                :class="element.layout === 'horizontal' ? 'w-32 text-right' : 'mb-1'">
                                                {{ element.label }}
                                            </label>
                                            <button @click="openEditModal(index)"
                                                class="text-sm text-blue-600 hover:underline">
                                                Edit
                                            </button>
                                        </div>

                                        <!-- Input preview -->
                                        <template
                                            v-if="['text', 'email', 'password', 'number', 'tel', 'url', 'date', 'datetime-local', 'month', 'week', 'time', 'search', 'color', 'range', 'hidden'].includes(element.type)">
                                            <input :type="element.type" class="border px-3 py-2 rounded w-full"
                                                :placeholder="element.placeholder || ''" />
                                        </template>

                                        <template v-else-if="element.type === 'textarea'">
                                            <textarea class="border px-3 py-2 rounded w-full"
                                                :placeholder="element.placeholder || ''"></textarea>
                                        </template>

                                        <template v-else-if="element.type === 'select'">
                                            <select class="border px-3 py-2 rounded w-full">
                                                <option disabled value="">Select an option</option>
                                                <option v-for="opt in element.options || []" :key="opt.value"
                                                    :value="opt.value">
                                                    {{ opt.label }}
                                                </option>
                                            </select>
                                        </template>

                                        <template v-else-if="element.type === 'radio' || element.type === 'checkbox'">
                                            <div class="flex flex-wrap gap-4">
                                                <label v-for="opt in element.options || []" :key="opt.value"
                                                    class="flex items-center gap-1">
                                                    <input :type="element.type" :value="opt.value"
                                                        :name="element.name" />
                                                    {{ opt.label }}
                                                </label>
                                            </div>
                                        </template>

                                        <template v-else-if="element.type === 'file'">
                                            <input type="file" class="border px-3 py-2 rounded w-full"
                                                :accept="element.accept" />
                                        </template>

                                        <template v-else>
                                            <p class="text-red-600">
                                                Unsupported field type: {{ element.type }}
                                            </p>
                                        </template>
                                    </div>
                                </template>
                            </draggable>
                        </div>

                        <div class="w-48 flex flex-col gap-3 justify-start max-h-[400px] overflow-y-auto">
                            <button v-for="field in availableFields" :key="field.name" @click="addField(field)"
                                class="bg-green-600 text-white px-2 py-1 text-sm rounded-full hover:bg-green-700 whitespace-nowrap">
                                {{ field.label }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Field Modal -->
        <div v-if="isEditModalOpen" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
            <div
                class="bg-white dark:bg-gray-900 rounded-lg max-w-3xl w-full p-6 shadow-lg max-h-[80vh] overflow-y-auto">
                <h3 class="text-lg font-semibold mb-4">Edit Field</h3>

                <form @submit.prevent="saveEditModal" class="space-y-4 text-sm">
                    <div>
                        <label class="block mb-1 font-medium">Label</label>
                        <input v-model="editingField.label" type="text" class="w-full border px-3 py-2 rounded"
                            required />
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Placeholder</label>
                        <input v-model="editingField.placeholder" type="text" class="w-full border px-3 py-2 rounded"
                            :disabled="['select', 'radio', 'checkbox', 'file'].includes(editingField.type)" />
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input v-model="editingField.required" type="checkbox" class="mr-2" />
                            Required
                        </label>
                    </div>

                    <!-- Numeric and pattern validations for applicable types -->
                    <template
                        v-if="['text', 'email', 'password', 'number', 'tel', 'url', 'date', 'datetime-local', 'month', 'week', 'time', 'search', 'color', 'range'].includes(editingField.type)">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1 font-medium">Min</label>
                                <input v-model="editingField.min"
                                    :type="editingField.type === 'number' || editingField.type === 'range' ? 'number' : 'text'"
                                    class="w-full border px-3 py-2 rounded" />
                            </div>

                            <div>
                                <label class="block mb-1 font-medium">Max</label>
                                <input v-model="editingField.max"
                                    :type="editingField.type === 'number' || editingField.type === 'range' ? 'number' : 'text'"
                                    class="w-full border px-3 py-2 rounded" />
                            </div>

                            <div>
                                <label class="block mb-1 font-medium">Min Length</label>
                                <input v-model.number="editingField.minLength" type="number"
                                    class="w-full border px-3 py-2 rounded" min="0" />
                            </div>

                            <div>
                                <label class="block mb-1 font-medium">Max Length</label>
                                <input v-model.number="editingField.maxLength" type="number"
                                    class="w-full border px-3 py-2 rounded" min="0" />
                            </div>

                            <div class="col-span-2">
                                <label class="block mb-1 font-medium">Pattern (Regex)</label>
                                <input v-model="editingField.pattern" type="text"
                                    class="w-full border px-3 py-2 rounded" placeholder="e.g. ^[A-Za-z]+$" />
                            </div>

                            <div>
                                <label class="block mb-1 font-medium">Step</label>
                                <input v-model.number="editingField.step" type="number"
                                    class="w-full border px-3 py-2 rounded"
                                    :disabled="editingField.type !== 'number' && editingField.type !== 'range'" />
                            </div>
                        </div>
                    </template>

                    <div v-if="['select', 'radio', 'checkbox'].includes(editingField.type)">
                        <label class="block mb-1 font-medium">Options (one per line, format: label:value)</label>
                        <textarea v-model="optionsInput" rows="5"
                            class="w-full border px-3 py-2 rounded font-mono text-xs"></textarea>
                    </div>

                    <div v-if="editingField.type === 'file'">
                        <label class="block mb-1 font-medium">Accept (file types)</label>
                        <input v-model="editingField.accept" type="text" placeholder="e.g. image/*,.pdf"
                            class="w-full border px-3 py-2 rounded" />
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Layout</label>
                        <select v-model="editingField.layout" class="w-full border px-3 py-2 rounded">
                            <option value="vertical">Vertical</option>
                            <option value="horizontal">Horizontal</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" @click="closeEditModal"
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
