<template>
  <div :class="cardClasses">
    <div v-if="$slots.header || title" class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <slot name="header">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ title }}</h3>
      </slot>
    </div>

    <div :class="['px-6', noPadding ? 'py-0' : 'py-4']">
      <slot></slot>
    </div>

    <div v-if="$slots.footer" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: String,
  noPadding: Boolean,
  hoverable: Boolean,
});

const cardClasses = computed(() => [
  'bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700',
  {
    'transition-shadow hover:shadow-lg': props.hoverable,
  },
]);
</script>
