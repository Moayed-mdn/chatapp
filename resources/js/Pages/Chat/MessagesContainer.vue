<template>
  <div class="messages-container" ref="container">
    <MessageBubble 
      v-for="(message, index) in messages" 
      :key="index" 
      :message="message" 
      :isOwn="message.user.id === currentUserId"
    />
  </div>
</template>

<script setup>
import { ref, onUpdated } from 'vue'
import MessageBubble from './MessageBubble.vue'

const props = defineProps({
  messages: {
    type: Array,
    required: true
  },
  currentUserId: {
    type: Number,
    required: true
  }
})

const container = ref(null)

// Auto-scroll to bottom when new messages arrive
onUpdated(() => {
  if (container.value) {
    container.value.scrollTop = container.value.scrollHeight
  }
})
</script>

<style scoped>
.messages-container {
  flex: 1;
  padding: 20px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 5px;
  scrollbar-width: thin; /* For Firefox */
}

/* Custom scrollbar for Webkit browsers (Chrome, Safari, Edge) */
.messages-container::-webkit-scrollbar {
  width: 6px;
}

.messages-container::-webkit-scrollbar-track {
  background: transparent;
  margin: 5px 0;
}

.messages-container::-webkit-scrollbar-thumb {
  background-color: rgba(122, 122, 122, 0.3);
  border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
  background-color: rgba(122, 122, 122, 0.5);
}

/* For Firefox */
.messages-container {
  scrollbar-color: rgba(122, 122, 122, 0.3) transparent;
  scrollbar-width: thin;
}
</style>