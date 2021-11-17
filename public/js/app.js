// =============== //
// Modules imports //
// =============== //

// Import variables from `scss/abstracts/_variables.scss` from the 'content' property of the body::after pseudo class
// The variables are in a JSON format that gets converted into a JavaScript Object for access
import { importVariables } from "./modules/variables.js";

// Detection Module
// import { elementInView, getElementsInView } from "./modules/detection.js";

// ========================== //
// Components Modules imports //
// ========================== //

// Theme toggler Module Component
import { themeToggle } from "./modules/components/theme-toggler.js";


// === //
// App //
// === //

// Any code that needs to run after the document loads
document.addEventListener('DOMContentLoaded', () => {
})

// Any code that needs to run after the document fully loads with all the assets
window.addEventListener('load', () => {
    // Import the SASS variables as defined in `scss/abstracts/_variables.scss` into a property (sass_vars) of the window object
    importVariables();
    // Optional: Extract your SASS variables from the sass_vars object
    const colors = sass_vars.colors;
    const font_weight = sass_vars.font_weight;
    const breakpoints = sass_vars.breakpoints;
    // Optional: delete the sass_vars property of the window object if it's not used
    delete window.sass_vars;

    // Theme toggler (light/dark)
    const themeToggler = document.querySelector("#theme-toggler");
    themeToggle(themeToggler);
})