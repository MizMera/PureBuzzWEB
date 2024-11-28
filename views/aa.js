<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Interface</title>

    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            z-index: 1030;
        }

        .sidebar {
            background-color: #f8f9fa;
            height: 100vh;
            padding: 10px;
        }

        .main-content {
            padding: 20px;
            padding-top: 100px;
            min-height: calc(100vh - 100px);
        }

        .chat-container {
            margin-top: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            padding: 0;
        }

        .chat-header {
            background-color: #ffcc00;
            padding: 15px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .chat-header h3 {
            margin: 0;
            font-size: 22px;
        }

        .chat-body {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
        }

        .chat-message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            max-width: 80%;
        }

        .user-message {
            background-color: #e1f7d5;
            align-self: flex-end;
            text-align: right;
            margin-left: auto;
        }

        .bot-message {
            background-color: #f1f0f0;
            align-self: flex-start;
            text-align: left;
            margin-right: auto;
        }

        .chat-input {
            display: flex;
            padding: 15px;
            border-top: 1px solid #ccc;
        }

        .chat-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .chat-input button {
            padding: 10px 15px;
            background-color: #ffcc00;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .chat-input button:hover {
            background-color: #e6b800;
        }
    </style>
</head>

<body>
    <div class="ala">
        <header class="container-scroller">
            <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                    <div class="me-3">
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                            data-bs-toggle="minimize">
                            <span class="icon-menu"></span>
                        </button>
                    </div>
                    <div>
                        <a class="navbar-brand brand-logo" href="index.html">
                            <img src="logo.png" style="height: 80px;" alt="Company Logo">
                        </a>
                    </div>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-top">
                    <ul class="navbar-nav">
                        <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                            <h1 style="margin-left: 500px;" class="welcome-text">Chatbot Interface</h1>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <!-- Your Navbar Items -->
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-bs-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
        </header>

        <div style="margin-top: 100px;" class="d-flex">

            <!-- Sidebar -->
            <nav class="sidebar">
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.html">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="Orders.html">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Submit Claim</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">Logout</a></li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div class="container main-content">
                <section class="chat-section">
                    <!-- Chatbot Interface -->
                    <div class="chat-container">
                        <div class="chat-header">
                            <h3>Bee Claims Chatbot</h3>
                        </div>
                        <div class="chat-body" id="chatBody">
                            <!-- Chat messages will appear here -->
                        </div>
                        <div class="chat-input">
                            <input type="text" id="userInput" placeholder="Type your message..." />
                            <button onclick="sendMessage()">Send</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container-fluid">
            <p class="text-muted">&copy; 2024 PureBuzz. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        function sendMessage() {
            const userInput = document.getElementById("userInput");
            const chatBody = document.getElementById("chatBody");

            if (userInput.value.trim() === "") return;

            // Append user message
            const userMessage = document.createElement("div");
            userMessage.className = "chat-message user-message";
            userMessage.textContent = userInput.value;
            chatBody.appendChild(userMessage);

            // Simulate bot response (replace with actual bot response logic)
            setTimeout(() => {
                const botMessage = document.createElement("div");
                botMessage.className = "chat-message bot-message";
                botMessage.textContent = "This is a bot response to: " + userInput.value; // Replace with actual response
                chatBody.appendChild(botMessage);
                chatBody.scrollTop = chatBody.scrollHeight; // Scroll to the bottom
            }, 1000);

            userInput.value = ""; // Clear input
            chatBody.scrollTop = chatBody.scrollHeight; // Scroll to the bottom
        }
    </script>
</body>

</html>