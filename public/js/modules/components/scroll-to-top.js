// Scroll to top handling
export function scrollToTop(parent, y_offset) {
    // Create the scroll to top button
    let stt_button = document.createElement('div');
    // Classes assignement
    stt_button.setAttribute('class', 'scroll-to-top ss-circle ss-shadow-3 material-icons hidden');
    // Icons are from Google Fonts Material Icons
    stt_button.innerHTML = 'keyboard_double_arrow_up';
    // Append the button to the specified parent
    parent.appendChild(stt_button);
    // Select the scroll to top button
    let scrollToTop = document.querySelector('.scroll-to-top');
    // Show/Hide scroll to top button depending and the Y offset of the page
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > y_offset) {
            scrollToTop.classList.remove('hidden')
        } else if (window.pageYOffset < y_offset) {
            scrollToTop.classList.add('hidden')
        }
    })
    // Scroll to the top of the page on click
    let dom_elem = document.documentElement;
    scrollToTop.addEventListener('click', () => {
        dom_elem.scrollTo({
            top: 0,
            behavior: 'smooth'
        })
    })
}