/**
 * HRM Sync Functionality
 * Handles real-time synchronization between local and HRM systems
 */

class HrmSyncManager {
    constructor() {
        this.syncInProgress = false;
        this.initEventListeners();
    }

    initEventListeners() {
        // Handle sync buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.sync-staff-btn')) {
                e.preventDefault();
                const fullRefresh = e.target.closest('.sync-staff-btn').dataset.fullRefresh === 'true';
                this.performSync(fullRefresh);
            }
        });

        // Handle form submissions that trigger sync
        document.addEventListener('submit', (e) => {
            if (e.target.closest('form[data-sync-on-submit]')) {
                e.preventDefault();
                this.handleFormSync(e.target);
            }
        });
    }

    async performSync(fullRefresh = false) {
        if (this.syncInProgress) {
            this.showNotification('Sync already in progress...', 'warning');
            return;
        }

        this.syncInProgress = true;
        this.showNotification('Starting synchronization...', 'info');

        try {
            const params = new URLSearchParams({
                full_refresh: fullRefresh
            });

            const response = await fetch(`/hr/staff/sync?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.showNotification(
                    fullRefresh 
                        ? 'Complete refresh completed successfully' 
                        : 'Synchronization completed successfully',
                    'success'
                );
            } else {
                throw new Error('Sync failed');
            }
        } catch (error) {
            console.error('Sync error:', error);
            this.showNotification('Synchronization failed: ' + error.message, 'error');
        } finally {
            this.syncInProgress = false;
        }
    }

    async handleFormSync(form) {
        if (this.syncInProgress) {
            this.showNotification('Sync in progress, please wait...', 'warning');
            return;
        }

        this.syncInProgress = true;
        this.showNotification('Processing request and syncing data...', 'info');

        try {
            const formData = new FormData(form);
            const action = form.getAttribute('action');
            const method = form.getAttribute('method') || 'POST';

            const response = await fetch(action, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                // After form submission, trigger a sync
                await this.performSync(true); // Always do a full refresh after CRUD operations
            } else {
                const error = await response.json();
                this.showNotification('Form submission failed: ' + error.message, 'error');
            }
        } catch (error) {
            console.error('Form sync error:', error);
            this.showNotification('Form submission failed: ' + error.message, 'error');
        } finally {
            this.syncInProgress = false;
        }
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.hrm-sync-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `hrm-sync-notification alert alert-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : type} alert-dismissible fade show`;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.width = '400px';
        notification.style.maxWidth = '90%';

        notification.innerHTML = `
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="d-flex align-items-center">
                <i class="ki-duotone ki-information-5 me-2 ${type === 'error' ? 'text-danger' : type === 'warning' ? 'text-warning' : 'text-info'}">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);

        // Add close event
        const closeBtn = notification.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                notification.remove();
            });
        }
    }

    // Method to check sync status periodically
    startSyncStatusCheck() {
        setInterval(async () => {
            if (!this.syncInProgress) {
                try {
                    const response = await fetch('/hr/staff/sync-status', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.outOfSync) {
                            this.showNotification('Data may be out of sync. Consider syncing with HRM system.', 'warning');
                        }
                    }
                } catch (error) {
                    console.error('Error checking sync status:', error);
                }
            }
        }, 300000); // Check every 5 minutes
    }
}

// Initialize HrmSyncManager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.hrmSyncManager = new HrmSyncManager();
    
    // Start periodic status check
    window.hrmSyncManager.startSyncStatusCheck();
});

// Add a helper function to manually trigger sync
window.triggerHrmSync = function(fullRefresh = false) {
    if (window.hrmSyncManager) {
        window.hrmSyncManager.performSync(fullRefresh);
    }
};