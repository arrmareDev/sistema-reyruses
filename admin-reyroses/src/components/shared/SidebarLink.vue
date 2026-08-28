<template>
  <router-link :to="to" custom v-slot="{ href, navigate, isActive }">
    <a :href="href" @click="navigate" :class="[
      'w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group cursor-pointer',
      isActive
        ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
        : isDarkTheme
          ? 'text-zinc-400 hover:bg-[#1e1e24] hover:text-white'
          : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
    ]">
      <div
        :class="['p-2 rounded-lg', isActive ? 'bg-emerald-500/20' : isDarkTheme ? 'bg-[#1e1e24] group-hover:bg-[#2a2a32]' : 'bg-gray-100 group-hover:bg-gray-200']">
        <component :is="icon" class="w-4 h-4" :stroke-width="2" />
      </div>
      <span class="text-sm font-medium">{{ label }}</span>
      <span v-if="isActive" class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
    </a>
  </router-link>
</template>

<script setup>
import { useTheme } from '@/composables/useTheme'

const { isDarkTheme } = useTheme()

defineProps({
  to: { type: String, required: true },
  label: { type: String, required: true },
  icon: { type: [Object, Function], required: true },
})
</script>
