<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventory AI Assistant</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Custom CSS -->
    @yield('styles')
</head>
<body>
    <!-- Main Content -->
    <div class="wrapper">
        @include('layouts.header')
        
        <div class="main-content">
            @yield('content')
            @include('partials.chatbot-modal')
        </div>
        
        <!-- Floating Chat Button -->
        <button id="chatTrigger" class="btn btn-primary chat-trigger" onclick="openChatbot()">
            <i class="fas fa-robot"></i>
        </button>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sweet Alert for better notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chatbot Scripts -->
    <script>
    function openChatbot() {
        const chatbotModal = new bootstrap.Modal(document.getElementById('chatbotModal'));
        chatbotModal.show();
    }
    </script>

    <style>
    .chat-trigger {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-trigger i {
        font-size: 24px;
    }
    </style>
    
    <!-- Error Handler -->
    <script>
    function handleAjaxError(error) {
        console.error('Ajax Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong! Please try again.'
        });
    }

    // Global Ajax Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(xhr, status, error) {
            handleAjaxError(error);
        }
    });
    </script>

    <!-- Custom JS -->
    @yield('scripts')
</body>
</html>