<template>
    <div class="message-wrapper" :class="{'own-message': isOwn}">
      <div class="message">
        <div class="message-content">
          <p>{{ message.message }}</p>
        </div>
        <div class="message-time">
          {{ formatDate(message.created_at) }}
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    message: {
      type: Object,
      required: true
    },
    isOwn: {
      type: Boolean,
      default: false
    }
  })
  
  const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  }
  </script>
  
  <style scoped>
  .message-wrapper {
    display: flex;
  }
  
  .message-wrapper.own-message {
    justify-content: flex-end;
  }
  
  .message {
    max-width: 65%;
    padding: 8px 12px;
    border-radius: 10px;
    position: relative;
    display: flex;
    flex-direction: column;
  }
  
  .message-wrapper:not(.own-message) .message {
    background-color: #182533;
    border-top-left-radius: 0;
  }
  
  .message-wrapper.own-message .message {
    background-color: #2b5278;
    border-top-right-radius: 0;
  }
  
  .message-content {
    margin-bottom: 4px;
  }
  
  .message-content p {
    margin: 0;
    line-height: 1.4;
  }
  
  .message-time {
    font-size: 12px;
    color: #7f91a4;
    align-self: flex-end;
    margin-top: 2px;
  }
  </style>