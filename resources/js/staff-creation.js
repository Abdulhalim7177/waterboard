// Staff Creation Form Validation and Tab Navigation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const tabs = document.querySelectorAll('.nav-link');
    const tabContents = document.querySelectorAll('.tab-pane');
    const submitBtn = document.querySelector('button[type="submit"]');
    const progressIndicator = document.querySelector('.progress-indicator');
    
    // Initialize progress indicator if it doesn't exist
    if (!progressIndicator) {
        createProgressIndicator();
    }
    
    // Initialize navigation buttons if they don't exist
    addNavigationButtons();
    
    // Initialize validation
    initializeValidation();
    
    // Update progress and button state on load
    updateProgressAndButton();
});

function createProgressIndicator() {
    const tabContainer = document.querySelector('.nav.nav-tabs');
    const progressHtml = `
        <div class="progress-indicator mb-4">
            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
            </div>
            <div class="progress-labels d-flex justify-content-between mt-1">
                <span class="text-muted small">0%</span>
                <span class="text-muted small">100%</span>
            </div>
        </div>
    `;
    tabContainer.insertAdjacentHTML('beforebegin', progressHtml);
}

function addNavigationButtons() {
    const tabContentContainer = document.querySelector('.tab-content');
    
    // Add navigation buttons to each tab pane
    document.querySelectorAll('.tab-pane').forEach((pane, index) => {
        // Skip adding buttons to the last tab since it will have the submit button
        if (index === document.querySelectorAll('.tab-pane').length - 1) {
            return;
        }
        
        const navButtonsHtml = `
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" onclick="goToPrevTab(${index})">
                    Previous
                </button>
                <button type="button" class="btn btn-primary" onclick="goToNextTab(${index})">
                    Next
                </button>
            </div>
        `;
        pane.insertAdjacentHTML('beforeend', navButtonsHtml);
    });
    
    // Add navigation buttons to the last tab pane (before the submit button)
    const lastPane = document.querySelectorAll('.tab-pane').item(document.querySelectorAll('.tab-pane').length - 1);
    const lastPaneNavButtons = `
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-secondary" onclick="goToPrevTab(${document.querySelectorAll('.tab-pane').length - 1})">
                Previous
            </button>
            <button type="button" class="btn btn-light" onclick="resetForm()">
                Reset
            </button>
        </div>
    `;
    lastPane.insertAdjacentHTML('afterbegin', lastPaneNavButtons);
    
    // Move submit button to the end of the last tab
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.classList.add('mt-4');
        lastPane.appendChild(submitButton);
    }
}

function initializeValidation() {
    // Add event listeners to all form inputs
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('change', updateProgressAndButton);
        input.addEventListener('input', updateProgressAndButton);
    });
    
    // Also listen for tab changes
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('shown.bs.tab', updateProgressAndButton);
    });
}

function updateProgressAndButton() {
    const totalTabs = document.querySelectorAll('.tab-pane').length;
    let completedTabs = 0;
    
    // Check each tab pane for required fields
    document.querySelectorAll('.tab-pane').forEach(pane => {
        const requiredFields = pane.querySelectorAll('[required]');
        let validCount = 0;
        
        requiredFields.forEach(field => {
            // Check if the field has a value (not empty)
            if (field.type === 'checkbox' || field.type === 'radio') {
                if (field.checked) {
                    validCount++;
                }
            } else if (field.value.trim() !== '') {
                validCount++;
            }
        });
        
        // Mark tab as completed if all required fields are filled
        if (validCount === requiredFields.length) {
            completedTabs++;
        }
    });
    
    // Calculate progress percentage
    const progressPercent = Math.round((completedTabs / totalTabs) * 100);
    
    // Update progress bar
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        progressBar.style.width = progressPercent + '%';
        progressBar.textContent = progressPercent + '%';
    }
    
    // Enable or disable submit button based on completion
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = (completedTabs < totalTabs);
        
        if (submitBtn.disabled) {
            submitBtn.title = `Complete all ${totalTabs} sections to enable submission`;
        } else {
            submitBtn.title = 'Submit form';
        }
    }
}

function goToNextTab(currentIndex) {
    const nextIndex = currentIndex + 1;
    if (nextIndex < document.querySelectorAll('.tab-pane').length) {
        const nextTabLink = document.querySelectorAll('.nav-link')[nextIndex];
        if (nextTabLink) {
            // Check if current tab has all required fields filled
            const currentPane = document.querySelectorAll('.tab-pane')[currentIndex];
            const requiredFields = currentPane.querySelectorAll('[required]');
            let validCount = 0;
            
            requiredFields.forEach(field => {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    if (field.checked) {
                        validCount++;
                    }
                } else if (field.value.trim() !== '') {
                    validCount++;
                }
            });
            
            // Only proceed if current tab is completed
            if (validCount === requiredFields.length) {
                nextTabLink.click();
            } else {
                alert('Please fill in all required fields in the current section before proceeding.');
            }
        }
    }
}

function goToPrevTab(currentIndex) {
    const prevIndex = currentIndex - 1;
    if (prevIndex >= 0) {
        const prevTabLink = document.querySelectorAll('.nav-link')[prevIndex];
        if (prevTabLink) {
            prevTabLink.click();
        }
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        document.querySelector('form').reset();
        // Go back to first tab
        document.querySelectorAll('.nav-link')[0].click();
        updateProgressAndButton();
    }
}