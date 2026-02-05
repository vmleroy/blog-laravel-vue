<template>
  <teleport to="body">
    <div
      v-if="modelValue"
      class="fixed inset-0 flex items-center justify-center p-4 z-50"
      style="backdrop-filter: blur(4px); background-color: rgba(0, 0, 0, 0.1);"
      @click.self="$emit('close')"
    >
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full overflow-hidden">
        <!-- Title Bar -->
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ title }}</h2>
          <button
            @click="$emit('close')"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Content -->
        <div class="px-6 py-4">
          <slot />
        </div>

        <!-- Footer (optional) -->
        <div v-if="$slots.footer" class="px-6 py-4">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </teleport>
</template>

<script setup>
defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: '',
  },
});

defineEmits(['close']);
</script>
