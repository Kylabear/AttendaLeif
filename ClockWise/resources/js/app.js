import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('attendanceConfirm', (messages) => ({
    showConfirm: false,
    message: '',
    pendingForm: null,
    ask(action, form) {
        if (!form) return;
        this.pendingForm = form;
        this.message = action === 'in' ? messages.in : messages.out;
        this.showConfirm = true;
    },
    confirm() {
        if (!this.pendingForm) return;
        const form = this.pendingForm;
        const action = form.getAttribute('data-clock-action');
        if (action === 'in') {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.textContent = messages.clockedInLabel;
            }
        }
        this.showConfirm = false;
        this.pendingForm = null;
        form.submit();
    },
    cancel() {
        this.showConfirm = false;
        this.pendingForm = null;
    },
}));

Alpine.start();
