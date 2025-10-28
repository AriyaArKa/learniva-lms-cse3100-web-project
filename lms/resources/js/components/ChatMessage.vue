<template>
  <div class="chat-container">
    <!-- Users Sidebar -->
    <div class="users-sidebar">
      <div class="sidebar-header">
        <h4><i class="fas fa-comments"></i> Chat</h4>
      </div>
      <div class="users-list">
        <div v-for="(user, index) in users" :key="index" @click="userMessage(user.id)"
          :class="['user-item', { 'active': selectedUser === user.id }]">
          <div class="user-avatar">
            <img v-if="user.photo && user.role === 'user'" :src="'/upload/user_images/' + user.photo" :alt="user.name"
              class="avatar-img" @error="handleImageError($event)" />
            <img v-else-if="user.photo && user.role !== 'user'" :src="'/upload/instructor_images/' + user.photo"
              :alt="user.name" class="avatar-img" @error="handleImageError($event)" />
            <div v-else class="avatar-fallback">
              {{ getInitials(user.name) }}
            </div>
            <div class="online-indicator"></div>
          </div>
          <div class="user-info">
            <div class="user-name">{{ user.name }}</div>
            <div class="user-role">{{ user.role }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chat Area -->
    <div class="chat-area" v-if="allmessages.user">
      <!-- Chat Header -->
      <div class="chat-header">
        <div class="chat-user-info">
          <div class="user-avatar">
            <img v-if="allmessages.user.photo" :src="getImageUrl(allmessages.user)" :alt="allmessages.user.name"
              class="avatar-img" @error="handleImageError($event)" />
            <div v-else class="avatar-fallback">
              {{ getInitials(allmessages.user.name) }}
            </div>
            <div class="online-indicator"></div>
          </div>
          <div>
            <h5 class="mb-0">{{ allmessages.user.name }}</h5>
            <small class="text-muted">{{ allmessages.user.role }}</small>
          </div>
        </div>
      </div>

      <!-- Messages Area -->
      <div class="messages-area" ref="messagesContainer">
        <div v-for="(msg, index) in allmessages.messages" :key="index"
          :class="['message-wrapper', isSentByCurrentUser(msg.sender_id) ? 'sent' : 'received']">

          <!-- Avatar for received messages (left side) - Other user's avatar -->
          <div class="message-avatar" v-if="!isSentByCurrentUser(msg.sender_id)">
            <img v-if="msg.user && msg.user.photo" :src="getImageUrl(msg.user)" :alt="msg.user.name"
              class="avatar-img-small" @error="handleImageError($event)" />
            <div v-else class="avatar-fallback-small">
              {{ getInitials(msg.user ? msg.user.name : allmessages.user.name) }}
            </div>
          </div>

          <div class="message-bubble">
            <div class="message-content">
              {{ msg.msg }}
            </div>
            <div class="message-time">
              {{ formatDate(msg.created_at) }}
              <!-- Seen indicator for sent messages -->
              <span v-if="isSentByCurrentUser(msg.sender_id)" class="seen-indicator">
                <i class="fas fa-check-circle seen-icon"></i>
              </span>
            </div>
          </div>

          <!-- Avatar for sent messages (right side) - Current user's avatar -->
          <div class="message-avatar" v-if="isSentByCurrentUser(msg.sender_id)">
            <img v-if="currentUserAvatar" :src="currentUserAvatar" :alt="currentUserName" class="avatar-img-small"
              @error="handleImageError($event)" />
            <div v-else class="avatar-fallback-small">
              {{ getInitials(currentUserName || 'Me') }}
            </div>
          </div>
        </div>

        <!-- Typing Indicator -->
        <div v-if="isTyping" class="typing-indicator">
          <div class="message-avatar">
            <img v-if="allmessages.user.photo" :src="getImageUrl(allmessages.user)" :alt="allmessages.user.name"
              class="avatar-img-small" @error="handleImageError($event)" />
            <div v-else class="avatar-fallback-small">
              {{ getInitials(allmessages.user.name) }}
            </div>
          </div>
          <div class="typing-bubble">
            <div class="typing-dots">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Message Input -->
      <div class="message-input-area">
        <div class="input-group">
          <input type="text" v-model="msg" @keyup.enter="sendMsg" @input="handleTyping" class="message-input"
            placeholder="Type your message... (Use @gpt to ask AI)" :disabled="sending" />
          <span v-if="isGptMessage" class="ai-indicator" title="AI will respond to this message">
            <i class="fas fa-robot"></i>
          </span>
          <button @click="sendMsg" :disabled="!msg.trim() || sending" class="send-btn"
            :title="sending ? 'Sending...' : 'Send message'">
            <i v-if="sending" class="fas fa-spinner fa-spin"></i>
            <i v-else class="fas fa-paper-plane"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="empty-state">
      <div class="empty-content">
        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
        <h5>Select a conversation</h5>
        <p class="text-muted">Choose a user from the sidebar to start chatting</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    initialCurrentUser: {
      type: Object,
      required: false,
      default: null
    }
  },

  data() {
    return {
      users: [],
      allmessages: {},
      selectedUser: '',
      msg: '',
      sending: false,
      isTyping: false,
      typingTimer: null,
      currentUserId: null,
      currentUserPhoto: '',
      currentUserName: '',
      currentUserRole: '',
    }
  },

  computed: {
    currentUserAvatar() {
      return this.currentUserPhoto && this.currentUserPhoto !== '/upload/no_image.jpg'
        ? this.currentUserPhoto
        : null;
    },
    isGptMessage() {
      return this.msg.trim().toLowerCase().startsWith('@gpt');
    }
  },

  created() {
    // Set current user FIRST before anything else
    if (this.initialCurrentUser) {
      console.log('=== INITIALIZING CURRENT USER FROM PROP ===');
      this.currentUserId = parseInt(this.initialCurrentUser.id);
      this.currentUserName = this.initialCurrentUser.name;
      this.currentUserRole = this.initialCurrentUser.role;
      this.currentUserPhoto = this.getImageUrl(this.initialCurrentUser);

      console.log('Current User Set:', {
        id: this.currentUserId,
        name: this.currentUserName,
        role: this.currentUserRole,
        photo: this.currentUserPhoto
      });
      console.log('=======================================');
    }

    // Now that currentUserId is set, we can load users and messages
    this.getCurrentUser();
  },

  mounted() {
    // Listen for real-time messages
    this.setupRealTimeListeners();
  },

  methods: {
    getCurrentUser() {
      console.log('=== GETTING CURRENT USER ===');

      // If we already have currentUserId from props (set in created()), just load users
      if (this.currentUserId) {
        console.log('Current user already set from prop, loading users...');
        this.getAllUser();
        return;
      }

      // Fallback to API call if prop was not available
      console.log('No prop found, fetching from API...');
      axios.get('/api/current-user')
        .then((res) => {
          console.log('API Response:', res.data);

          // Ensure we get the ID as a number for proper comparison
          this.currentUserId = parseInt(res.data.id);
          this.currentUserName = res.data.name;
          this.currentUserRole = res.data.role;
          this.currentUserPhoto = this.getImageUrl(res.data);

          console.log('=== CURRENT USER SET FROM API ===');
          console.log('ID:', this.currentUserId, '(type:', typeof this.currentUserId, ')');
          console.log('Name:', this.currentUserName);
          console.log('Photo:', this.currentUserPhoto);
          console.log('Raw Data:', res.data);
          console.log('=================================');

          // Load users after current user is set
          this.getAllUser();
        })
        .catch((err) => {
          console.error('Error fetching current user:', err);
        });
    },

    getImageUrl(user) {
      if (!user || !user.photo) {
        console.log('No user or photo:', user); // Debug log
        return '/upload/no_image.jpg';
      }

      // Determine the correct path based on role
      let basePath = '/upload/user_images/'; // default for regular users

      if (user.role === 'instructor') {
        basePath = '/upload/instructor_images/';
      } else if (user.role === 'admin') {
        basePath = '/upload/admin_images/';
      }

      const imageUrl = basePath + user.photo;
      console.log('Generated image URL:', imageUrl, 'for user:', user.name, 'role:', user.role); // Debug log
      return imageUrl;
    },

    handleImageError(event) {
      console.log('Image failed to load:', event.target.src); // Debug log
      // Set fallback image when image fails to load
      event.target.src = '/upload/no_image.jpg';
    },

    getInitials(name) {
      if (!name) return '?';
      return name.split(' ').map(word => word.charAt(0)).join('').toUpperCase().slice(0, 2);
    },

    setupRealTimeListeners() {
      // For now, use a more frequent polling until Echo is fully configured
      setInterval(() => {
        if (this.selectedUser) {
          this.refreshMessages();
        }
      }, 2000);
    },

    refreshMessages() {
      // Silently refresh messages without changing UI state
      axios.get('/user-message/' + this.selectedUser)
        .then((res) => {
          const newMessageCount = res.data.messages.length;
          const currentCount = this.allmessages.messages ? this.allmessages.messages.length : 0;

          if (newMessageCount > currentCount) {
            this.allmessages = res.data;
            console.log('Messages refreshed. New count:', newMessageCount, 'Current user:', this.currentUserId);
            this.$nextTick(() => {
              this.scrollToBottom();
            });
          }
        })
        .catch((err) => {
          console.error('Error refreshing messages:', err);
        });
    },

    getAllUser() {
      axios.get('/user-all')
        .then((res) => {
          this.users = res.data;
          console.log('Users data:', this.users); // Debug log

          // Filter out current user from the list to prevent self-messaging
          if (this.currentUserId) {
            this.users = this.users.filter(user => user.id !== this.currentUserId);
          }

          // auto-select first user so messages load immediately
          if (Array.isArray(this.users) && this.users.length > 0) {
            this.userMessage(this.users[0].id);
          }
        }).catch((err) => {
          console.error('Error fetching users:', err);
        });
    },

    userMessage(userId) {
      // Prevent selecting self conversation
      if (userId === this.currentUserId) {
        console.warn('Cannot start conversation with yourself');
        return;
      }

      console.log('=== LOADING MESSAGES FOR USER:', userId, '===');
      console.log('Current User ID:', this.currentUserId);

      axios.get('/user-message/' + userId)
        .then((res) => {
          this.allmessages = res.data;
          this.selectedUser = userId;

          console.log('=== MESSAGES LOADED ===');
          console.log('Total messages:', res.data.messages.length);
          console.log('First 3 messages analysis:');
          res.data.messages.slice(0, 3).forEach((msg, idx) => {
            console.log(`Message ${idx + 1}:`, {
              id: msg.id,
              text: msg.msg.substring(0, 30) + '...',
              sender_id: msg.sender_id,
              sender_id_type: typeof msg.sender_id,
              currentUserId: this.currentUserId,
              currentUserId_type: typeof this.currentUserId,
              sender_equals_current: msg.sender_id === this.currentUserId,
              sender_equals_current_parseInt: parseInt(msg.sender_id) === parseInt(this.currentUserId),
              SHOULD_BE_ON_RIGHT: parseInt(msg.sender_id) === parseInt(this.currentUserId)
            });
          });
          console.log('===================');

          this.$nextTick(() => {
            this.scrollToBottom();
          });
        }).catch((err) => {
          console.error('Error fetching messages:', err);
        })
    },

    sendMsg() {
      if (!this.msg.trim() || this.sending) return;

      this.sending = true;

      // Create optimistic message for instant feedback
      const optimisticMessage = {
        sender_id: this.currentUserId,
        receiver_id: this.selectedUser,
        msg: this.msg,
        created_at: new Date().toISOString(),
        user: {
          id: this.currentUserId,
          name: this.currentUserName,
          photo: this.currentUserPhoto,
          role: 'sending...'
        },
        isOptimistic: true
      };

      const messageText = this.msg;
      const isGptMessage = messageText.trim().toLowerCase().startsWith('@gpt');
      this.msg = "";

      axios.post('/send-message', { receiver_id: this.selectedUser, msg: messageText })
        .then((res) => {
          // Remove optimistic message if it exists
          this.allmessages.messages = this.allmessages.messages.filter(m => !m.isOptimistic);

          // Add actual message from server
          this.allmessages.messages.push(res.data.data);

          // If there's an AI response, add it too
          if (res.data.ai_response) {
            this.allmessages.messages.push(res.data.ai_response);
          }

          this.sending = false;
          this.$nextTick(() => {
            this.scrollToBottom();
          });
        }).catch((err) => {
          // Remove optimistic message on error
          this.allmessages.messages = this.allmessages.messages.filter(m => !m.isOptimistic);
          this.msg = messageText; // Restore message
          this.sending = false;
          if (err.response && err.response.status === 400) {
            alert(err.response.data.message || 'Cannot send message to yourself');
          } else {
            console.error('Error sending message:', err);
            alert('Failed to send message. Please try again.');
          }
        })
    },

    handleTyping() {
      // This method is called when the current user types
      // In a real implementation with broadcasting, you would:
      // 1. Broadcast typing status to the other user
      // 2. The other user would receive the event and show the typing indicator
      // For now, we don't show typing indicator for yourself

      // TODO: Implement with Laravel Echo broadcasting
      // broadcast typing event to receiver_id: this.selectedUser
    },

    scrollToBottom() {
      if (this.$refs.messagesContainer) {
        this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
      }
    },

    isSentByCurrentUser(senderId) {
      // Ensure both values are compared as the same type (numbers)
      // Handle null/undefined cases
      if (!senderId || !this.currentUserId) {
        console.warn('Missing senderId or currentUserId:', { senderId, currentUserId: this.currentUserId });
        return false;
      }

      const senderIdNum = parseInt(senderId);
      const currentUserIdNum = parseInt(this.currentUserId);
      const result = senderIdNum === currentUserIdNum;

      // Debug logging (can be removed in production)
      console.log('isSentByCurrentUser check:', {
        senderId: senderId,
        currentUserId: this.currentUserId,
        senderIdNum: senderIdNum,
        currentUserIdNum: currentUserIdNum,
        result: result ? '✓ SENT BY ME (RIGHT)' : '✗ RECEIVED (LEFT)'
      });

      return result;
    },

    formatDate(dateString) {
      const options = { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleDateString('en-US', options);
    },
  },
};
</script>



<style scoped>
/* Reset and base styles */
.chat-container * {
  box-sizing: border-box;
}

.chat-container {
  display: flex;
  height: 600px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  position: relative;
  z-index: 1;
}

/* Users Sidebar */
.users-sidebar {
  width: 300px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-right: 1px solid #e0e0e0;
}

.sidebar-header {
  padding: 20px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

.sidebar-header h4 {
  color: white;
  margin: 0;
  font-weight: 600;
}

.users-list {
  overflow-y: auto;
  height: calc(100% - 80px);
  padding: 10px 0;
}

.user-item {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  cursor: pointer;
  transition: all 0.3s ease;
  border-left: 3px solid transparent;
  position: relative;
}

.user-item::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 0;
  background: rgba(255, 255, 255, 0.3);
  transition: width 0.3s ease;
}

.user-item:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateX(2px);
}

.user-item:hover::before {
  width: 3px;
}

.user-item.active {
  background: rgba(255, 255, 255, 0.2);
  border-left-color: #fff;
  box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.user-item.active::before {
  width: 3px;
}

.user-avatar {
  position: relative;
  margin-right: 12px;
}

.avatar-wrapper {
  position: relative;
  width: 45px;
  height: 45px;
}

.avatar-fallback {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.avatar-fallback-small {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 12px;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.avatar-img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(255, 255, 255, 0.3);
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.avatar-img-small {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(255, 255, 255, 0.3);
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Typing Indicator */
.typing-indicator {
  display: flex;
  align-items: flex-end;
  gap: 8px;
  margin-bottom: 12px;
  margin-right: auto;
}

.typing-bubble {
  background: #e2e8f0;
  border-radius: 18px 18px 18px 4px;
  padding: 12px 16px;
  margin-left: 4px;
}

.online-indicator {
  position: absolute;
  bottom: 2px;
  right: 2px;
  width: 12px;
  height: 12px;
  background: #4ade80;
  border: 2px solid white;
  border-radius: 50%;
}

.user-info {
  flex: 1;
  color: white;
}

.user-name {
  font-weight: 600;
  font-size: 14px;
  margin-bottom: 2px;
}

.user-role {
  font-size: 12px;
  opacity: 0.8;
  text-transform: capitalize;
}

/* Chat Area */
.chat-area {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.chat-header {
  padding: 20px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.chat-user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.chat-user-info h5 {
  font-weight: 600;
  color: #1e293b;
}

/* Messages Area */
.messages-area {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  background: linear-gradient(to bottom, #f8fafc, #ffffff);
  scroll-behavior: smooth;
  display: flex;
  flex-direction: column;
}

.message-wrapper {
  display: flex;
  margin-bottom: 16px;
  align-items: flex-end;
  gap: 8px;
  max-width: 70%;
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Sent messages (current user) - Right side with filled background */
.message-wrapper.sent {
  align-self: flex-end;
  flex-direction: row-reverse;
  margin-left: auto;
}

/* Received messages (other user) - Left side */
.message-wrapper.received {
  align-self: flex-start;
  flex-direction: row;
  margin-right: auto;
}

.message-bubble {
  position: relative;
  min-width: 100px;
  max-width: 100%;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

/* Sent message styling (current user) - FILLED BACKGROUND on RIGHT */
.message-wrapper.sent .message-bubble {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 18px 18px 4px 18px;
  margin-right: 4px;
  box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
  position: relative;
}

.message-wrapper.sent .message-bubble::after {
  content: '';
  position: absolute;
  bottom: 0;
  right: -8px;
  width: 0;
  height: 0;
  border: 8px solid transparent;
  border-left-color: #764ba2;
  border-bottom: none;
  border-right: none;
}

/* Received message styling (other user) - LEFT SIDE */
.message-wrapper.received .message-bubble {
  background: #e2e8f0;
  color: #334155;
  border-radius: 18px 18px 18px 4px;
  margin-left: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  position: relative;
}

.message-wrapper.received .message-bubble::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: -8px;
  width: 0;
  height: 0;
  border: 8px solid transparent;
  border-right-color: #e2e8f0;
  border-bottom: none;
  border-left: none;
}

.message-content {
  padding: 12px 16px 8px 16px;
  font-size: 14px;
  line-height: 1.5;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.message-wrapper.sent .message-content {
  color: white;
}

.message-wrapper.received .message-content {
  color: #334155;
}

.message-time {
  padding: 0 16px 8px;
  font-size: 11px;
  opacity: 0.8;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.message-wrapper.sent .message-time {
  color: rgba(255, 255, 255, 0.9);
  justify-content: flex-end;
  gap: 6px;
}

.message-wrapper.received .message-time {
  color: #64748b;
}

.seen-indicator {
  margin-left: 6px;
}

.seen-icon {
  color: #4ade80;
  font-size: 12px;
}

.message-avatar {
  flex-shrink: 0;
  align-self: flex-end;
  margin-bottom: 2px;
}


/* Message Input */
.message-input-area {
  padding: 20px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
}

.input-group {
  display: flex;
  align-items: center;
  gap: 12px;
  background: white;
  border-radius: 25px;
  padding: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.input-group:focus-within {
  box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
  transform: translateY(-1px);
}

.ai-indicator {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #667eea;
  font-size: 20px;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {

  0%,
  100% {
    opacity: 1;
    transform: scale(1);
  }

  50% {
    opacity: 0.7;
    transform: scale(1.1);
  }
}

.message-input {
  border: none;
  outline: none;
  flex: 1;
  padding: 12px 16px;
  font-size: 14px;
  background: transparent;
  color: #334155;
  resize: none;
}

.message-input::placeholder {
  color: #94a3b8;
}

.send-btn {
  border-radius: 50%;
  width: 44px;
  height: 44px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  cursor: pointer;
  flex-shrink: 0;
}

.send-btn:hover:not(:disabled) {
  transform: scale(1.1);
  box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
}

.send-btn:active:not(:disabled) {
  transform: scale(0.95);
}

.send-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
  background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
}

.send-btn i {
  color: white;
  font-size: 16px;
  transition: transform 0.3s ease;
}

.send-btn:hover:not(:disabled) i {
  transform: translateX(2px);
}

/* Empty State */
.empty-state {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(to bottom, #f8fafc, #ffffff);
}

.empty-content {
  text-align: center;
  color: #64748b;
}

/* Scrollbar Styling */
.messages-area::-webkit-scrollbar,
.users-list::-webkit-scrollbar {
  width: 6px;
}

.messages-area::-webkit-scrollbar-track,
.users-list::-webkit-scrollbar-track {
  background: transparent;
}

.messages-area::-webkit-scrollbar-thumb,
.users-list::-webkit-scrollbar-thumb {
  background: rgba(148, 163, 184, 0.5);
  border-radius: 3px;
}

.messages-area::-webkit-scrollbar-thumb:hover,
.users-list::-webkit-scrollbar-thumb:hover {
  background: rgba(148, 163, 184, 0.8);
}

/* Responsive Design */
@media (max-width: 768px) {
  .chat-container {
    height: 500px;
  }

  .users-sidebar {
    width: 250px;
  }

  .message-bubble {
    max-width: 85%;
  }
}
</style>