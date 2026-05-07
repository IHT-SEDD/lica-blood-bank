// ---------- Helper: ambil ID dari URL saat ini----------
export function getDataFromURL(idx) {
    const segments = window.location.pathname.split("/");
    return segments[segments.length - idx];
}
