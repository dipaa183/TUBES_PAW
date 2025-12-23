<?php
namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $users = []; // Map resourceId -> ['id' => int, 'role' => string]
    protected $dbConfig;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        // Hardcoded config matching project
        $this->dbConfig = [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'name' => 'fotokopi_online'
        ];
        echo "Chat Server Started!\n";
    }

    private function getDbConnection()
    {
        $conn = new \mysqli($this->dbConfig['host'], $this->dbConfig['user'], $this->dbConfig['pass'], $this->dbConfig['name']);
        if ($conn->connect_error) {
            echo "DB Connection Failed: " . $conn->connect_error . "\n";
            return null;
        }
        return $conn;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Parse query string for user authentication
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $query);

        $userId = $query['user_id'] ?? 0;
        $role = $query['role'] ?? 'guest';

        if ($userId == 0) {
            // $conn->close();
            // return;
            // Allow guests? No.
        }

        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = ['id' => $userId, 'role' => $role];

        echo "New connection! ({$conn->resourceId}) User: $userId Role: $role\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $sender = $this->users[$from->resourceId];
        $data = json_decode($msg, true);

        echo "Message from {$sender['id']}: $msg\n";

        if (!$data)
            return;

        $conn = $this->getDbConnection();
        if (!$conn)
            return;

        $txt = $data['message'] ?? '';
        if ($txt === '')
            return;

        // Validasi & Routing
        if ($sender['role'] === 'user') {
            // User sends to Admins
            // Save to DB
            $this->saveMessage($conn, $sender['id'], 'user', $txt);

            // Broadcast to Admins
            foreach ($this->clients as $client) {
                if (isset($this->users[$client->resourceId]) && $this->users[$client->resourceId]['role'] === 'admin') {
                    $client->send(json_encode([
                        'from_user_id' => $sender['id'],
                        'message' => $txt,
                        'type' => 'new_message'
                    ]));
                }
            }
            // Echo back to sender (optional if frontend handles optimistic UI)
        } elseif ($sender['role'] === 'admin') {
            // Admin sends to ALL connected Users (Broadcast)

            // 1. Iterate through all clients to find users
            foreach ($this->clients as $client) {
                // Check if this client is a 'user'
                if (isset($this->users[$client->resourceId]) && $this->users[$client->resourceId]['role'] === 'user') {
                    $targetUserId = $this->users[$client->resourceId]['id'];

                    // 2. Save to DB for THIS specific user (so it shows in their history)
                    // We must reconnect/check DB for each, or assume $conn is alive (it is)
                    $this->saveMessage($conn, $targetUserId, 'admin', $txt);

                    // 3. Send via WebSocket
                    $client->send(json_encode([
                        'from_admin' => true,
                        'message' => $txt,
                        'type' => 'new_message'
                    ]));
                }
            }
        }
        $conn->close();
    }

    private function saveMessage($conn, $userId, $senderType, $message)
    {
        $stmt = $conn->prepare("INSERT INTO chat_messages (user_id, sender_type, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $senderType, $message);
        $stmt->execute();
        $stmt->close();
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
