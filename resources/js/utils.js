export function initializeAlerts() {
    const alert = document.getElementById('success-alert');
    if (alert) {
        const alertContainer = alert.parentElement;
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                alertContainer.remove();
            }, 300); // Wait for fade out animation to complete
        }, 3000); // Wait 3 seconds before starting fade out
    }
}
