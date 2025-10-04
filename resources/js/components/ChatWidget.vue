<template>
  <div>
    <div class="chat-icon" @click="toggleChat">💬</div>

    <div v-if="isOpen" class="chat-box">
      <div class="chat-header">
        <span>AI Gợi ý sản phẩm</span>
        <div>
          <button @click="clearHistory" title="Xóa lịch sử">🗑️</button>
          <button @click="toggleChat">×</button>
        </div>
      </div>

      <div class="chat-body" ref="chatBody">
        <div v-for="(msg,index) in messages" :key="index" :class="msg.sender">
          {{ msg.text }}
        </div>
        <div v-if="isTyping" class="bot typing">...</div>
      </div>

      <div class="chat-footer">
        <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Nhập tin nhắn..." :disabled="isTyping" />
        <button @click="sendMessage" :disabled="isTyping">Gửi</button>
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
      isTyping: false,
      messages: [
        { text: "Xin chào 👋, bạn muốn mình gợi ý truyện gì không?", sender: "bot" }
      ],
    };
  },
  mounted() {
    // Load tin nhắn từ localStorage khi mở lại trang
    const saved = localStorage.getItem("chatMessages");
    if (saved) {
      this.messages = JSON.parse(saved);
    }
  },
  methods: {
    toggleChat() { 
      this.isOpen = !this.isOpen; 
    },

    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.chatBody;
        if (container) container.scrollTop = container.scrollHeight;
      });
    },

    async sendMessage() {
      if (!this.newMessage.trim()) return;

      const msg = this.newMessage;
      this.messages.push({ text: msg, sender: "user" });
      this.newMessage = "";
      this.scrollToBottom();
      this.isTyping = true;

      try {
        const res = await fetch("/api/chat-ai/handle", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ message: msg, messages: this.messages })
        });

        const data = await res.json();
        const reply = data.message || "⚠️ Không có phản hồi từ AI.";

        this.isTyping = false;
        this.messages.push({ text: reply, sender: "bot" });
        this.scrollToBottom();

      } catch (err) {
        console.error(err);
        this.isTyping = false;
        this.messages.push({ text: "⚠️ Không kết nối được server.", sender: "bot" });
        this.scrollToBottom();
      }

      // 🔹 Lưu lại lịch sử chat
      localStorage.setItem("chatMessages", JSON.stringify(this.messages));
    },

    async clearHistory() {
      this.messages = [
        { text: "Xin chào 👋, bạn muốn mình gợi ý truyện gì không?", sender: "bot" }
      ];
      this.scrollToBottom();

      // Xóa localStorage luôn
      localStorage.removeItem("chatMessages");

      try {
        await fetch("/api/chat-ai/handle", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ message: "clear", messages: [] })
        });
      } catch (err) {
        console.error("Lỗi khi xóa lịch sử trên server:", err);
      }
    }
  }
};
</script>

<style scoped>
.chat-icon{
  position:fixed; bottom:20px; right:20px;
  background:#0084ff; color:white; border-radius:50%;
  width:55px; height:55px; display:flex;
  align-items:center; justify-content:center;
  font-size:26px; cursor:pointer; box-shadow:0 4px 10px rgba(0,0,0,0.2);
}
.chat-box{
  position:fixed; bottom:80px; right:20px;
  width:320px; max-height:400px; display:flex; flex-direction:column;
  border-radius:10px; overflow:hidden;
  box-shadow:0 4px 15px rgba(0,0,0,0.3); background:#fff;
}
.chat-header{
  background:#0084ff; color:white; padding:10px; display:flex; justify-content:space-between;
}
.chat-header button{
  background:transparent; border:none; color:white; font-size:16px; margin-left:5px; cursor:pointer;
}
.chat-body{flex:1; padding:10px; overflow-y:auto; background:#f5f5f5;}
.chat-body .user{text-align:right; margin:5px 0; color:#333;}
.chat-body .bot{text-align:left; margin:5px 0; color:#809bf4;}
.chat-body .bot.typing{
  font-style: italic;
  color: #aaa;
  animation: blink 1s infinite;
  margin: 5px 0;
}
@keyframes blink{
  0%,50%,100%{opacity:0.3;}
  25%,75%{opacity:1;}
}
.chat-footer{display:flex; padding:10px; background:#eee;}
.chat-footer input{flex:1; padding:5px; border:1px solid #ddd; border-radius:5px;}
.chat-footer button{margin-left:5px; background:#0084ff; color:white; border:none; padding:5px 12px; border-radius:5px; cursor:pointer;}
</style>
