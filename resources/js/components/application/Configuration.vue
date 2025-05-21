<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'

const inputMethod = ref<'file' | 'text' | null>(null)
const jsonText = ref<string>('')
const jsonFile = ref<File | null>(null)

function handleFileUpload(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (file) {
    jsonFile.value = file

    const reader = new FileReader()
    reader.onload = (e) => {
      jsonText.value = e.target?.result as string
    }
    reader.readAsText(file)
  }
}

async function submitJson() {
  try {
    JSON.parse(jsonText.value)
  } catch {
    alert('Invalid JSON data')
    return
  }

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (!csrfToken) {
      alert('CSRF token not found')
      return
    }

    let body: BodyInit
    let headers: HeadersInit = {
      'X-CSRF-TOKEN': csrfToken,
    }

    if (jsonFile.value) {
      const formData = new FormData()
      formData.append('jsonFile', jsonFile.value)
      body = formData
    } else {
      headers['Content-Type'] = 'application/json'
      body = jsonText.value
    }

    const res = await fetch('/save-json', {
      method: 'POST',
      headers,
      body,
    })

    if (res.ok) {
      alert('Form Configuration JSON data saved successfully')
      jsonText.value = ''
      jsonFile.value = null
      inputMethod.value = null
    } else {
      const errorData = await res.json().catch(() => null)
      alert('Failed to save JSON data: ' + (errorData?.message || res.statusText))
    }
  } catch (error) {
    alert('Network error while saving JSON data')
  }
}
</script>

<template>

  <div class="min-h-screen flex flex-col items-center justify-start p-6 bg-gray-100 pt-16">
    <h2 class="mb-6 text-xl font-semibold">Upload or Paste JSON Data</h2>
    <div class="flex gap-4 mb-6">
      <Button variant="outline" @click="inputMethod = 'file'">Upload JSON File</Button>
      <Button variant="outline" @click="inputMethod = 'text'">Paste JSON Text</Button>
    </div>

    <div v-if="inputMethod === 'file'"
      class="border-2 border-dashed border-gray-300 rounded-lg p-6 w-full max-w-xl text-center hover:border-blue-500 transition-colors duration-300">
      <label for="json-upload" class="cursor-pointer block text-sm text-gray-500 hover:text-blue-600">
        <span>Click to upload or drag and drop your JSON file here</span>
      </label>
      <input id="json-upload" type="file" accept=".json" class="hidden" @change="handleFileUpload" />

      <p v-if="jsonFile" class="mt-4 text-sm text-gray-600">Selected file: <strong>{{ jsonFile.name }}</strong></p>
    </div>

    <div v-if="inputMethod === 'text'" class="w-full max-w-xl">
      <textarea v-model="jsonText" rows="10" class="w-full p-4 border border-black rounded-md font-mono text-sm"
        placeholder="Paste your JSON data here..."></textarea>
    </div>

    <Button class="mt-6" :disabled="!jsonText.trim()" @click="submitJson">
      Submit JSON Data
    </Button>
  </div>
</template>
