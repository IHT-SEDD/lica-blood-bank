export function initLanguageSwitcher() {
    document.querySelectorAll(".language-switcher").forEach((el) => {
        el.addEventListener("click", async function () {
            const { lang, flag } = this.dataset;
            try {
                const response = await fetch(
                    window.AppConfig.languageSwitchUrl,
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content"),
                        },
                        body: JSON.stringify({ lang }),
                    },
                );

                const data = await response.json();
                if (!response.ok)
                    throw new Error(
                        data.message || "Failed to switch language",
                    );
                if (data.success) {
                    const imageEl = document.getElementById(
                        "selected-language-image",
                    );
                    const codeEl = document.getElementById(
                        "selected-language-code",
                    );

                    if (imageEl) imageEl.setAttribute("src", flag);
                    if (codeEl) codeEl.textContent = lang.toUpperCase();

                    window.location.reload();
                }
            } catch (error) {
                console.error("Language Switch Error:", error);
                notyf.error({ message: "Failed to switch language!" });
            }
        });
    });
}
