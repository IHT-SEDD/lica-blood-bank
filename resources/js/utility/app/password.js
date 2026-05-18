export function initPassword() {
    document.querySelectorAll("[data-password]").forEach((el) => {
        const password = el.querySelector(".form-password");
        const eyeOn = el.querySelector(".password-eye-on");
        const eyeOff = el.querySelector(".password-eye-off");
        if (!password || !eyeOn || !eyeOff) return;

        eyeOff.classList.add("d-none");

        eyeOn.addEventListener("click", () => {
            password.type = "text";
            eyeOn.classList.add("d-none");
            eyeOff.classList.remove("d-none");
        });
        eyeOff.addEventListener("click", () => {
            password.type = "password";
            eyeOff.classList.add("d-none");
            eyeOn.classList.remove("d-none");
        });
    });
}
