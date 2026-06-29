/**
 * projectranslate.js
 * Additional English translation keys for the Project Detail page.
 * Works alongside the main i18n system in app.js.
 */
document.addEventListener("DOMContentLoaded", () => {
    const projectTranslations = {
        "project.stat.category": "Category",
        "project.stat.year": "Year",
        "project.stat.tech": "Tech Used",
        "project.about": "About Project",
    };

    const originalTexts = {};
    document.querySelectorAll("[data-i18n]").forEach((el) => {
        const key = el.getAttribute("data-i18n");
        if (projectTranslations[key]) {
            originalTexts[key] = el.innerHTML;
        }
    });

    const applyProjectLang = () => {
        const savedLang = localStorage.getItem("lang") || "id";
        document.querySelectorAll("[data-i18n]").forEach((el) => {
            const key = el.getAttribute("data-i18n");
            if (projectTranslations[key]) {
                if (savedLang === "en") {
                    el.innerHTML = projectTranslations[key];
                } else if (savedLang === "id" && originalTexts[key]) {
                    el.innerHTML = originalTexts[key];
                }
            }
        });
    };

    applyProjectLang();

    const langToggle = document.getElementById("lang-toggle");
    if (langToggle) {
        langToggle.addEventListener("click", () => {
            setTimeout(applyProjectLang, 0);
        });
    }
});
