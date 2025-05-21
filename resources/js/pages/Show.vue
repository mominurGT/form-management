<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue'
import type { BreadcrumbItem } from '@/types'

interface Option {
    label: string
    value: string
}

interface Field {
    name: string
    label?: string
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
    value?: string
    options?: Option[]
    src?: string
    alt?: string
    autocomplete?: string
}

interface Configuration {
    fields: Field[]
}

const props = defineProps<{
    configuration?: Configuration
}>()

const formData = ref<Record<string, any>>({})

// Initialize with default values
watchEffect(() => {
    if (props.configuration?.fields) {
        props.configuration.fields.forEach(field => {
            if (!(field.name in formData.value)) {
                if (field.type === 'checkbox') {
                    formData.value[field.name] = []
                } else if (field.type === 'file') {
                    formData.value[field.name] = null
                } else {
                    formData.value[field.name] = field.value || ''
                }
            }
        })
    }
})

const submitForm = () => {
    router.post('/submit', formData.value)
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'View Configure JSON',
        href: '/show',
    },
]
</script>
<template>

    <Head title="View Configure JSON" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="max-w-xl mx-auto py-10">
                    <h2 class="text-2xl font-bold mb-6"> {{ props.configuration.title || 'Dynamic Form' }}</h2>

                    <form v-if="props.configuration?.fields" @submit.prevent="submitForm" class="space-y-4">
                        <div v-for="field in props.configuration.fields" :key="field.name" class="flex flex-col">
                            <label v-if="field.label && field.type !== 'hidden'" :for="field.name"
                                class="font-medium mb-1">
                                {{ field.label }}
                            </label>

                            <!-- Input types -->
                            <input
                                v-if="['text', 'email', 'password', 'number', 'tel', 'url', 'date', 'datetime-local', 'month', 'week', 'time', 'search', 'color', 'range', 'hidden'].includes(field.type)"
                                v-model="formData[field.name]" :type="field.type" :id="field.name"
                                :placeholder="field.placeholder || ''" :required="field.required" :min="field.min"
                                :max="field.max" :step="field.step" :pattern="field.pattern"
                                :minlength="field.minLength" :maxlength="field.maxLength"
                                :autocomplete="field.autocomplete" class="border p-2 rounded" />

                            <!-- Textarea -->
                            <textarea v-else-if="field.type === 'textarea'" v-model="formData[field.name]"
                                :id="field.name" :placeholder="field.placeholder || ''" :required="field.required"
                                :maxlength="field.maxLength" class="border p-2 rounded"></textarea>

                            <!-- Select -->
                            <select v-else-if="field.type === 'select'" v-model="formData[field.name]" :id="field.name"
                                :required="field.required" class="border p-2 rounded">
                                <option disabled value="">Select an option</option>
                                <option v-for="option in field.options || []" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>

                            <!-- Radio -->
                            <div v-else-if="field.type === 'radio'" :id="field.name">
                                <div v-for="option in field.options || []" :key="option.value"
                                    class="flex gap-2 items-center">
                                    <input type="radio" :name="field.name" :value="option.value"
                                        v-model="formData[field.name]" :required="field.required" />
                                    <label>{{ option.label }}</label>
                                </div>
                            </div>

                            <!-- Checkbox -->
                            <div v-else-if="field.type === 'checkbox'" :id="field.name">
                                <div v-for="option in field.options || []" :key="option.value"
                                    class="flex gap-2 items-center">
                                    <input type="checkbox" :value="option.value" v-model="formData[field.name]" />
                                    <label>{{ option.label }}</label>
                                </div>
                            </div>

                            <!-- File -->
                            <input v-else-if="field.type === 'file'" :id="field.name" type="file"
                                :required="field.required" :accept="field.accept" class="border p-2 rounded"
                                @change="(e) => formData[field.name] = (e.target as HTMLInputElement).files?.[0]" />

                            <!-- Image (clickable input image) -->
                            <input v-else-if="field.type === 'image'" type="image" :src="field.src"
                                :alt="field.alt || field.label || 'image input'" class="w-32 h-10 object-cover" />

                            <!-- Unsupported -->
                            <p v-else class="text-red-500">Unsupported field type: {{ field.type }}</p>
                        </div>

                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            {{ props.configuration.submitText || 'Submit' }}
                        </button>
                    </form>

                    <div v-else class="text-center text-gray-500">
                        Loading form configuration...
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
