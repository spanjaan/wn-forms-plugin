document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');

    if (!form) {
        console.warn('Form not found.');
        return;
    }

    form.addEventListener('ajaxDone', () => {
        try {
            // Reset the form if required
            form.reset();
    
            // Check if FilePond is defined and remove files if so
            if (typeof pond !== "undefined") {
                pond.removeFiles();
            }
        } catch (error) {
            console.error('Error handling form submission:', error);
        }
    });
});
