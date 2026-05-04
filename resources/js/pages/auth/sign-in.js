import { GlobalSubmitForm, GlobalFormValidation } from "../../app";

function LoginUser() {
    // ---------- Validasi inputan form :begin ----------
    const LoginUserValidation = GlobalFormValidation.init("#login_user", {
        username: {
            validators: {
                notEmpty: {
                    message: "Username is required",
                },
            },
        },
        password: {
            validators: {
                notEmpty: {
                    message: "Password is required",
                },
            },
        },
    });
    // ---------- Validasi inputan form :end ----------

    const overlay = document.getElementById("fullscreen_loading_overlay");

    // ---------- Submit form ke url :begin ----------
    new GlobalSubmitForm({
        formId: "login_user",
        url: "/login",
        validator: LoginUserValidation,
        beforeSubmit: () => {
            if (overlay) overlay.classList.remove("d-none");
        },
        onSuccess: (data) => {
            if (overlay) overlay.classList.add("d-none");

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        },
        onError: (err) => {
            if (overlay) overlay.classList.add("d-none");
            notyf.error({
                message: "Login failed!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}

document.addEventListener("DOMContentLoaded", function () {
    LoginUser();
});
