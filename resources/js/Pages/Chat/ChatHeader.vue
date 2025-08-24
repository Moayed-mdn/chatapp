<template>
    <div class="chat-header">
      <div class="current-chat-info">
        <div class="chat-avatar">
          {{ roomInitial }}
        </div>
        <div class="chat-details">
          <div class="chat-title">{{ room.name }}</div>
          <div class="chat-status">online</div>
        </div>
      </div>
      <div class="chat-actions">
        <button class="icon-button">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="currentColor" d="M12 20.664a9.163 9.163 0 0 1-6.521-2.702.977.977 0 0 1 1.381-1.381 7.269 7.269 0 0 0 10.024.244.977.977 0 0 1 1.313 1.445A9.192 9.192 0 0 1 12 20.664zm7.965-6.112a.977.977 0 0 1-.944-1.229 7.26 7.26 0 0 0-4.8-8.804.977.977 0 0 1 .594-1.86 9.212 9.212 0 0 1 6.092 11.169.976.976 0 0 1-.942.724z"/>
          </svg>
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  
  const props = defineProps({
    room: {
      type: Object,
      required: true
    }
  })
  
  const roomInitial = computed(() => {
    return props.room.name.charAt(0).toUpperCase()
  })
  </script>
  
  <style scoped>
  .chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    background-color: #17212b;
    border-bottom: 1px solid #0e1621;
  }
  
  .current-chat-info {
    display: flex;
    align-items: center;
  }
  
  .chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #5288c1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
  }
  
  .chat-details {
    display: flex;
    flex-direction: column;
  }
  
  .chat-title {
    font-weight: 500;
    margin-bottom: 2px;
  }
  
  .chat-status {
    font-size: 13px;
    color: #7f91a4;
  }
  
  .chat-actions {
    display: flex;
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
  </style>