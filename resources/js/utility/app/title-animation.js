export function initTitleTextAnimation() {
    const originalTitle = document.title;
    const fullTitle = originalTitle + " - LICA Blood Bank | ";
    let scrollIndex = 0;
    let animationId;

    function scrollTitle() {
        if (document.hidden) return;
        document.title =
            fullTitle.slice(scrollIndex) + fullTitle.slice(0, scrollIndex);
        scrollIndex = (scrollIndex + 1) % fullTitle.length;
        animationId = setTimeout(scrollTitle, 100);
    }

    document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
            clearTimeout(animationId);
            document.title = originalTitle;
        } else {
            scrollTitle();
        }
    });

    if (!document.hidden) scrollTitle();
}
