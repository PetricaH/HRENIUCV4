<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<title>Admin | Add Technology</title>
<style>
        /* Toast Notification Styling */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column-reverse; /* Newest notification appears at the bottom */
            gap: 10px;
        }
        .toast {
            min-width: 250px;
            padding: 15px;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            opacity: 0;
            transform: translateY(100%);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .toast.success { background-color: #28a745; }
        .toast.error { background-color: #dc3545; }
        .toast .close-btn {
            font-weight: bold;
            color: #fff;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>
        
        <h2>Add New Technology</h2>
        <form action="add_techs_function.php" method="POST" enctype="multipart/form-data">
            <label for="tech_name">Technology Name:</label>
            <input type="text" name="tech_name" id="tech_name" required>
            <br>

            <label for="tech_logo">Upload Technology Logo:</label>
            <input type="file" name="tech_logo" id="tech_logo" required>
            <br>

            <button type="submit" name="add_technology">Add Technology</button>
        </form>
        <!-- Toast Notification Container -->
        <div id="toast-container" class="toast-container"></div>
    </div>
    <script>
    // notification for success of failure in adding new tech
    document.addEventListener('DOMContentLoaded', function() {
        const toastContainer = document.getElementById('toast-container');
        const message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
        const msgType = "<?php echo isset($_SESSION['msg_type']) ? $_SESSION['msg_type'] : ''; ?>";
        
        if (message) {
            showToast(message, msgType);
            <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>arguments
        }

        // function to show toast notification
        function showToast(message, type) {
            // create toast element
            const toast = document.createElement('div');
            toast.classList.add('toast', 'show', type);
            toast.innerHTML = `
                <span>${message}</span>
                <button class="close-btn" onclick="window.closeToast(this)">X</button>
            `;

            // append to container
            toastContainer.appendChild(toast);

            // auto-hide after 6 seconds
            setTimeout(() => {
                if (toast.classList.contains('show')) {
                    window.closeToast(toast.querySelector('.close-btn'));
                }
            }, 6000);
        }
        
    // define closeToast function as a global function on the window
    window.closeToast = (button) => {
        const toast = button.parrentElement;
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300); // this is to allow transition time before removing
    };
});
</script>
</body>
</html>
