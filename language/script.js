document.addEventListener("DOMContentLoaded", Script);
function Script() {
    let languageSession = sessionStorage.getItem("websiteLanguage") || "en";
    loadLanguageScript(sessionStorage.getItem("websiteLanguage"));

    document.addEventListener("click", function () {
        languageSession = sessionStorage.getItem("websiteLanguage") || "en";
        if (languageSession == "en") {
            sessionStorage.setItem("websiteLanguage", "np");
        } else {
            sessionStorage.setItem("websiteLanguage", "en");
        }

        loadLanguageScript(sessionStorage.getItem("websiteLanguage"));
    })
}

function setLanguageInHomePage(language) {
    document.getElementById("hero_title").textContent = language.hero_title;
    document.getElementById("hero_subtitle").textContent = language.hero_subtitle;
    document.getElementById("explore").textContent = language.explore;
    document.getElementById("why_choose").textContent = language.why_choose;
    document.getElementById("healthy").textContent = language.healthy;
    document.getElementById("healthy_desc").textContent = language.healthy_desc;
    document.getElementById("sustainable").textContent = language.sustainable;
    document.getElementById("sustainable_desc").textContent = language.sustainable_desc;
    document.getElementById("sourced").textContent = language.sourced;
    document.getElementById("sourced_desc").textContent = language.sourced_desc;
    document.getElementById("featured_products").textContent = language.featured_products;
    document.getElementById("black_tea").textContent = language.black_tea;
    document.getElementById("chamomile_tea").textContent = language.chamomile_tea;
    document.getElementById("herbal_tea").textContent = language.herbal_tea;
    document.getElementById("green_tea").textContent = language.green_tea;
    document.getElementById("flower_tea").textContent = language.flower_tea;
}

function loadLanguageScript(language) {
    const scriptId = "languageScript";
    const existingScript = document.getElementById(scriptId);

    // Remove the previous script if it exists
    if (existingScript) {
        existingScript.remove();
    }

    // Add the new script dynamically
    const script = document.createElement("script");
    script.id = scriptId;
    script.src = `/language/language.${language}.js`;
    script.onload = () => {
        console.log(`Loaded language.${language}.js`);
        console.log(language === "en" ? window.LANG_EN : window.LANG_NP);
        window.languageObj = language === "en" ? window.LANG_EN : window.LANG_NP;
        setLanguageInHomePage(window.languageObj);
    };
    script.onerror = () => {
        console.error(`Failed to load language.${language}.js`);
    };

    document.head.appendChild(script);
}