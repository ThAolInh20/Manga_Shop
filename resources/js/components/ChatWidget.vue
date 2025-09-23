<template>
  <div>
    <!-- Nút chat nổi -->
    <div 
      class="chat-icon"
      @click="toggleChat"
    >
      💬
    </div>

    <!-- Khung chat -->
    <div v-if="isOpen" class="chat-box">
      <div class="chat-header">
        <span>AI Gợi ý sản phẩm</span>
        <button @click="toggleChat">×</button>
      </div>
      <div class="chat-body">
        <div v-for="(msg, index) in messages" :key="index" 
             :class="msg.sender">
          {{ msg.text }}
        </div>
         <!-- Gợi ý lựa chọn -->
        <div v-if="showChoices" class="choices">
          <button @click="handleChoice('Tìm hiểu về trang web MangaShop')">
            📖 Tìm hiểu về trang web
          </button>
          <button @click="handleChoice('Tôi muốn kiểm tra đơn hàng')">
            📦 Kiểm tra đơn hàng
          </button>
          <button @click="handleChoice('Tôi muốn tìm sản phẩm')">
            🔎 Tìm sản phẩm
          </button>
        </div>
    
      </div>

      <div class="chat-footer">
        <input v-model="newMessage" 
               @keyup.enter="sendMessage" 
               type="text" placeholder="Nhập tin nhắn..." />
        <button @click="sendMessage">Gửi</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ChatWidget",
  data() {
    return {
      isOpen: false,
      newMessage: "",
      messages: [
        { text: "Xin chào 👋, bạn muốn mình gợi ý truyện gì không?", sender: "bot" }
      ],
    };
  },
  methods: {
    toggleChat() {
      this.isOpen = !this.isOpen;
    },
    sendMessage() {
      if (!this.newMessage.trim()) return;
      // Thêm message user
      this.messages.push({ text: this.newMessage, sender: "user" });

      // Giả lập bot trả lời
      setTimeout(() => {
        this.messages.push({ text: "Mình đang phân tích và sẽ gợi ý cho bạn 📚", sender: "bot" });
      }, 800);

      this.newMessage = "";
    },
  },
};
</script>

<style scoped>
/* Nút chat nổi */
.chat-icon {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #0084ff;
  color: white;
  border-radius: 50%;
  width: 55px;
  height: 55px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

/* Khung chat */
.chat-box {
  position: fixed;
  bottom: 80px;
  right: 20px;
  width: 320px;
  max-height: 400px;
  display: flex;
  flex-direction: column;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  background: #fff;
}

.chat-header {
  background: #0084ff;
  color: white;
  padding: 10px;
  display: flex;
  justify-content: space-between;
}

.chat-body {
  flex: 1;
  padding: 10px;
  overflow-y: auto;
  background: #f5f5f5;
}

.chat-body .user {
  text-align: right;
  margin: 5px 0;
  color: #333;
}

.chat-body .bot {
  text-align: left;
  margin: 5px 0;
  color: #0084ff;
}

.chat-footer {
  display: flex;
  padding: 10px;
  background: #eee;
}

.chat-footer input {
  flex: 1;
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.chat-footer button {
  margin-left: 5px;
  background: #0084ff;
  color: white;
  border: none;
  padding: 5px 12px;
  border-radius: 5px;
  cursor: pointer;
}
</style>
