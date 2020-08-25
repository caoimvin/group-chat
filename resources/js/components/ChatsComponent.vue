<template>
  <div class="row">
    <div class="col-8 messages">
      <div class="card card-default">
        <div class="card-header">Messages</div>
        <div class="card-body p-0">
          <ul class="list-unstyled" style="height:300px; overflow-y:scroll" v-chat-scroll>
            <li class="p-2" v-for="message in allMessage">
              <strong>{{ message.user.name }}</strong>
              {{ message.message }}
            </li>
          </ul>
        </div>

        <input
          @keydown="getTypingEvent"
          @keyup.enter="saveMessage"
          v-model="newMessage"
          type="text"
          name="message"
          placeholder="Enter your message..."
          class="form-control"
        />
      </div>
      <span class="text-muted" v-if="typingUser">{{ typingUser.name }} is typing...</span>
    </div>

    <div class="col-4 active-users">
      <div class="card card-default">
        <div class="card-header">Active Users</div>
        <div class="card-body">
          <ul>
            <li class="py-2" v-for="(user, index) in users" :key="index">{{ user.name }}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      allMessage: [],
      newMessage: "",
      users: [],
      typingUser: "false"
    };
  },
  created() {
    this.getMessages();
    Echo.join("chats")
      .here(user => {
        this.users = user;
      })
      .joining(user => {
        this.users.push(user);
      })
      .leaving(user => {
        this.users = this.users.filter(u => u.id != user.id);
      })
      .listenForWhisper("typing", user => {
        this.typingUser = user;

        if (this.typingTimer) {
          clearTimeout(this.typingTimer);
        }

        this.typingTimer = setTimeout(() => {
          this.typingUser = false;
        }, 3000);
      })
      .listen("SendMessage", event => {
        this.allMessage.push(event.message);
      });
  },
  methods: {
    getMessages() {
      axios.get("messages").then(response => {
        this.allMessage = response.data;
      });
    },

    saveMessage() {
      this.allMessage.push({
        user: this.user,
        message: this.newMessage
      });
      axios.post("messages", { message: this.newMessage });
      this.newMessage = "";
    },

    getTypingEvent() {
      Echo.join("chats").whisper("typing", this.user);
    }
  }
};
</script>

<style lang="css" scoped>
.row {
  flex-direction: column;
}
.card {
  width: 90vw;
}
.active-users {
  margin-top: 50px;
}
</style>