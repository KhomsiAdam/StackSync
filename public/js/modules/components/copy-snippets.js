// Copy code snippets
export function copySnippet(buttons) {
    buttons.forEach(button => {
        // Reveal the buttons
        button.classList.remove("hidden")
        button.addEventListener('click', () => {
            if (!navigator.clipboard) {
                // Clipboard API not available
                return
              }
              // Get the id of the target to copy
              const copy_target_id = button.getAttribute('data-copy-target');
              // Get the target
              const copy_target = document.getElementById(copy_target_id);
              // Get the text of the target
              const text = copy_target.innerHTML
              // Write the text to the clipboard API
              navigator.clipboard.writeText(text)
              // Change the icon and color
              button.innerHTML = "assignment_turned_in";
              button.classList.add('copied');
              // Revert the icon and color
              setTimeout(() => {
                  button.innerHTML = "content_pasted";
                  button.classList.remove('copied');
              }, 2000);
      
        })
    });
}