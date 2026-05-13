export function initCounter() {
    const fmt = (n) =>
        n % 1 === 0
            ? n.toLocaleString()
            : n.toLocaleString(undefined, {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
              });

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const counter = entry.target;
                let target = parseFloat(
                    counter.getAttribute("data-target").replace(/,/g, ""),
                );
                let current = 0;
                const increment = Number.isInteger(target)
                    ? Math.floor(target / 25)
                    : target / 25;

                const update = () => {
                    current = Math.min(current + increment, target);
                    counter.innerText = fmt(current);
                    if (current < target) requestAnimationFrame(update);
                    else counter.innerText = fmt(target);
                };

                update();
                obs.unobserve(counter);
            });
        },
        { threshold: 1 },
    );

    document
        .querySelectorAll("[data-target]")
        .forEach((c) => observer.observe(c));
}
