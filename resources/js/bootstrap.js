import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';




// Import Laravel Echo and Pusher
import Echo from 'laravel-echo';

import Pusher from 'pusher-js'; // ← Change this line

// Configure Laravel Echo
window.Pusher = Pusher; // ← And this line

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    // Optional: if you need to use a different Pusher server
    // wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    // wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    // wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    // enabledTransports: ['ws', 'wss'],
});


function getRoomIdFromDataAttribute() {
    const chatApp = document.getElementById('chat-app');
    if (chatApp && chatApp.dataset.roomId) {
        return parseInt(chatApp.dataset.roomId);
    }
    return 1; // Fallback to room 1
}

const roomId = getRoomIdFromDataAttribute();
console.log('Listening to room:', roomId); // Check if this logs the correct room ID

// REMOVE THIS - it's listening to the wrong channel!
// window.Echo.channel('chat-room')
//     .listen('message.sent', (e) => {
//         console.log('Message received:', e);
//     });

// ADD THIS - listen to the correct channel with room ID
window.Echo.channel('chat-room.' + roomId)
    .listen('new.message', (e) => {
        console.log('New chat message received:', e);
        displayNewMessage(e);
    })
    .error((error) => {
        console.error('Channel subscription error:', error);
    });

// Function to display the new message
function displayNewMessage(messageData) {
    console.log('Displaying message:', messageData);
    
    // Test if this function is called by adding an alert
    alert('Message received: ' + messageData.message);
    
    // Add message to UI
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        const messageElement = document.createElement('div');
        messageElement.innerHTML = `
            <strong>${messageData.user.name}:</strong>
            ${messageData.message}
            <small>${messageData.created_at}</small>
        `;
        messagesContainer.appendChild(messageElement);
    }
}



// Optional: Listen for connection events
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Connected to Pusher!');
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('Pusher connection error:', error);
});