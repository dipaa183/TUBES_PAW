<?php if (isset($_SESSION['user_id'])): ?>
    <div id="chat-widget" class="card shadow"
        style="position: fixed; bottom: 20px; right: 20px; width: 300px; display: none; z-index: 1000;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Chat Support</span>
            <button type="button" class="btn-close btn-close-white" onclick="toggleChat()"></button>
        </div>
        <div class="card-body" id="chat-messages" style="height: 300px; overflow-y: auto;">
            <!-- Messages appear here -->
        </div>
        <div class="card-footer">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control" placeholder="Tulis pesan...">
                <button class="btn btn-primary" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
    <button id="chat-btn" class="btn btn-primary rounded-circle shadow p-3"
        style="position: fixed; bottom: 20px; right: 20px; z-index: 999;" onclick="toggleChat()">
        <i class="fas fa-comments fa-2x"></i>
    </button>

    <script>
        const userId = <?= $_SESSION['user_id'] ?>;
        const role = '<?= $_SESSION['role'] ?>';
        let conn;

        function initChat() {
            conn = new WebSocket('ws://localhost:8080?user_id=' + userId + '&role=' + role);

            conn.onopen = function (e) {
                console.log("Connected to chat server");
                addMessage("System", "Terhubung ke layanan chat.");
            };

            conn.onmessage = function (e) {
                const data = JSON.parse(e.data);
                if (data.type === 'new_message') {
                    addMessage(data.from_admin ? 'Admin' : 'User ' + data.from_user_id, data.message);
                }
            };

            conn.onclose = function (e) {
                console.log("Disconnected");
            };
        }

        function sendMessage() {
            const input = document.getElementById('chat-input');
            const msg = input.value;
            if (!msg) return;

            let payload = {
                message: msg
            };

            if (role === 'admin') {
                // Broadcast mode: no target needed
            }

            conn.send(JSON.stringify(payload));
            addMessage('Me', msg);
            input.value = '';
        }

        function addMessage(sender, text) {
            const div = document.createElement('div');
            div.className = 'mb-2';
            div.innerHTML = `<strong>${sender}:</strong> ${text}`;
            document.getElementById('chat-messages').appendChild(div);
            document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
        }

        function toggleChat() {
            const widget = document.getElementById('chat-widget');
            const btn = document.getElementById('chat-btn');
            if (widget.style.display === 'none') {
                widget.style.display = 'block';
                btn.style.display = 'none';
                if (!conn) initChat();
            } else {
                widget.style.display = 'none';
                btn.style.display = 'block';
            }
        }
    </script>
<?php endif; ?>