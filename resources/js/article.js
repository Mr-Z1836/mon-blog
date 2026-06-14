import Prism from 'prismjs';
import 'prismjs/themes/prism-tomorrow.css';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-bash';

document.addEventListener('DOMContentLoaded', () => {
    Prism.highlightAll();

    const progressBar = document.getElementById('reading-progress');
    const article = document.getElementById('article-content');

    const updateProgress = () => {
        if (!progressBar || !article) {
            return;
        }

        const rect = article.getBoundingClientRect();
        const total = article.offsetHeight - window.innerHeight;

        if (total <= 0) {
            progressBar.style.width = '100%';

            return;
        }

        const scrolled = Math.min(Math.max(-rect.top, 0), total);
        const percent = Math.round((scrolled / total) * 100);
        progressBar.style.width = `${percent}%`;
    };

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    const hideAllInlineForms = () => {
        document.querySelectorAll('.reply-form-container, .edit-form-container').forEach((container) => {
            container.classList.add('hidden');
        });
    };

    document.querySelectorAll('.reply-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.parent;

            if (!commentId) {
                return;
            }

            const container = document.querySelector(`.reply-form-container[data-comment-id="${commentId}"]`);

            if (!container) {
                return;
            }

            const isOpen = !container.classList.contains('hidden');
            hideAllInlineForms();

            if (!isOpen) {
                container.classList.remove('hidden');
                container.querySelector('textarea')?.focus();
            }
        });
    });

    document.querySelectorAll('.reply-cancel-btn').forEach((button) => {
        button.addEventListener('click', () => {
            button.closest('.reply-form-container')?.classList.add('hidden');
        });
    });

    document.querySelectorAll('.edit-comment-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.commentId;

            if (!commentId) {
                return;
            }

            const container = document.querySelector(`.edit-form-container[data-comment-id="${commentId}"]`);

            if (!container) {
                return;
            }

            const isOpen = !container.classList.contains('hidden');
            hideAllInlineForms();

            if (!isOpen) {
                container.classList.remove('hidden');
                container.querySelector('textarea')?.focus();
            }
        });
    });

    document.querySelectorAll('.edit-cancel-btn').forEach((button) => {
        button.addEventListener('click', () => {
            button.closest('.edit-form-container')?.classList.add('hidden');
        });
    });

    document.querySelectorAll('.reply-form-container').forEach((container) => {
        const textarea = container.querySelector('textarea[name="comment_content"]');

        if (textarea?.value.trim()) {
            hideAllInlineForms();
            container.classList.remove('hidden');
            textarea.focus();
        }
    });

    document.querySelectorAll('.edit-form-container').forEach((container) => {
        if (!container.classList.contains('hidden')) {
            hideAllInlineForms();
            container.classList.remove('hidden');
            container.querySelector('textarea')?.focus();
        }
    });
});
