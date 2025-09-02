import './bootstrap';

// Import Metronic theme components
import KTThemeMode from '../../public/assets/js/layout/theme-mode/theme-mode';
import KTApp from '../../public/assets/js/components/app';
import KTMenu from '../../public/assets/js/components/menu';
import KTDrawer from '../../public/assets/js/components/drawer';
import KTScroll from '../../public/assets/js/components/scroll';

// Initialize theme mode
KTThemeMode.init();

// Initialize app components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize KTApp
    KTApp.init();
    
    // Initialize menus
    const menus = document.querySelectorAll('[data-kt-menu="true"]');
    menus.forEach(menu => {
        KTMenu.createInstances(menu);
    });
    
    // Initialize drawers
    const drawers = document.querySelectorAll('[data-kt-drawer="true"]');
    drawers.forEach(drawer => {
        KTDrawer.createInstances(drawer);
    });
    
    // Initialize scroll components
    const scrolls = document.querySelectorAll('[data-kt-scroll="true"]');
    scrolls.forEach(scroll => {
        KTScroll.createInstances(scroll);
    });
});

// Make components globally available
window.KTThemeMode = KTThemeMode;
window.KTApp = KTApp;
window.KTMenu = KTMenu;
window.KTDrawer = KTDrawer;
window.KTScroll = KTScroll;