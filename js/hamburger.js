 // Toggle sidebar on hamburger menu click
        document.getElementById('hamburgerMenu').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const topbar = document.querySelector('.topbar');
            const dashboardMain = document.getElementById('.dashboardMain');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            topbar.style.display = 'none';
            dashboardMain.style.marginTop = '1rem';

            // Change icon based on state
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close sidebar when clicking on overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const hamburger = document.getElementById('hamburgerMenu');
            const icon = hamburger.querySelector('i');

            sidebar.classList.remove('active');
            this.classList.remove('active');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        });

        // Close sidebar when clicking on a link (mobile only)
        if (window.innerWidth < 768) {
            document.querySelectorAll('.sidebar a').forEach(link => {
                link.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    const hamburger = document.getElementById('hamburgerMenu');
                    const icon = hamburger.querySelector('i');
                    const dashboardMain = document.getElementById('.dashboardMain');


                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                    dashboardMain.style.marginTop = '10rem';

                });
            });
        }