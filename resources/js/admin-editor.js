import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';

document.addEventListener('DOMContentLoaded', () => {
    const editorHost = document.getElementById('tiptap-editor');
    const contentInput = document.querySelector('textarea[name="content"]');
    const htmlInput = document.querySelector('input[name="content_html"]');

    if (!editorHost || !contentInput) {
        return;
    }

    const editor = new Editor({
        element: editorHost,
        extensions: [StarterKit],
        content: contentInput.value ? `<p>${contentInput.value.replace(/\n\n/g, '</p><p>').replace(/\n/g, '<br>')}</p>` : '',
        onUpdate: ({ editor: ed }) => {
            contentInput.value = ed.getText();
            if (htmlInput) {
                htmlInput.value = ed.getHTML();
            }
        },
    });

    const form = editorHost.closest('form');

    form?.addEventListener('submit', () => {
        contentInput.value = editor.getText();
        if (htmlInput) {
            htmlInput.value = editor.getHTML();
        }
    });
});
