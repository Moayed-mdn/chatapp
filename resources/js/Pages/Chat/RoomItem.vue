<template>
  <div 
    class="room-item" 
    :class="{ active: isActive }"
    @click="$emit('click', $event)"
    :title="sidebarOpen ? '' : room.name"
  >
    <div class="room-avatar">
      {{ roomInitial }}
    </div>
    <div class="room-info" v-if="sidebarOpen">
      <div class="room-name">{{ room.name }}</div>
      <div class="room-last-message">Last message preview...</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  room: {
    type: Object,
    required: true
  },
  isActive: {
    type: Boolean,
    default: false
  },
  sidebarOpen: {
    type: Boolean,
    default: true
  }
})

const roomInitial = computed(() => {
  return props.room.name.charAt(0).toUpperCase()
})
</script>

<style scoped>
.room-item {
  display: flex;
  align-items: center;
  padding: 10px 15px;
  cursor: pointer;
  transition: background-color 0.2s;
  overflow: hidden;
}

.room-item:hover {
  background-color: #202b36;
}

.room-item.active {
  background-color: #2b5278;
}

.room-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #5288c1;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin-right: 12px;
  flex-shrink: 0;
}

.room-info {
  flex: 1;
  min-width: 0;
  overflow: hidden;
}

.room-name {
  font-weight: 500;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.room-last-message {
  font-size: 13px;
  color: #7f91a4;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>