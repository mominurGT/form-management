<script setup lang="ts">
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

const props = defineProps<{
  field: {
    id: string;
    type: string;
    label: string;
    name: string;
    required: boolean;
    options?: string[];
  };
}>();

const { attributes, listeners, setNodeRef, transform, transition } = useSortable({ id: props.field.id });

const style = {
  transform: CSS.Transform.toString(transform),
  transition,
};
</script>

<template>
  <div :ref="setNodeRef" :style="style" v-bind="attributes" v-on="listeners"
       class="p-3 mb-2 border rounded bg-white shadow-sm">
    <label class="block font-semibold">{{ field.label }}</label>
    <input v-if="field.type === 'text'" type="text" class="input" disabled />
    <select v-if="field.type === 'select'" class="input" disabled>
      <option v-for="opt in field.options" :key="opt">{{ opt }}</option>
    </select>
  </div>
</template>
