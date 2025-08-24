<template>
  <div class="chat-sidebar" :class="{ 'sidebar-open': sidebarOpen, 'sidebar-closed': !sidebarOpen }">
    <div class="sidebar-header">
      <h2 v-if="sidebarOpen">Chat</h2>
      <button class="sidebar-toggle" @click="$emit('toggle-sidebar')">
        <svg v-if="sidebarOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
          <path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
          <path fill="currentColor" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
      </button>
    </div>
    
    <div class="rooms-section">
      <h3 v-if="sidebarOpen">Chat Rooms</h3>
      <div class="rooms-list">
        <RoomItem 
          v-for="room in rooms" 
          :key="room.id" 
          :room="room" 
          :isActive="currentRoom?.id === room.id"
          :sidebarOpen="sidebarOpen"
          @click="$emit('room-selected', room)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import RoomItem from './RoomItem.vue'

defineProps({
  rooms: {
    type: Array,
    required: true
  },
  currentRoom: {
    type: Object,
    default: null
  },
  sidebarOpen: {
    type: Boolean,
    default: true
  }
})

defineEmits(['room-selected', 'toggle-sidebar'])
</script>

<style scoped>
.chat-sidebar {
  background-color: #17212b;
  border-right: 1px solid #0e1621;
  display: flex;
  flex-direction: column;
  transition: width 0.3s ease;
  overflow: hidden;
  height: 100%;
}

.sidebar-open {
  width: 280px;
}

.sidebar-closed {
  width: 70px;
}

.sidebar-header {
  padding: 15px;
  background-color: #17212b;
  border-bottom: 1px solid #0e1621;
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 60px;
}

.sidebar-header h2 {
  margin: 0;
  font-size: 18px;
  color: #e6e6e6;
}

.sidebar-toggle {
  background: none;
  border: none;
  color: #7f91a4;
  cursor: pointer;
  padding: 5px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}

.sidebar-toggle:hover {
  background-color: #2b5278;
  color: #e6e6e6;
}

.rooms-section {
  flex: 1;
  overflow-y: auto;
  padding: 10px 0;
}

.rooms-section h3 {
  padding: 0 15px;
  margin-top: 0;
  margin-bottom: 10px;
  font-size: 14px;
  color: #7f91a4;
  text-transform: uppercase;
}

.rooms-list {
  padding: 0;
}

/* Responsive behavior */
@media (max-width: 768px) {
  .sidebar-open {
    width: 280px;
    position: fixed;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
  }
  
  .sidebar-closed {
    width: 0;
    position: fixed;
  }
}
</style>