// Keyboard Shortcuts Handler
function initKeyboardShortcuts() {
    // Search shortcut (Ctrl/Cmd + K)
    document.addEventListener('keydown', function(e) {
        // Search shortcut - only when not typing in an input
        if ((e.ctrlKey || e.metaKey) && e.key === 'k' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
            e.preventDefault();
            
            // Function to find and focus search input
            const focusSearchInput = () => {
                // Try to find visible search input first
                const visibleSearchInput = Array.from(document.querySelectorAll('input[type="search"], input[name="search"]'))
                    .find(input => {
                        const style = window.getComputedStyle(input);
                        return style.display !== 'none' && style.visibility !== 'hidden' && input.offsetParent !== null;
                    });
                
                if (visibleSearchInput) {
                    visibleSearchInput.focus();
                    visibleSearchInput.select();
                    return true;
                }
                return false;
            };
            
            // First, try to find the sidebar search button using data attribute
            let searchButton = document.querySelector('button[data-search-toggle]');
            
            // If not found, try to find by other methods
            if (!searchButton) {
                const searchButtons = Array.from(document.querySelectorAll('button, [role="button"]'))
                    .filter(btn => {
                        const ariaLabel = btn.getAttribute('aria-label');
                        const text = btn.textContent || '';
                        const onclick = btn.getAttribute('@click') || btn.getAttribute('onclick') || '';
                        return (ariaLabel && ariaLabel.toLowerCase().includes('search')) ||
                               (text.toLowerCase().includes('search') && onclick.includes('showSearch')) ||
                               onclick.includes('showSearch');
                    });
                if (searchButtons.length > 0) {
                    searchButton = searchButtons[0];
                }
            }
            
            if (searchButton) {
                // Click the button to open search
                searchButton.click();
                
                // Wait for the search input to appear, then focus
                const tryFocus = (attempts = 0) => {
                    if (attempts > 10) {
                        // Give up after 1 second
                        focusSearchInput();
                        return;
                    }
                    
                    const searchInput = document.querySelector('input[type="search"], input[name="search"]');
                    if (searchInput) {
                        const style = window.getComputedStyle(searchInput);
                        if (style.display !== 'none' && style.visibility !== 'hidden') {
                            searchInput.focus();
                            searchInput.select();
                        } else {
                            // Input exists but hidden, wait a bit more
                            setTimeout(() => tryFocus(attempts + 1), 50);
                        }
                    } else {
                        // Input not found yet, wait a bit more
                        setTimeout(() => tryFocus(attempts + 1), 50);
                    }
                };
                
                setTimeout(() => tryFocus(), 100);
                return;
            }
            
            // Alternative: Try to find sidebar container with showSearch
            const sidebarContainers = Array.from(document.querySelectorAll('[x-data]'))
                .filter(el => {
                    const xDataAttr = el.getAttribute('x-data');
                    return xDataAttr && xDataAttr.includes('showSearch');
                });
            
            if (sidebarContainers.length > 0 && window.Alpine) {
                const sidebarContainer = sidebarContainers[0];
                
                // Try to access Alpine data directly
                if (sidebarContainer.__x && sidebarContainer.__x.$data.showSearch !== undefined) {
                    sidebarContainer.__x.$data.showSearch = true;
                    
                    setTimeout(() => {
                        const searchInput = sidebarContainer.querySelector('input[type="search"], input[name="search"]');
                        if (searchInput) {
                            searchInput.focus();
                            searchInput.select();
                        } else {
                            focusSearchInput();
                        }
                    }, 200);
                    return;
                }
            }
            
            // If sidebar search not found or Alpine not ready, try to find any search input
            if (!focusSearchInput()) {
                // Last resort: find any search input and try to make it visible
                const anySearchInput = document.querySelector('input[type="search"], input[name="search"]');
                if (anySearchInput) {
                    // Try to find parent with x-show and open it
                    let parent = anySearchInput.closest('[x-show]');
                    if (parent && parent.__x) {
                        const xShowAttr = parent.getAttribute('x-show');
                        if (xShowAttr && parent.__x.$data[xShowAttr.trim()] !== undefined) {
                            parent.__x.$data[xShowAttr.trim()] = true;
                            setTimeout(() => {
                                anySearchInput.focus();
                                anySearchInput.select();
                            }, 150);
                            return;
                        }
                    }
                    // If no x-show parent, just try to focus
                    anySearchInput.focus();
                    anySearchInput.select();
                }
            }
        }
        
        // Escape to close modals/dropdowns and search
        if (e.key === 'Escape') {
            // Close search in sidebar if open
            const sidebarSearchContainer = document.querySelector('[x-data*="showSearch"]');
            if (sidebarSearchContainer && sidebarSearchContainer.__x && sidebarSearchContainer.__x.$data.showSearch) {
                sidebarSearchContainer.__x.$data.showSearch = false;
                // Blur any focused search input
                const activeElement = document.activeElement;
                if (activeElement && (activeElement.type === 'search' || activeElement.name === 'search')) {
                    activeElement.blur();
                }
                return;
            }
            
            // Close any open dropdowns
            document.querySelectorAll('[x-data*="open"]').forEach(el => {
                if (el.__x && el.__x.$data.open) {
                    el.__x.$data.open = false;
                }
            });
        }
        
        // Navigation shortcuts (only when not in input)
        if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
            // Dashboard shortcut (Ctrl/Cmd + D)
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                const dashboardLink = document.querySelector('a[href*="dashboard"]');
                if (dashboardLink) {
                    window.location.href = dashboardLink.href;
                }
            }
        }
    });
    
    // Show keyboard shortcuts help
    const shortcuts = {
        'Ctrl/Cmd + K': 'Focus search',
        'Ctrl/Cmd + D': 'Go to dashboard',
        'Escape': 'Close modals/dropdowns'
    };
    
    // Add tooltip for keyboard shortcuts
    if (document.querySelector('[data-shortcuts]')) {
        console.log('Keyboard shortcuts available:', shortcuts);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initKeyboardShortcuts);
} else {
    // DOM is already ready
    initKeyboardShortcuts();
}

// Also wait for Alpine to be ready
if (window.Alpine) {
    // Alpine is already loaded
    window.Alpine.nextTick(() => {
        // Keyboard shortcuts are already initialized
    });
} else {
    // Wait for Alpine to load
    document.addEventListener('alpine:init', () => {
        // Alpine is now initialized
    });
}
