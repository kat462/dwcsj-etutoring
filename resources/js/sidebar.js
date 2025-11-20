/* Small sidebar toggle logic for mobile screens
   - toggles `sidebar-open` on <body>
   - persists state in localStorage
   - closes on backdrop click or Escape
*/
(function(){
    const TOGGLE_KEY = 'dwcsj.sidebar.open';
    const btn = document.getElementById('mobileSidebarToggle');
    const backdrop = document.getElementById('sidebarBackdrop');

    function isOpen() {
        return document.body.classList.contains('sidebar-open');
    }

    function setOpen(val, persist=true) {
        if (val) document.body.classList.add('sidebar-open');
        else document.body.classList.remove('sidebar-open');
        if (persist) localStorage.setItem(TOGGLE_KEY, val ? '1' : '0');
    }

    function toggle() { setOpen(!isOpen()); }

    // Initialize
    document.addEventListener('DOMContentLoaded', function(){
        try {
            const stored = localStorage.getItem(TOGGLE_KEY);
            if (stored === '1') setOpen(true, false);
        } catch(e) {
            // ignore storage errors
        }

        if (btn) btn.addEventListener('click', function(e){ e.preventDefault(); toggle(); });
        if (backdrop) backdrop.addEventListener('click', function(){ setOpen(false); });

        document.addEventListener('keydown', function(e){ if (e.key === 'Escape') setOpen(false); });
    });
})();
