document.addEventListener('DOMContentLoaded', () => {
    // Console log to verify script load
    console.log('Privacy Platform Login Loaded');

    // Optional: Add simple interaction for the AI button
    const aiBtn = document.querySelector('.ai-fab');
    if (aiBtn) {
        aiBtn.addEventListener('click', () => {
            console.log('AI Help requested');
            // Future implementation: Open AI chat modal
            alert('AI Help coming soon!');
        });
    }
});
