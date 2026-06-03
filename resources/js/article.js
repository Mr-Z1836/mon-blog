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

        const readUrl = article.dataset.readUrl;

        if (readUrl && percent > 0 && percent % 25 === 0) {
            fetch(readUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ progress_percent: percent }),
            }).catch(() => {});
        }
    };

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    document.querySelectorAll('.reply-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById('comment-parent-id');

            if (input) {
                input.value = button.dataset.parent ?? '';
            }

            document.querySelector('[name="comment_content"]')?.focus();
        });
    });
});
