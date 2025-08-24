<template>
  <div class="message-input-container">
    <!-- Attachment Menu (shown when button is clicked) -->
    <div v-if="showAttachmentMenu" class="attachment-menu">
      <div class="menu-item" @click="handleAttachment('image')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="currentColor" d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/>
        </svg>
        <span>Photo/Video</span>
      </div>
      <div class="menu-item" @click="handleAttachment('document')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="currentColor" d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
        </svg>
        <span>Document</span>
      </div>
      <div class="menu-item" @click="handleAttachment('location')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
        </svg>
        <span>Location</span>
      </div>
    </div>

    <div class="input-wrapper">
      <button class="icon-button attachment-btn" @click="toggleAttachmentMenu" :class="{ active: showAttachmentMenu }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="currentColor" d="M20.5 13.5a1.5 1.5 0 0 1-1.5 1.5H6.91l2.3 2.29a1 1 0 0 1-1.42 1.42l-4-4a1 1 0 0 1 0-1.42l4-4a1 1 0 1 1 1.42 1.42L6.91 12H19a1.5 1.5 0 0 1 1.5 1.5z"/>
        </svg>
      </button>
      
      <input 
        type="text" 
        placeholder="Type a message..." 
        :value="modelValue" 
        @input="$emit('update:modelValue', $event.target.value)"
        @keyup.enter="$emit('send-message')"
        class="message-input"
        :disabled="disabled"
      >
      
      <button 
        @click="$emit('send-message')" 
        class="send-button"
        :disabled="!modelValue.trim() || disabled"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
          <path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  modelValue: {
    type: String,
    required: true
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'send-message', 'attach-file'])

const showAttachmentMenu = ref(false)

const toggleAttachmentMenu = () => {
  showAttachmentMenu.value = !showAttachmentMenu.value
}

const handleAttachment = (type) => {
  showAttachmentMenu.value = false
  // Emit an event to parent component with the attachment type
  // The parent would handle the actual file selection/processing
  emit('attach-file', type)
}

// Close menu when clicking outside
const onClickOutside = (event) => {
  const attachmentMenu = document.querySelector('.attachment-menu')
  const attachmentBtn = document.querySelector('.attachment-btn')
  
  if (attachmentMenu && 
      !attachmentMenu.contains(event.target) && 
      !attachmentBtn.contains(event.target)) {
    showAttachmentMenu.value = false
  }
}

// Add click event listener to document
document.addEventListener('click', onClickOutside)
</script>

<style scoped>
.message-input-container {
  padding: 15px 20px;
  background-color: #17212b;
  border-top: 1px solid #0e1621;
  position: relative;
}

.input-wrapper {
  display: flex;
  align-items: center;
  background-color: #1e2c3a;
  border-radius: 20px;
  padding: 0 10px;
  position: relative;
  z-index: 2;
}

.attachment-btn {
  margin-right: 5px;
  transition: background-color 0.2s, transform 0.2s;
}

.attachment-btn.active {
  background-color: #2b5278;
  color: #e6e6e6;
  transform: rotate(-45deg);
}

.message-input {
  flex: 1;
  background: none;
  border: none;
  padding: 12px 0;
  color: #e6e6e6;
  font-size: 14px;
  outline: none;
}

.message-input::placeholder {
  color: #7f91a4;
}

.message-input:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.send-button {
  background: none;
  border: none;
  color: #5288c1;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}

.send-button:hover:not(:disabled) {
  background-color: #202b36;
}

.send-button:disabled {
  color: #7f91a4;
  cursor: not-allowed;
}

.icon-button {
  background: none;
  border: none;
  color: #7f91a4;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}

.icon-button:hover {
  background-color: #202b36;
  color: #e6e6e6;
}

/* Attachment menu styles */
.attachment-menu {
  position: absolute;
  bottom: 100%;
  left: 20px;
  background-color: #17212b;
  border: 1px solid #0e1621;
  border-radius: 10px;
  padding: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  z-index: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 10px;
}

.attachment-menu::after {
  content: '';
  position: absolute;
  bottom: -6px;
  left: 20px;
  width: 12px;
  height: 12px;
  background-color: #17212b;
  border-right: 1px solid #0e1621;
  border-bottom: 1px solid #0e1621;
  transform: rotate(45deg);
}

.menu-item {
  display: flex;
  align-items: center;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.menu-item:hover {
  background-color: #2b5278;
}

.menu-item svg {
  margin-right: 10px;
  color: #5288c1;
}

.menu-item span {
  font-size: 14px;
  color: #e6e6e6;
}
</style>