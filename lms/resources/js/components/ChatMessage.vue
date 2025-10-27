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
            placeholder="Type your message..." :disabled="sending" />
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
    }
  },

  computed: {
    currentUserAvatar() {
      return this.currentUserPhoto && this.currentUserPhoto !== '/upload/no_image.jpg'
        ? this.currentUserPhoto
        : null;
    }
  },

  created() {
    this.getCurrentUser();
    // getAllUser is now called from getCurrentUser after currentUserId is set
  },

  mounted() {
    // Listen for real-time messages
    this.setupRealTimeListeners();
  },

  methods: {
    getCurrentUser() {
      axios.get('/api/current-user')
        .then((res) => {
          // Ensure we get the ID as a number for proper comparison
          this.currentUserId = parseInt(res.data.id);
          this.currentUserName = res.data.name;
          this.currentUserPhoto = this.getImageUrl(res.data);
          console.log('Current user loaded:', {
            id: this.currentUserId,
            name: this.currentUserName,
            photo: this.currentUserPhoto,
            rawData: res.data
          });

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

      const basePath = user.role === 'user' ? '/upload/user_images/' : '/upload/instructor_images/';
      const imageUrl = basePath + user.photo;
      console.log('Generated image URL:', imageUrl, 'for user:', user.name); // Debug log
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

      axios.get('/user-message/' + userId)
        .then((res) => {
          this.allmessages = res.data;
          this.selectedUser = userId;
          console.log('Messages loaded for user:', userId, 'Current user ID:', this.currentUserId);
          console.log('Sample messages with sender IDs:', res.data.messages.slice(0, 3).map(msg => ({
            id: msg.id,
            sender_id: msg.sender_id,
            sender_type: typeof msg.sender_id,
            current_id: this.currentUserId,
            current_type: typeof this.currentUserId,
            is_sent: msg.sender_id == this.currentUserId
          })));
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

      axios.post('/send-message', { receiver_id: this.selectedUser, msg: this.msg })
        .then((res) => {
          // Add message to current conversation immediately
          this.allmessages.messages.push(res.data.data);
          this.msg = "";
          this.sending = false;
          this.$nextTick(() => {
            this.scrollToBottom();
          });
        }).catch((err) => {
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
      // Implement typing indicator logic
      if (!this.isTyping) {
        this.isTyping = true;
        // In a real implementation, you'd broadcast typing status
      }

      clearTimeout(this.typingTimer);
      this.typingTimer = setTimeout(() => {
        this.isTyping = false;
      }, 1000);
    },

    scrollToBottom() {
      if (this.$refs.messagesContainer) {
        this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
      }
    },

    isSentByCurrentUser(senderId) {
      // Ensure both values are compared as the same type (numbers)
      return parseInt(senderId) === parseInt(this.currentUserId);
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
}

.user-item:hover {
  background: rgba(255, 255, 255, 0.1);
}

.user-item.active {
  background: rgba(255, 255, 255, 0.2);
  border-left-color: #fff;
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
}

.message-wrapper {
  display: flex;
  margin-bottom: 16px;
  align-items: flex-end;
  gap: 8px;
  max-width: 75%;
  clear: both;
}

/* Sent messages (current user) - Right side */
.message-wrapper.sent {
  margin-left: auto;
  margin-right: 0;
  flex-direction: row-reverse;
  float: right;
  clear: both;
}

/* Received messages (other user) - Left side */
.message-wrapper.received {
  margin-right: auto;
  margin-left: 0;
  flex-direction: row;
  float: left;
  clear: both;
}

.message-bubble {
  position: relative;
  min-width: 100px;
  max-width: 100%;
  word-wrap: break-word;
}

/* Sent message styling (current user) */
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

/* Received message styling (other user) */
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
  line-height: 1.4;
  word-wrap: break-word;
}

.message-time {
  padding: 0 16px 8px;
  font-size: 11px;
  opacity: 0.7;
  display: flex;
  align-items: center;
  justify-content: space-between;
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

/* Clear floats after messages */
.messages-area::after {
  content: "";
  display: table;
  clear: both;
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
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.send-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.send-btn i {
  color: white;
  font-size: 16px;
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