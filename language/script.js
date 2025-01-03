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
                language = "np";
            } else {
                localStorage.setItem("websiteLanguage", "en");
                language = "en";
            }

            loadLanguageScript(language);
        })
    }, 100)
}

//Header 
function setLanguageInHeader(language) {
    try {
        document.getElementById("message").textContent = language.message;
        document.getElementById("title").textContent = language.title;
        document.getElementById("home").textContent = language.home;
        document.getElementById("products").textContent = products;
        document.getElementById("accessories").textContent = language.accessories;
        document.getElementById("about_us_title").textContent = language.about_us_title;
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
        document.getElementById("select_option1").textContent = language.select_option1;
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
        // document.getElementById("products").textContent = products;
        document.getElementById("black_tea_heading").textContent = language.black_tea_heading;
        document.getElementById("orthodox_tea").textContent = language.orthodox_tea;
        document.getElementById("orthodox_tea_price").textContent = language.orthodox_tea_price;
        document.getElementById("add_to_cart4").textContent = language.add_to_cart4;
        document.getElementById("ctc_tea").textContent = language.ctc_tea;
        document.getElementById("ctc_tea_price").textContent = language.ctc_tea_price;
        document.getElementById("add_to_cart5").textContent = language.add_to_cart5;
        document.getElementById("everest_tea").textContent = language.everest_tea;
        document.getElementById("everest_tea_price").textContent = language.everest_tea_price;
        document.getElementById("add_to_cart6").textContent = language.add_to_cart6;
        document.getElementById("golden_black_tea").textContent = language.golden_black_tea;
        document.getElementById("golden_black_tea_price").textContent = language.golden_black_tea_price;
        document.getElementById("add_to_cart7").textContent = language.add_to_cart7;

        // Flower Tea Section
        document.getElementById("flower_tea_heading").textContent = language.flower_tea_heading;
        document.getElementById("butterfly_pea_tea").textContent = language.butterfly_pea_tea;
        document.getElementById("butterfly_pea_tea_price").textContent = language.butterfly_pea_tea_price;
        document.getElementById("add_to_cart8").textContent = language.add_to_cart8;
        document.getElementById("chamomile_tea").textContent = language.chamomile_tea;
        document.getElementById("chamomile_tea_price").textContent = language.chamomile_tea_price;
        document.getElementById("add_to_cart9").textContent = language.add_to_cart9;
        document.getElementById("rose_tea").textContent = language.rose_tea;
        document.getElementById("rose_tea_price").textContent = language.rose_tea_price;
        document.getElementById("add_to_cart10").textContent = language.add_to_cart10;
        document.getElementById("hibiscus_tea").textContent = language.hibiscus_tea;
        document.getElementById("hibiscus_tea_price").textContent = language.hibiscus_tea_price;
        document.getElementById("add_to_cart11").textContent = language.add_to_cart11;

        // Herbal Tea Section
        document.getElementById("herbal_tea_heading").textContent = language.herbal_tea_heading;
        document.getElementById("tulsi_tea").textContent = language.tulsi_tea;
        document.getElementById("tulsi_tea_price").textContent = language.tulsi_tea_price;
        document.getElementById("add_to_cart12").textContent = language.add_to_cart12;
        document.getElementById("lemongrass_tea").textContent = language.lemongrass_tea;
        document.getElementById("lemongrass_tea_price").textContent = language.lemongrass_tea_price;
        document.getElementById("add_to_cart13").textContent = language.add_to_cart13;
        document.getElementById("oregano_tea").textContent = language.oregano_tea;
        document.getElementById("oregano_tea_price").textContent = language.oregano_tea_price;
        document.getElementById("add_to_cart14").textContent = language.add_to_cart14;
        document.getElementById("thyme_tea").textContent = language.thyme_tea;
        document.getElementById("thyme_tea_price").textContent = language.thyme_tea_price;
        document.getElementById("add_to_cart15").textContent = language.add_to_cart15;

        // Green Tea Section
        document.getElementById("green_tea_heading").textContent = language.green_tea_heading;
        document.getElementById("sencha_tea").textContent = language.sencha_tea;
        document.getElementById("sencha_tea_price").textContent = language.sencha_tea_price;
        document.getElementById("add_to_cart16").textContent = language.add_to_cart16;
        document.getElementById("hand_rolled_tea").textContent = language.hand_rolled_tea;
        document.getElementById("hand_rolled_tea_price").textContent = language.hand_rolled_tea_price;
        document.getElementById("add_to_cart17").textContent = language.add_to_cart17;
        document.getElementById("silver_needle_tea").textContent = language.silver_needle_tea;
        document.getElementById("silver_needle_tea_price").textContent = language.silver_needle_tea_price;
        document.getElementById("add_to_cart18").textContent = language.add_to_cart18;
        document.getElementById("himalayan_tea").textContent = language.himalayan_tea;
        document.getElementById("himalayan_tea_price").textContent = language.himalayan_tea_price;
        document.getElementById("add_to_cart19").textContent = language.add_to_cart19;
    } catch (e) {
    }
}
// TeapotSet and Accessories
function setLanguageInTeapotSetAndAccessories(language) {

    try {
        // document.getElementById("accessories").textContent = language.accessories;
        document.getElementById("travel_mug").textContent = language.travel_mug;
        document.getElementById("travel_mug_price").textContent = language.travel_mug_price;
        document.getElementById("add_to_cart1").textContent = language.add_to_cart1;
        document.getElementById("travel_mug_desc").textContent = language.travel_mug_desc;
        document.getElementById("porcelain_cup").textContent = language.porcelain_cup;
        document.getElementById("porcelain_cup_price").textContent = language.porcelain_cup_price;
        document.getElementById("add_to_cart2").textContent = language.add_to_cart2;
        document.getElementById("porcelain_cup_desc").textContent = language.porcelain_cup_desc;
        document.getElementById("ceramic_mug").textContent = language.ceramic_mug;
        document.getElementById("ceramic_mug_price").textContent = language.ceramic_mug_price;
        document.getElementById("add_to_cart3").textContent = language.add_to_cart3;
        document.getElementById("ceramic_mug_desc").textContent = language.ceramic_mug_desc;
    } catch (e) {
    }
}
// About Us
function setLanguageInAboutUs(language) {
    try {
        // document.getElementById("about_us_title").textContent = language.about_us_title;
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
        // console.log(`Loaded language.${language}.js`);
        // console.log(language === "en" ? window.LANG_EN : window.LANG_NP);
        window.languageObj = language === "en" ? window.LANG_EN : window.LANG_NP;

        setLanguageInHeader(window.languageObj);
        setLanguageInHomePage(window.languageObj);
        setLanguageInProducts(window.languageObj);
        setLanguageInTeapotSetAndAccessories(window.languageObj);
        setLanguageInAboutUs(window.languageObj);
        setLanguageInFooter(window.languageObj);
    };
    script.onerror = () => {
        console.error(`Failed to load language.${language}.js`);
    };

    document.head.appendChild(script);
}