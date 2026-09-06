<!-- resources/views/partials/theme-switcher.blade.php -->
<div class="industrial-theme-switcher-wrapper d-inline-flex align-items-center">
    <button class="industrial-theme-btn d-inline-flex align-items-center justify-content-center" 
            id="themeSwitcherBtn" 
            type="button" 
            aria-label="Toggle Theme" 
            title="Chuyển đổi giao diện: Sáng / Tối">
        
        <!-- Only Theme Icon -->
        <i class="bi bi-sun-fill theme-icon-sun d-none" style="font-size: 1rem; color: #ff9800;"></i>
        <i class="bi bi-moon-stars-fill theme-icon-moon d-none" style="font-size: 1rem; color: #e6e4df;"></i>

        <!-- Hidden elements for JS compatibility -->
        <span class="theme-text-light d-none"></span>
        <span class="theme-text-dark d-none"></span>
    </button>
</div>
