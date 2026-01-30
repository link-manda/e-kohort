## 2024-05-23 - Skip to Content Link
**Learning:** Dashboard layouts with persistent sidebars create a keyboard trap where users must tab through every menu item to reach content. This is a critical barrier in data-heavy apps like this health dashboard.
**Action:** Always inspect the main layout (`dashboard-layout` or `app`) for a skip link first. Implement it with high z-index to ensure it overlays fixed sidebars/headers.
