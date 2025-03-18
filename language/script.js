document.addEventListener("DOMContentLoaded", Script);
function Script() {
    setTimeout(() => {
        let languageSession = localStorage.getItem("websiteLanguage") || "en";
        loadLanguageScript(languageSession);

        let languageEvent = document.getElementById('language');
        languageEvent.addEventListener("click", function () {
            languageSession = localStorage.getItem("websiteLanguage") || "en";
            let language = "";
            if (languageSession == "en") {
                localStorage.setItem("websiteLanguage", "np");
                sessionStorage.setItem("websiteLanguage", "np");
                language = "np";
            } else {
                localStorage.setItem("websiteLanguage", "en");
                sessionStorage.setItem("websiteLanguage", "en");
                language = "en";
            }

            loadLanguageScript(language);
            location.reload();
            // language();
        })

        changeLanguageInParameter(languageSession);
    }, 100)
}

function changeLanguageInParameter(language) {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    if (params.has("language")) {
        // Append language parameter
        params.set("language", language);
        
        // Update the URL without reloading
        window.history.replaceState({}, "", url.pathname + "?language=" + language);
    } else {
        window.location.href += "?language=" + language;
    }
}

//Header 
function setLanguageInHeader(language) {
    try {
        document.getElementById("message").textContent = language.message;
        document.getElementById("title").textContent = language.title;
        document.getElementById("home").textContent = language.home;
        document.getElementById("products").textContent = language.products;
        document.getElementById("accessories").textContent = language.accessories;
        document.getElementById("about_us_title").textContent = language.about_us_title;
        document.getElementById("log_out").textContent = language.log_out;
    } catch (e) { }
}

// Home Page 
function setLanguageInHomePage(language) {
    try {
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
        document.getElementById("black_tea_price").textContent = language.black_tea_price;
        document.getElementById("select_option").textContent = language.select_option1;
        document.getElementById("select_option1").textContent = language.select_option1;
        document.getElementById("select_option2").textContent = language.select_option1;
        document.getElementById("select_option3").textContent = language.select_option1;
        document.getElementById("flower_tea").textContent = language.flower_tea;
        document.getElementById("flower_tea_price").textContent = language.flower_tea_price;
        document.getElementById("select_option2").textContent = language.select_option2;
        document.getElementById("herbal_tea").textContent = language.herbal_tea;
        document.getElementById("herbal_tea_price").textContent = language.herbal_tea_price;
        document.getElementById("select_option3").textContent = language.select_option3;
        document.getElementById("green_tea").textContent = language.green_tea;
        document.getElementById("green_tea_price").textContent = language.green_tea_price;
        document.getElementById("select_option4").textContent = language.select_option4;
    } catch (e) {
    }
}
// Black Tea Section
function setLanguageInProducts(language) {
    try {
        document.querySelectorAll(".add_to_cart").forEach(button => {
            button.textContent = language.add_to_cart2;
        });

        // document.querySelectorAll(".category_name").forEach(button => {
        //     button.textContent = language.category_name;
        // });

        document.getElementById("black-tea_1").textContent = language.category_name1;
        document.getElementById("flower-tea_1").textContent = language.category_name2;
        document.getElementById("herbal-tea_1").textContent = language.category_name3;
        document.getElementById("green-tea_1").textContent = language.category_name4;
        
    } catch (e) {
    }
}
// TeapotSet and Accessories
function setLanguageInTeapotSetAndAccessories(language) {

    try {
        document.getElementById("title_of_accessories").textContent = language.title_of_accessories;
        document.getElementById("sub_title").textContent = language.sub_title;
        document.querySelectorAll(".add_to_cart").forEach(button => {
            button.textContent = language.add_to_cart2;
        });
    } catch (e) {
    }
}
// About Us
function setLanguageInAboutUs(language) {
    try {
        document.getElementById("about_us_description").textContent = language.about_us_description;
        document.getElementById("mission_title").textContent = language.mission_title;
        document.getElementById("mission_item_1").textContent = language.mission_item_1;
        document.getElementById("mission_item_2").textContent = language.mission_item_2;
        document.getElementById("mission_item_3").textContent = language.mission_item_3;
        document.getElementById("our_story_title").textContent = language.our_story_title;
        document.getElementById("our_story_description").textContent = language.our_story_description;
        document.getElementById("story_member_1").textContent = language.story_member_1;
        document.getElementById("story_member_2").textContent = language.story_member_2;
        document.getElementById("story_member_3").textContent = language.story_member_3;
        document.getElementById("about_us_title_1").textContent = language.about_us_title;
    } catch (e) {
    }
}
// Footer Of Webpage
function setLanguageInFooter(language) {
    try {
        document.getElementById("footer_about_us").textContent = language.footer_about_us;
        document.getElementById("footer_address").textContent = language.footer_address;
        document.getElementById("footer_phone").textContent = language.footer_phone;
        document.getElementById("footer_links_title").textContent = language.footer_links_title;
        document.getElementById("footer_link_about_us").textContent = language.footer_link_about_us;
        document.getElementById("footer_payment_title").textContent = language.footer_payment_title;
        document.getElementById("footer_payment_desc").textContent = language.footer_payment_desc;
        document.getElementById("footer_payment_icon_alt").textContent = language.footer_payment_icon_alt;
        document.getElementById("footer_bottom_copyright").textContent = language.footer_bottom_copyright;
    } catch (e) {
    }
}

// Add this function to your script.js file
function setLanguageInLogOut(language) {
    try {
        document.getElementById("log_out").textContent = language.log_out;
    } catch (e) { }
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
    script.src = `/Our_Project/language/language.${language}.js`;
    script.onload = () => {
        // console.log(`Loaded language.${language}.js`);
        // console.log(language === "en" ? window.LANG_EN : window.LANG_NP);
        window.languageObj = language === "en" ? window.LANG_EN : window.LANG_NP;

        setLanguageInHeader(window.languageObj);
        setLanguageInHomePage(window.languageObj);
        setLanguageInProducts(window.languageObj);
        setLanguageInTeapotSetAndAccessories(window.languageObj);
        setLanguageInAboutUs(window.languageObj);
        // setLanguageInLogOut(window.languageObj);
        setLanguageInFooter(window.languageObj);
    };
    script.onerror = () => {
        console.error(`Failed to load language.${language}.js`);
    };

    document.head.appendChild(script);
}
