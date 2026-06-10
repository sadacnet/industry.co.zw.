    </div><!-- /main-content -->
    
    <script>
        // Common functions for all admin pages
        
        // Show alert message
        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            const mainContent = document.querySelector('.main-content');
            const topBar = document.querySelector('.top-bar');
            mainContent.insertBefore(alertDiv, topBar.nextSibling);
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
        
        // Format date
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-ZA', options);
        }
        
        // Confirm before delete
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }
    </script>
</body>
</html>