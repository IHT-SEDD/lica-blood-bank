export function initSidenav() {
    const sideNav = document.querySelector(".side-nav");
    if (!sideNav) return;

    sideNav
        .querySelectorAll("li [data-bs-toggle='collapse']")
        .forEach((toggle) => {
            toggle.addEventListener("click", (e) => e.preventDefault());
        });

    const allCollapses = sideNav.querySelectorAll("li .collapse");
    allCollapses.forEach((collapse) => {
        collapse.addEventListener("show.bs.collapse", (event) => {
            const current = event.target;
            const ancestors = [];
            let el = current.parentElement;
            while (el && el !== sideNav) {
                if (el.classList.contains("collapse")) ancestors.push(el);
                el = el.parentElement;
            }
            allCollapses.forEach((other) => {
                if (other !== current && !ancestors.includes(other)) {
                    new bootstrap.Collapse(other, { toggle: false }).hide();
                }
            });
        });
    });

    const currentUrl = window.location.href.split(/[?#]/)[0];
    const currentPage = window.location.pathname.split("/").pop();

    sideNav.querySelectorAll(".side-nav-link[href]").forEach((link) => {
        const href = link.getAttribute("href");
        const match = href === currentPage || link.href === currentUrl;
        if (!match) return;

        sideNav
            .querySelectorAll("a.active, li.active, .collapse.show")
            .forEach((el) => {
                el.classList.remove("active", "show");
            });

        link.classList.add("active");
        link.closest("li.side-nav-item")?.classList.add("active");

        let parent = link.closest("li");
        while (parent && parent !== sideNav) {
            parent.classList.add("active");
            const parentCollapse = parent.closest(".collapse");
            if (parentCollapse) {
                parentCollapse.classList.add("show");
                const toggleLink = sideNav.querySelector(
                    `a[href="#${parentCollapse.id}"]`,
                );
                if (toggleLink) {
                    toggleLink.setAttribute("aria-expanded", "true");
                    toggleLink
                        .closest("li.side-nav-item")
                        ?.classList.add("active");
                }
                parent = parentCollapse.closest("li");
            } else {
                parent = parent.parentElement;
            }
        }
    });

    setTimeout(() => {
        const activeItem = sideNav.querySelector("li.active .active");
        const scrollContainer = document.querySelector(
            ".sidenav-menu .simplebar-content-wrapper",
        );
        if (activeItem && scrollContainer) {
            const offset = activeItem.offsetTop - 300;
            if (offset > 100) scrollToPosition(scrollContainer, offset, 600);
        }
    }, 200);

    function scrollToPosition(el, to, duration) {
        const start = el.scrollTop;
        const change = to - start;
        let currentTime = 0;
        (function animate() {
            currentTime += 20;
            el.scrollTop = easeInOutQuad(currentTime, start, change, duration);
            if (currentTime < duration) setTimeout(animate, 20);
        })();
    }

    function easeInOutQuad(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return (c / 2) * t * t + b;
        return (-c / 2) * (--t * (t - 2) - 1) + b;
    }
}
