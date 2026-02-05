<template>
  <span :class="badgeClasses">
    <slot></slot>
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'success', 'warning', 'danger', 'info'].includes(value),
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
});

const badgeClasses = computed(() => {
  const base = 'inline-flex items-center font-medium rounded-full';

  const variants = {
    default: 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300',
    success: 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
    warning: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300',
    danger: 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300',
    info: 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300',
  };

  const sizes = {
    sm: 'px-2 py-0.5 text-xs',
    md: 'px-2.5 py-1 text-sm',
    lg: 'px-3 py-1.5 text-base',
  };

  return [base, variants[props.variant], sizes[props.size]];
});
</script>
