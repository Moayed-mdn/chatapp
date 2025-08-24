<template>
  <div class="chat-app">
    <!-- Sidebar -->
    <ChatSidebar 
      :rooms="rooms" 
      :currentRoom="currentRoom" 
      :sidebarOpen="sidebarOpen"
      @room-selected="setRoom"
      @toggle-sidebar="toggleSidebar"
    />
    
    <!-- Main Chat Area -->
    <div class="chat-main" :class="{ 'expanded': !sidebarOpen }">
      <ChatHeader 
        v-if="currentRoom" 
        :room="currentRoom" 
      />
      
      <MessagesContainer 
        :messages="messages" 
        :currentUserId="currentUserId" 
      />
      
      <MessageInput 
        v-model="newMessage" 
        @send-message="sendMessage" 
        @attach-file="handleAttachment"
        :disabled="!currentRoom"
      />

      <!-- Hidden file input for attachments -->
      <input 
        type="file" 
        ref="fileInput" 
        :accept="currentAccept" 
        style="display: none" 
        @change="processFile"
        multiple
      >
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ChatSidebar from './ChatSidebar.vue'
import ChatHeader from './ChatHeader.vue'
import MessagesContainer from './MessagesContainer.vue'
import MessageInput from './MessageInput.vue'

// You'll need to set this based on your authentication
const currentUserId = ref(1) // Example user ID

const rooms = ref([])
const messages = ref([])
const currentRoom = ref(null)
const newMessage = ref('')
const sidebarOpen = ref(true) // Sidebar is open by default
const fileInput = ref(null)
const currentAccept = ref('*/*') // Default file accept
const attachmentType = ref(null) // Type of attachment being processed
let echoChannel = null

// Toggle sidebar visibility
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

// Handle attachment button click from MessageInput
const handleAttachment = (type) => {
  currentAccept.value = 'image/*,video/*'
  attachmentType.value = type
  
  // Set appropriate file accept based on attachment type
  switch(type) {
    case 'image':
      currentAccept.value = 'image/*,video/*'
      break
    case 'document':
      currentAccept.value = '.pdf,.doc,.docx,.txt,.zip,.rar'
      break
    case 'location':
      handleLocationShare()
      return
    default:
      currentAccept.value = '*/*'
  }
  // Trigger the hidden file input
  if (fileInput.value) {
    fileInput.value.click()
  }
}

// Process the selected file
const processFile = (event) => {
  const files = event.target.files
  if (!files || files.length === 0) return
  
  // Handle multiple files if needed
  for (let i = 0; i < files.length; i++) {
    const file = files[i]
    uploadFile(file)
  }
  
  // Reset the file input
  event.target.value = ''
}

// Upload file to server
const uploadFile = async (file) => {
  if (!currentRoom.value?.id) return
  
  try {
    const formData = new FormData()
    formData.append('file', file)
    formData.append('room_id', currentRoom.value.id)
    formData.append('type', attachmentType.value)
      
    console.log('FormData entries:', Array.from(formData.entries()));

    // const response = await axios.post('/chat/upload', formData, {
    //   headers: {
    //     'Content-Type': 'multipart/form-data'
    //   }
    // })
    
    // If you want to automatically send a message with the attachment
    // if (response.data.success) {
    //   // You could automatically send a message or just show the uploaded file
    //   console.log('File uploaded successfully:', response.data)
      
    //   // Optionally send a message with the file
    //   // await sendMessageWithAttachment(response.data.file_url, file.type)
    //     console.log(response.data.file_url, file.type);
    // }
  } catch (error) {
    console.error('Error uploading file:', error)
    alert('Failed to upload file. Please try again.')
  }
}

// Send message with attachment
const sendMessageWithAttachment = async (fileUrl, fileType) => {
  try {
    let messageText = ''
    
    if (fileType.startsWith('image/')) {
      messageText = `📷 [Image](${fileUrl})`
    } else if (fileType.startsWith('video/')) {
      messageText = `🎥 [Video](${fileUrl})`
    } else {
      messageText = `📎 [File](${fileUrl})`
    }
    
    await axios.post('/chat/room/' + currentRoom.value.id + '/message', {
      message: messageText,
      attachment: fileUrl,
      attachment_type: attachmentType.value
    })
  } catch (error) {
    console.error('Error sending message with attachment:', error)
  }
}

// Handle location sharing
const handleLocationShare = () => {
  if (!navigator.geolocation) {
    alert('Geolocation is not supported by your browser')
    return
  }
  
  navigator.geolocation.getCurrentPosition(
    async (position) => {
      const { latitude, longitude } = position.coords
      const locationUrl = `https://www.openstreetmap.org/?mlat=${latitude}&mlon=${longitude}#map=15/${latitude}/${longitude}`
      
      try {
        await axios.post('/chat/room/' + currentRoom.value.id + '/message', {
          message: `📍 [My Location](${locationUrl})`,
          location: { latitude, longitude }
        })
      } catch (error) {
        console.error('Error sharing location:', error)
      }
    },
    (error) => {
      console.error('Error getting location:', error)
      alert('Unable to retrieve your location')
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
  )
}

// Methods
const getRooms = async () => {
  try {
    const response = await axios.get('/chat/rooms')
    rooms.value = response.data
    if (rooms.value.length > 0 && !currentRoom.value) {
      setRoom(rooms.value[0])
    }
  } catch (error) {
    console.error('Error fetching rooms:', error)
  }
}

const setRoom = (room) => {
  // Leave previous channel
  if (echoChannel) {
    window.Echo.leave(echoChannel)
  }
  
  currentRoom.value = room
  getMessages()
  setupEchoListener()
}

const setupEchoListener = () => {
  if (!currentRoom.value?.id) return
  
  const channelName = 'chat-room.' + currentRoom.value.id
  echoChannel = channelName
  
  console.log('📡 Listening to channel:', channelName)

  window.Echo.private(channelName)
    .listen('NewChatMessage', (e) => {
      console.log("NewChatMessage", e)
      handleNewMessage(e)
    })
}

const handleNewMessage = (messageData) => {
  messages.value.push(messageData)
}

const sendMessage = async () => {
  if (newMessage.value.trim().length > 0 && currentRoom.value?.id) {
    try {
      await axios.post('/chat/room/' + currentRoom.value.id + '/message', {
        message: newMessage.value
      })
      newMessage.value = ''
    } catch (error) {
      console.error('Error sending message:', error)
    }
  }
}

const getMessages = async () => {
  if (!currentRoom.value?.id) return
  
  try {
    const response = await axios.get('/chat/room/' + currentRoom.value.id + '/messages')
    messages.value = response.data
  } catch (error) {
    console.error('Error fetching messages:', error)
  }
}

onMounted(() => {
  getRooms()
})
</script>

<style scoped>
.chat-app {
  display: flex;
  height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  background-color: #0e1621;
  color: #e6e6e6;
}

.chat-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  background-color: #0e1621;
  transition: margin-left 0.3s ease;
}

.chat-main.expanded {
  margin-left: 0;
}

/* Responsive behavior */
@media (max-width: 768px) {
  .chat-main:not(.expanded) {
    margin-left: 0;
  }
}
</style>